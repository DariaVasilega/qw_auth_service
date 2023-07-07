<?php

declare(strict_types=1);

namespace App\Domain;

/**
 * @property string $code
 * @property string $label
 */
class Permission extends \Illuminate\Database\Eloquent\Model
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
    protected $table = 'permission';

    /**
     * @inheritDoc
     */
    protected $fillable = [
        'code',
        'label',
    ];
}
