<?php

declare(strict_types=1);

namespace App\Support;

trait PhinxCommandWrapper
{
    private const NAMESPACE = 'migrations';

    private const CONFIG = 'app' . DIRECTORY_SEPARATOR . 'phinx-config.php';

    /**
     * @var \League\Flysystem\PathPrefixer $pathNormalizer
     */
    private \League\Flysystem\PathPrefixer $pathNormalizer;

    /**
     * @param \League\Flysystem\PathPrefixer $pathNormalizer
     * @param string|null $name
     */
    public function __construct(
        \League\Flysystem\PathPrefixer $pathNormalizer,
        string $name = null
    ) {
        parent::__construct($name);

        $this->pathNormalizer = $pathNormalizer;
    }

    /**
     * @inheritDoc
     */
    protected function configure(): void
    {
        parent::configure();

        $this->setName(self::NAMESPACE . ':' . static::NAME);
    }

    /**
     * @inheritDoc
     */
    protected function execute(
        \Symfony\Component\Console\Input\InputInterface $input,
        \Symfony\Component\Console\Output\OutputInterface $output
    ): int {
        $input->getOption('configuration')
            ?: $input->setOption('configuration', $this->pathNormalizer->prefixPath(self::CONFIG));

        return parent::execute($input, $output);
    }
}
