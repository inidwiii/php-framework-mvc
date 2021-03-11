<?php

return [
    'before' => [
        \App\Http\Middleware\CSRFVerifyMiddleware::class,
    ],

    'first' => \App\Http\Middleware\FirstMiddleware::class,
];