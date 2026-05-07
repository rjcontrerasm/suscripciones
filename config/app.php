<?php

return [
    'name' => 'ARPYNET Renovaciones',
    'base_url' => getenv('APP_URL') ?: 'http://localhost:8000',
    'timezone' => 'America/Lima',
    'uploads_dir' => __DIR__ . '/../public/uploads',
    'alerts_days' => [7, 15, 30],
    'smtp' => [
        'enabled' => false,
        'host' => getenv('SMTP_HOST') ?: '',
        'port' => (int)(getenv('SMTP_PORT') ?: 587),
        'user' => getenv('SMTP_USER') ?: '',
        'pass' => getenv('SMTP_PASS') ?: '',
        'from' => getenv('SMTP_FROM') ?: 'no-reply@arpynet.com',
    ],
];
