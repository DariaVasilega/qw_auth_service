<?php

declare(strict_types=1);

namespace App\Application;

interface ValidationRuleInterface
{
    /**
     * Returns validation rules for \Illuminate\Validation\Validator
     *
     * @return array
     */
    public function getRules(): array;

    /**
     * Returns error messages for \Illuminate\Validation\Validator
     *
     * @return array
     */
    public function getMessages(): array;
}
