<?php

declare(strict_types=1);

namespace App\Domain;

/**
 * @property int $id
 * @property string $email
 * @property \App\Domain\User\Status $status
 * @property string $password
 * @property \Illuminate\Database\Eloquent\Collection $roles
 */
class User extends \Illuminate\Database\Eloquent\Model
{
    /**
     * @inheritDoc
     */
    public $timestamps = false;

    /**
     * @inheritDoc
     */
    protected $table = 'user';

    /**
     * @inheritDoc
     */
    protected $casts = [
        'status' => \App\Domain\User\Status::class,
    ];

    /**
     * @inheritDoc
     */
    protected $fillable = [
        'email',
        'status',
        'password',
    ];

    /**
     * @inheritDoc
     */
    protected $hidden = [
        'password',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(
            \App\Domain\Role::class,
            'user_role',
            'user_id',
            'role_code'
        );
    }
}
