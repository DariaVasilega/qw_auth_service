<?php

declare(strict_types=1);

namespace App\Observer\User;

final class RelatedRoles
{
    /**
     * Handle the user "saving" event.
     *
     * @param  \App\Domain\User $user
     * @return void
     */
    public function saving(\App\Domain\User $user): void
    {
        $user->setAppends(['roles' => $this->retrieveRoles($user)]);
    }

    /**
     * Handle the user "saved" event.
     *
     * @param  \App\Domain\User $user
     * @return void
     */
    public function saved(\App\Domain\User $user): void
    {
        $appends = $user->getAppends();
        $this->process($user, $appends['roles']);
        $user->setAppends([]);
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\Domain\User  $user
     * @return void
     */
    public function updated(\App\Domain\User $user): void
    {
        $this->process($user, $this->retrieveRoles($user));
    }

    /**
     * Retrieve roles and unset them from user entity
     *
     * @param \App\Domain\User $user
     * @return array|null
     */
    private function retrieveRoles(\App\Domain\User $user): ?array
    {
        /** @phpstan-ignore-next-line */
        $roles = $user->role;

        unset($user->role);

        if ($roles === null) {
            return null;
        }

        return is_array($roles) ? $roles : [];
    }

    /**
     * Process related roles
     *
     * @param \App\Domain\User $user
     * @param array|null $roles
     * @return void
     */
    private function process(\App\Domain\User $user, ?array $roles): void
    {
        if ($roles === null) {
            return;
        }

        empty($roles)
            ? $user->roles()->detach()
            : $user->roles()->sync($roles);
    }
}
