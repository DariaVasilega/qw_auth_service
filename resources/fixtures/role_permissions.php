<?php

declare(strict_types=1);

$entity = new class extends \Illuminate\Database\Eloquent\Model {
    /**
     * @inheritDoc
     */
    protected $table = 'role_permission';
};

$permissions = [];

$entityTypes = [
    'user',
    'role',
    'permission',
    'position',
    'position_history',
    'lection',
    'test',
    'question',
    'answer',
    'score'
];

foreach ($entityTypes as $entityType) {
    foreach (['create', 'read', 'update', 'delete'] as $action) {
        $permissions[] = "${entityType}_${action}";
    }
}

return [
    $entity::class => [
        'relation_admin_role_permission_{' . implode(', ', $permissions) . '}' => [
            'role_code' => 'admin',
            'permission_code' => '<current()>',
        ]
    ],
];
