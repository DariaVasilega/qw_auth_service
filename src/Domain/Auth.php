<?php

declare(strict_types=1);

namespace App\Domain;

/**
 * @property string $email
 * @property string $token
 * @property string $expiration
 * @property \App\Domain\User $user
 */
class Auth extends \Illuminate\Database\Eloquent\Model
{
    /**
     * @inheritDoc
     */
    protected $table = 'auth';

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
    protected $primaryKey = 'email';

    /**
     * @inheritDoc
     */
    protected $fillable = [
        'email',
        'token',
        'expiration',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(\App\Domain\User::class, 'email');
    }
}
