<?php

return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
    'user.passwordResetTokenExpire' => 3600,
    'user.passwordMinLength' => 8,
    'jwt' => [
        'issuer' => 'https://api.example.com',
        'audience' => 'https://frontend.example.com',
        'id' => 'UNIQUE-JWT-IDENTIFIER',
        'expire' => 3600,
    ],
];
