<?php

declare(strict_types=1);

return [
    'region' => env('OWLET_REGION', 'world'),
    'user' => env('OWLET_USER', 'Owlet'),
    'pass' => env('OWLET_PASS', 'Owlet'),
    'config' => [
        'google_login' => 'https://identitytoolkit.googleapis.com/v1/accounts:signInWithPassword?key=',
        'url_mini' => 'https://ayla-sso.owletdata.com/mini/',
        'url_signin' => 'https://ads-owlue1.aylanetworks.com/api/v1/token_sign_in.json',
        'url_base' => 'https://ads-owlue1.aylanetworks.com/apiv1/',
        'apiKey' => env('OWLET_KEY', 'Owlet'),
        'app_id' => 'owa-rg-id',
        'app_secret' => 'owa-dx85qljgtR6hmVflyrL6LasCxA8',
    ],
];
