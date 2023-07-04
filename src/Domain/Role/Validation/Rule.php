<?php

declare(strict_types=1);

namespace App\Domain\Role\Validation;

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
    public function getRules(string $roleCode = '', bool $ignoreCode = false): array
    {
        $uniqRule = \Illuminate\Validation\Rule::unique('role', 'code');

        return [
            'code' => [
                'required',
                'max:63',
                'regex:/^[A-z0-9_-]*$/',
                $ignoreCode ? $uniqRule->ignore($roleCode, 'code') : $uniqRule,
            ],
            'label' => [
                'required',
                'max:255',
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    public function getMessages(): array
    {
        return [
            'code.required' => $this->translator->get('validation.required'),
            'code.max' => $this->translator->get('validation.max.string'),
            'code.regex' => $this->translator->get('role.validation.code.regex'),
            'code.unique' => $this->translator->get('validation.unique'),
            'label.required' => $this->translator->get('validation.required'),
            'label.max' => $this->translator->get('validation.max.string'),
        ];
    }
}
