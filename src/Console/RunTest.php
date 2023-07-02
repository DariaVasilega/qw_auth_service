<?php

declare(strict_types=1);

namespace App\Console;

final class RunTest extends \Symfony\Component\Console\Command\Command
{
    private const PHPUNIT = 'vendor' . DIRECTORY_SEPARATOR . 'bin' . DIRECTORY_SEPARATOR . 'phpunit';

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
    public function configure(): void
    {
        $this->setName('test:run');
        $this->setDescription('Run test');
        $this->addArgument(
            'path',
            \Symfony\Component\Console\Input\InputArgument::OPTIONAL,
            'Testable file or directory path'
        );
    }

    /**
     * @inheritDoc
     */
    protected function execute(
        \Symfony\Component\Console\Input\InputInterface $input,
        \Symfony\Component\Console\Output\OutputInterface $output
    ): int {
        $phpunit = $this->pathNormalizer->prefixPath(self::PHPUNIT);
        $testablePath = $input->getArgument('path');

        exec("$phpunit $testablePath --color=always", $response, $statusCode);

        $output->write($response, true);

        return $statusCode;
    }
}
