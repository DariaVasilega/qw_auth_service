<?php

declare(strict_types=1);

return [
    'required' => ':Attribute must not be empty.',
    'format' => ':Attribute must be in :format format.',
    'unique' => 'This :attribute is already registered.',
    'in' => ':Attribute must be one of the listed values: "%s".',
    'min.string' => ':Attribute must be longer than :min character(s).',
    'max.string' => ':Attribute must be less than :max character(s).',
    'password.mixed' => ':Attribute must contain at least one uppercase and one lowercase letter.',
    'password.letters' => ':Attribute must contain at least one letter.',
    'password.symbols' => ':Attribute must contain at least one symbol.',
    'password.numbers' => ':Attribute must contain at least one number.',
    'request.parameter.type' => 'The ":parameter" parameter is required and must be type of ":type"',
];
