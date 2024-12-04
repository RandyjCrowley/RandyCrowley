<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RealTimeVitals extends Model
{
    use HasFactory;

    // Specify the table name (optional if it's plural form)
    protected $table = 'real_time_vitals';

    // Specify the fillable attributes to guard against mass assignment
    protected $fillable = [
        'dsn',
        'charge_status',
        'heart_rate',
        'movement',
        'sensor_code',
        'status_code',
        'base_station_on',
        'battery_percentage',
        'battery_temperature',
        'charging_status',
        'alert_status',
        'ota_status',
        'sensor_fault',
        'rssi',
        'sensor_base_status',
        'sensor_status',
        'movement_vibration',
        'timestamp',
        'oxygen_saturation',
        'operation_mode',
        'base_station_status',
        'mode_status',
        'hardware_version',
        'error',
    ];
}
