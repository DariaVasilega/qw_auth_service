<?php

declare(strict_types=1);

return [
    \App\Domain\User::class => [
        'user{1..99}' => [
            'id (unique)' => '<current()>',
            'email (unique)' => '<email()>',
            'status' => '<((int) !($current % 5 === 0))>',
            'password' => '<(container(\App\Service\Password::class)->hash(\'q1w2e3r4\'))>',
        ],
    ],
];
