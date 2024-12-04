<?php

declare(strict_types=1);

namespace App\Helper;

use App\Models\RealTimeVitals;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class Owlet
{
    private string $username = '';

    private string $password = '';

    private array $config = [];

    private string $jwt = '';

    private string $miniToken = '';

    private string $access_token = '';

    private array $device_props = [];

    private array $headers = [];

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->username = config('owlet.user');
        $this->password = config('owlet.pass');
        $this->config = config('owlet.config');
        $this->jwt = Cache::get('jwt') ?? '';
        $this->miniToken = Cache::get('miniToken') ?? '';
        $this->access_token = Cache::get('access_token') ?? '';

    }

    public function main()
    {
        while (true) {
            try {
                $this->login();
                $this->fetchDSN();

                foreach ($this->fetch_props() as $prop) {
                    foreach ($prop as $key => $value) {
                        $this->record_vitals($value);
                    }
                }

            }
            catch (Exception $e) {
                $this->logError($e->getMessage());
                sleep(60);
            }
        }
    }

    private function reactivate($url_activate)
    {
        $data = [
            'datapoint' => [
                'value' => '1',
            ],
        ];

        // Send the POST request with the payload
        $response = Http::withHeaders(['Authorization' => $this->access_token])
            ->post($url_activate, $data);

        // Check for a successful response (status code 200)
        if ($response->getStatusCode() !== 201) {
            throw new Exception('Request failed with status code! ' . $response);
        }

    }

    private function fetch_props()
    {
        $new_props = [];
        foreach ($this->device_props as $key => $device) {
            $next_url_activate = $device['url_activate'];
            $next_url_props = $device['url_properties'];

            // Sleep for 5 seconds before fetching properties
            sleep(5);  // Wait for 5 seconds before making the GET request

            // Generate a unique cache key for each device activation
            $cacheKey = 'device_last_activated_' . $key;

            // Check if reactivation is needed (only if 30 seconds have passed)
            $lastActivated = Cache::get($cacheKey);
            if (! $lastActivated || now()->diffInSeconds($lastActivated) >= 30) {
                // Reactivate the device
                $this->reactivate($next_url_activate);

                // Cache the current time to prevent reactivation for the next 30 seconds
                Cache::put($cacheKey, now(), 60); // Store the timestamp for 60 seconds
            }

            // Send GET request to fetch properties
            $r = Http::withHeaders($this->headers)->get($next_url_props);

            // Check for errors
            if ($r->getStatusCode() !== 200) {
                throw new Exception('Request failed with status code ' . $r->getStatusCode());
            }

            $props = $r->json();

            // Process and append properties
            foreach ($props as $prop) {
                $n = $prop['property']['name'];

                if (is_string($n) && $n == 'REAL_TIME_VITALS' || $n == 'CHARGE_STATUS') {
                    $device_props[$n] = $prop['property'];
                    $device_props[$n]['device'] = $device['dsn'];

                    $new_props[] = $device_props;
                }
            }
        }

        return $new_props;
    }

    public function login()
    {
        if (! $this->jwt) {
            $apiKey = $this->config['apiKey'];
            $url = $this->config['google_login'] . $apiKey;

            $data = [
                'email' => $this->username,
                'password' => $this->password,
                'returnSecureToken' => true,
            ];

            $response = $this->post($url, $data);

            if (isset($response['idToken'])) {
                Cache::put('jwt', $response['idToken'], now()->addHour());
                $this->jwt = $response['idToken'];
            }
            else {
                throw new Exception('Failed to retrieve JWT.');
            }
        }

        if (! $this->miniToken) {
            $urlMini = $this->config['url_mini'];
            $miniTokenResponse = Http::withToken($this->jwt)->get($urlMini)->json();

            if (isset($miniTokenResponse['mini_token'])) {
                Cache::put('miniToken', $miniTokenResponse['mini_token'], now()->addHour());
                $this->miniToken = $miniTokenResponse['mini_token'];
            }
        }

        if (! $this->access_token) {
            $urlSignIn = $this->config['url_signin'];
            $signinData = [
                'app_id' => $this->config['app_id'],
                'app_secret' => $this->config['app_secret'],
                'provider' => 'owl_id',
                'token' => $this->miniToken,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
            ];
            $signinResponse = $this->post($urlSignIn, $signinData, []);
            if (isset($signinResponse['access_token'])) {
                $this->access_token = $signinResponse['access_token'];
                Cache::put('access_token', $signinResponse['access_token'], $signinResponse['expires_in']);
                $this->logError('Access token expires in ' . time() + $signinResponse['expires_in'] - 60 . ' seconds.');
                $this->headers = [
                    'Authorization' => 'Bearer ' . $signinResponse['access_token'],
                ];
            }
        }
        else {
            $this->headers = [
                'Authorization' => 'Bearer ' . $this->access_token,
            ];
        }
    }

    private function fetchDSN()
    {
        $this->device_props = Cache::rememberForever(
            'dsn',
            function () {
                $url = $this->config['url_base'] . 'devices.json';

                $devices = Http::withToken($this->access_token)->get($url)->json();

                if (count($devices) < 1) {
                    throw new Exception('Found zero Owlet monitors.');
                }

                $devicesNew = [];
                foreach ($devices as $key => $device) {
                    $deviceSn = $device['device']['dsn'];
                    $devicesNew[$key]['dsn'] = $deviceSn;
                    $devicesNew[$key]['url_properties'] = $this->config['url_base'] . 'dsns/' . $deviceSn . '/properties.json';
                    $devicesNew[$key]['url_activate'] = $this->config['url_base'] . 'dsns/' . $deviceSn . '/properties/APP_ACTIVE/datapoints.json';
                }

                return $devicesNew;
            }
        );
    }

    public function record_vitals($p)
    {
        $data = json_decode($p['value'], true);
        $name = $p['name'];
        $device = $p['device'] ?? null;  // Assuming DSN is part of the input data
        $newData = match ($name) {
            'REAL_TIME_VITALS' => [
                'dsn' => $device,
                'charge_status' => $data['chg'] ?? null,
                'heart_rate' => (string) $data['hr'] ?? null,
                'movement' => (string) $data['mv'] ?? null,
                'sensor_code' => (string) $data['sc'] ?? null,
                'status_code' => (string) $data['st'] ?? null,
                'base_station_on' => $data['bso'] ?? null,
                'battery_percentage' => (string) $data['bat'] ?? null,
                'battery_temperature' => (string) $data['btt'] ?? null,
                'charging_status' => (string) $data['chg'] ?? null,
                'alert_status' => (string) $data['alrt'] ?? null,
                'ota_status' => (string) $data['ota'] ?? null,
                'sensor_fault' => (string) $data['srf'] ?? null,
                'rssi' => (string) $data['rsi'] ?? null,
                'sensor_base_status' => (string) $data['sb'] ?? null,
                'sensor_status' => (string) $data['ss'] ?? null,
                'movement_vibration' => (string) $data['mvb'] ?? null,
                'timestamp' => date('Y-m-d H:i:s') ?? null,
                'oxygen_saturation' => (string) $data['oxta'] ?? null,
                'operation_mode' => (string) $data['onm'] ?? null,
                'base_station_status' => (string) $data['bsb'] ?? null,
                'mode_status' => (string) $data['mrs'] ?? null,
                'hardware_version' => (string) $data['hw'] ?? null,
                'error' => false,
            ],

            'CHARGE_STATUS' => [
                'dsn' => $device,
                'charge_status' => $data['value'] ?? null,
                'error' => false,
            ],

            default => [
                'dsn' => $device,
                'error' => true,
            ],
        };
        RealTimeVitals::create($newData);
    }

    public function record($s)
    {
        echo $s . "\n";
        flush(); // Ensure the output is immediately sent to the browser or terminal
    }

    private function post(string $url, array $data, array $headers = [])
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge(['Content-Type: application/json'], $headers));
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            logError('cURL error: ' . curl_error($ch));
        }
        curl_close($ch);

        return json_decode($response, true);
    }

    public function logError(string $message)
    {
        echo $message . "\n";
    }
}
