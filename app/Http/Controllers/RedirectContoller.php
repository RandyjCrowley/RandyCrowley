<?php

declare(strict_types=1);

namespace App\Http\Controllers;

class RedirectContoller extends Controller
{
    const ALLOWED_DOMAINS = [
        'portainer',
        'vw',
    ];

    public function redirect($to)
    {
        if (! in_array($to, self::ALLOWED_DOMAINS, true)) {
            return redirect()->back()->withErrors(['Invalid domain']);
        }

        $domain = config('app.url');
        $baseDomain = str_replace('https://', '', $domain);

        return redirect()->away("https://{$to}." . $baseDomain);

    }
}
