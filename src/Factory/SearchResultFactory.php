<?php

declare(strict_types=1);

namespace App\Factory;

/**
 * @method \App\Application\SearchResultInterface create(array $arguments = [])
 */
final class SearchResultFactory extends AbstractFactory
{
    protected const INSTANCE = \App\Application\SearchResultInterface::class;
}
