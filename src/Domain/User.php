<?php

declare(strict_types=1);

namespace App\Domain;

/**
 * @property int $id
 * @property string $email
 * @property \App\Domain\User\Status $status
 * @property string $password
 */
class User extends \Illuminate\Database\Eloquent\Model
{
    public const HIDDEN_ATTRIBUTES = [
        'password',
    ];

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
        'password'
    ];
}
