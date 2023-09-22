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
        ],
        'relation_admin_role_permission_admin_dashboard' => [
            'role_code' => 'admin',
            'permission_code' => 'admin_dashboard',
        ],
        'relation_admin_role_permission_latest_lection_statistic' => [
            'role_code' => 'admin',
            'permission_code' => 'latest_lection_statistic',
        ],
        'relation_admin_role_permission_most_perspective_users_statistic' => [
            'role_code' => 'admin',
            'permission_code' => 'most_perspective_users_statistic',
        ],
        'relation_admin_role_permission_lections_statistic' => [
            'role_code' => 'admin',
            'permission_code' => 'lections_statistic',
        ],
        'relation_admin_role_permission_user_changes_statistic' => [
            'role_code' => 'admin',
            'permission_code' => 'user_changes_statistic',
        ],
    ],
];
