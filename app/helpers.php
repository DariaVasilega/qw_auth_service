<?php

declare(strict_types=1);

use App\Application\Settings\SettingsInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Psr\Container\ContainerInterface;

// phpcs:disable

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
             * @param string $key
             * @param mixed|null $default
             * @return mixed
             */
            function config(string $key = '', mixed $default = null): mixed
            {
                return Arr::get(HelperRegistry::$settings->get(), $key, $default);
            }
        }
    }
}

return new HelperRegistry();
