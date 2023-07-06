<?php

declare(strict_types=1);

$entity = new class extends \Illuminate\Database\Eloquent\Model {
    /**
     * @inheritDoc
     */
    protected $table = 'user_role';
};

return [
    $entity::class => [
        'relation_{1..99}' => [
            'user_id (unique)' => '<current()>',
            'role_code' => '@role_user->code',
        ],
        'admin_relation' => [
            'user_id' => '@user_dasha->id',
            'role_code' => '@role_admin->code'
        ]
    ],
];
