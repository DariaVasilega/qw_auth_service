<?php

declare(strict_types=1);

namespace App\Factory;

/**
 * @method \App\Application\SearchCriteriaInterface create(array $arguments = [])
 */
final class SearchCriteriaFactory extends AbstractFactory
{
    protected const INSTANCE = \App\Application\SearchCriteriaInterface::class;
}
