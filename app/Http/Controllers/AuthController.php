<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Provider;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('login');
    }

    public function logout(): RedirectResponse
    {
        Auth::logout();
        Session::flush();

        return redirect('/');
    }

    public function redirectToProvider($provider): RedirectResponse
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider): RedirectResponse
    {
        try {
            $socialUser = Socialite::driver($provider)->user();

            // Start a database transaction
            return DB::transaction(function () use ($socialUser, $provider) {
                // First check if we have a user with this provider
                $providerUser = Provider::where('provider_name', $provider)
                    ->where('provider_id', $socialUser->getId())
                    ->first();

                if ($providerUser && $providerUser->user->id == 1) {

                    Auth::login($providerUser->user);

                    return redirect()->intended(route('home'));
                }

                // Then check if we have a user with this email
                $user = User::where('email', $socialUser->getEmail())->first();

                if (! $user) {
                    // Create new user if none exists
                    $user = User::create([
                        'name' => $socialUser->getName(),
                    ]);
                }

                // Create the provider record
                $user->providers()->create([
                    'provider_name' => $provider,
                    'provider_id' => $socialUser->getId(),
                ]);

                if ($user->id == 1) {
                    Auth::login($user);
                }

                return redirect()->intended(route('home'));
            });
        } catch (Exception $e) {
            return redirect('/login')
                ->with('error', 'Something went wrong with ' . $provider . ' login: ' . $e->getMessage());
        }
    }
}
