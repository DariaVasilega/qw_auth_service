<?php

declare(strict_types=1);

namespace App\Factory;

/**
 * @method \App\Application\SearchResultPageInterface create(array $arguments = [])
 */
final class SearchResultPageFactory extends AbstractFactory
{
    protected const INSTANCE = \App\Application\SearchResultPageInterface::class;
}
