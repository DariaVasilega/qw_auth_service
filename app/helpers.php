<?php

declare(strict_types=1);

use App\Application\Settings\SettingsInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Psr\Container\ContainerInterface;

// phpcs:disable PSR1.Files.SideEffects.FoundWithSymbols, PSR1.Classes.ClassDeclaration.MissingNamespace

final class HelperRegistry
{
    /**
     * @var \Psr\Container\ContainerInterface $container
     */
    public static ContainerInterface $container;

    /**
     * @var \App\Application\Settings\SettingsInterface $settings
     */
    public static SettingsInterface $settings;

    /**
     * @param \Psr\Container\ContainerInterface $container
     * @return void
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    private function setup(ContainerInterface $container): void
    {
        self::$container = $container;
        self::$settings = $container->get(SettingsInterface::class);
    }

    /**
     * Register helpers
     *
     * @param \Psr\Container\ContainerInterface $container
     * @return void
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): void
    {
        $this->setup($container);

        if (!function_exists('array_pluck')) {
            /**
             * Pluck an array of values from an array.
             *
             * @param iterable $array
             * @param string|array|int|null $value
             * @param string|array|null $key
             * @return array
             * @phpstan-ignore-next-line
             */
            function array_pluck(iterable $array, string|array|int|null $value, string|array|null $key = null): array
            {
                return Arr::pluck($array, $value, $key);
            }
        }

        if (!function_exists('studly_case')) {
            /**
             * Convert a value to studly caps case.
             *
             * @param string $value
             * @return string
             * @phpstan-ignore-next-line
             */
            function studly_case(string $value): string
            {
                return Str::studly($value);
            }
        }

        if (!function_exists('config')) {
            /**
             * Get the specified configuration value.
             *
             * @param string|null $key
             * @param mixed|null $default
             * @return mixed
             * @phpstan-ignore-next-line
             */
            function config(string $key = null, mixed $default = null): mixed
            {
                return Arr::get(HelperRegistry::$settings->get(), $key, $default);
            }
        }

        if (!function_exists('container')) {
            /**
             * Retrieve class instance from DI Container.
             *
             * @param string|null $className
             * @return object
             * @phpstan-ignore-next-line
             */
            function container(string $className = null): object
            {
                if ($className === null) {
                    return HelperRegistry::$container;
                }

                return HelperRegistry::$container->get($className);
            }
        }
    }
}

return new HelperRegistry();
