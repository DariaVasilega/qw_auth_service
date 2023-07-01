<?php

declare(strict_types=1);

namespace App\Support;

final class Fixtures
{
    private const FIXTURES_PATH = 'resources' . DIRECTORY_SEPARATOR . 'fixtures';

    private const FIXTURES_EXTENSIONS_REGEX = '/(\.php|\.yml|\.yaml|\.json)$/i';

    /**
     * @var \Nelmio\Alice\Loader\NativeLoader $fixturesLoader
     */
    private \Nelmio\Alice\Loader\NativeLoader $fixturesLoader;

    /**
     * @var \League\Flysystem\Filesystem $filesystem
     */
    private \League\Flysystem\Filesystem $filesystem;

    /**
     * @var \League\Flysystem\PathPrefixer $pathNormalizer
     */
    private \League\Flysystem\PathPrefixer $pathNormalizer;

    /**
     * @param \Nelmio\Alice\Loader\NativeLoader $fixturesLoader
     * @param \League\Flysystem\Filesystem $filesystem
     * @param \League\Flysystem\PathPrefixer $pathNormalizer
     */
    public function __construct(
        \Nelmio\Alice\Loader\NativeLoader $fixturesLoader,
        \League\Flysystem\Filesystem $filesystem,
        \League\Flysystem\PathPrefixer $pathNormalizer
    ) {
        $this->fixturesLoader = $fixturesLoader;
        $this->filesystem = $filesystem;
        $this->pathNormalizer = $pathNormalizer;
    }

    /**
     * Retrieve absolute fixtures paths as array
     *
     * @param string $fixturesPath
     * @return array
     * @throws \League\Flysystem\FilesystemException
     */
    public function getFixturesPaths(string $fixturesPath = self::FIXTURES_PATH): array
    {
        $directoryListing = $this->filesystem->listContents($fixturesPath, \League\Flysystem\Filesystem::LIST_DEEP);
        $directoryListing = $directoryListing->filter(
            static fn (\League\Flysystem\StorageAttributes $attributes): bool =>
                $attributes->isFile() && preg_match(self::FIXTURES_EXTENSIONS_REGEX, $attributes->path())
        );
        $directoryListing = $directoryListing->map(
            fn (\League\Flysystem\StorageAttributes $attributes): string =>
                $this->pathNormalizer->prefixPath($attributes->path())
        );

        return $directoryListing->toArray();
    }

    /**
     * @param array $files
     * @param array $parameters
     * @param array $objects
     * @return array
     * @throws \Nelmio\Alice\Throwable\LoadingThrowable
     */
    public function getFixturesData(
        array $files,
        array $parameters = [],
        array $objects = []
    ): array {
        $fixturesObjectSet = $this->fixturesLoader->loadFiles($files, $parameters, $objects);
        $groupedFixturesData = [];

        /**
         * @var string $rowName
         * @var \Illuminate\Database\Eloquent\Model $entity
         */
        foreach ($fixturesObjectSet->getObjects() as $rowName => $entity) {
            $groupedFixturesData[$entity->getTable()][$rowName] = $entity->getAttributes();
        }

        return $groupedFixturesData;
    }
}
