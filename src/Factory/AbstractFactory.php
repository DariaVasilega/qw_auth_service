<?php

declare(strict_types=1);

namespace App\Factory;

abstract class AbstractFactory
{
    protected const INSTANCE = '';

    /**
     * @var \Psr\Container\ContainerInterface $container
     */
    protected \Psr\Container\ContainerInterface $container;

    /**
     * @param \Psr\Container\ContainerInterface $container
     */
    public function __construct(
        \Psr\Container\ContainerInterface $container
    ) {
        $this->container = $container;
    }

    /**
     * @param array $arguments
     * @return object
     */
    public function create(array $arguments = []): object
    {
        return $this->container->make(static::INSTANCE, $arguments);
    }
}
