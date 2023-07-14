<?php

declare(strict_types=1);

namespace App\Domain;

/**
 * @property string $code
 * @property string $label
 * @property \Illuminate\Database\Eloquent\Collection $permissions
 */
class Role extends \Illuminate\Database\Eloquent\Model
{
    /**
     * @inheritDoc
     */
    public $timestamps = false;

    /**
     * @inheritDoc
     */
    public $incrementing = false;

    /**
     * @inheritDoc
     */
    protected $primaryKey = 'code';

    /**
     * @inheritDoc
     */
    protected $table = 'role';

    /**
     * @inheritDoc
     */
    protected $fillable = [
        'code',
        'label',
    ];

    /**
     * @inheritDoc
     */
    protected $hidden = [
        'pivot',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(
            \App\Domain\Permission::class,
            'role_permission',
            'role_code',
            'permission_code'
        );
    }
}
