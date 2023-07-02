<?php

declare(strict_types=1);

namespace App\Domain\User\Validation;

use App\Domain\User\Status as UserStatus;
use Illuminate\Validation\Rules\Password;

class Rule implements \App\Application\ValidationRuleInterface
{
    /**
     * @var \Illuminate\Translation\Translator $translator
     */
    private \Illuminate\Translation\Translator $translator;

    /**
     * @param \Illuminate\Translation\Translator $translator
     */
    public function __construct(
        \Illuminate\Translation\Translator $translator
    ) {
        $this->translator = $translator;
    }

    /**
     * @inheritDoc
     */
    public function getRules(int $userId = 0): array
    {
        return [
            'email' => [
                'required',
                'email',
                "unique:user,email,$userId",
            ],
            'status' => 'in:' . implode(',', UserStatus::values()),
            'password' => [
                'required',
                'max:256',
                Password::min(8)->letters()->numbers()->symbols()->mixedCase(),
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    public function getMessages(): array
    {
        return [
            'email.required' => $this->translator->get('validation.required'),
            'email.email' => $this->translator->get('validation.format', ['format' => 'email@example.com']),
            'email.unique' => $this->translator->get('validation.unique'),
            'status.in' => sprintf(
                $this->translator->get('validation.in'),
                array_reduce(
                    UserStatus::values(),
                    fn ($own, $val) => ($own ? "$own\", \"" : $own)
                        . $this->translator->get(UserStatus::label(UserStatus::from($val)))
                )
            ),
            'password.required' => $this->translator->get('validation.required'),
            'password.max' => $this->translator->get('validation.max.string'),
        ];
    }
}
