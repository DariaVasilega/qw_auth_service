<?php

declare(strict_types=1);

namespace App\Application\Actions\User;

abstract class Show extends Action
{
    /**
     * User object preparation
     *
     * @param \App\Domain\User $user
     * @return \App\Domain\User
     */
    protected function prepare(\App\Domain\User $user): \App\Domain\User
    {
        foreach ($user::HIDDEN_ATTRIBUTES as $attribute) {
            unset($user->{$attribute});
        }

        if (isset($user->status)) {
            $user->availability = [
                'label' => $this->translator->get($user->status::label($user->status)),
                'status' => $user->status,
            ];
        }

        return $user;
    }
}
