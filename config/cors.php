<?php

return [

    'paths' => ['api/*'],

    'allowed_methods' => ['*'],

    /*
    |--------------------------------------------------------------------------
    | PENTING: karena supports_credentials = true, ini TIDAK BOLEH '*'.
    | Harus persis URL frontend (protokol + host + port).
    |--------------------------------------------------------------------------
    */
    'allowed_origins' => [
        env('FRONTEND_URL', 'http://localhost:3000'),
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    /*
    |--------------------------------------------------------------------------
    | HARUS true — cuma refresh_token cookie yang lewat sini, tapi browser
    | butuh flag ini true di request DAN response supaya cookie httpOnly
    | boleh dikirim/diterima lintas origin (localhost:3000 <-> :8000).
    |--------------------------------------------------------------------------
    */
    'supports_credentials' => true,

];