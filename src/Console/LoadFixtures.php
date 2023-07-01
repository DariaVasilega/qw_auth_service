<?php

declare(strict_types=1);

namespace App\Console;

final class LoadFixtures extends \Symfony\Component\Console\Command\Command
{
    /**
     * @var \App\Support\Fixtures $fixturesHelper
     */
    private \App\Support\Fixtures $fixturesHelper;

    /**
     * @var \Illuminate\Database\ConnectionInterface $connection
     */
    private \Illuminate\Database\ConnectionInterface $connection;

    /**
     * @param \App\Support\Fixtures $fixturesHelper
     * @param \Illuminate\Database\Capsule\Manager $capsule
     * @param string|null $name
     */
    public function __construct(
        \App\Support\Fixtures $fixturesHelper,
        \Illuminate\Database\Capsule\Manager $capsule,
        string $name = null
    ) {
        parent::__construct($name);

        $this->fixturesHelper = $fixturesHelper;
        $this->connection = $capsule->getConnection();
    }

    /**
     * @inheritDoc
     */
    protected function configure(): void
    {
        $this->setName('fixtures:load');
        $this->setDescription('Load fixtures');
        $this->addOption(
            'truncate',
            't',
            \Symfony\Component\Console\Input\InputOption::VALUE_NONE,
            'Clear fixtures related tables'
        );
        $this->addOption(
            'keep',
            'k',
            \Symfony\Component\Console\Input\InputOption::VALUE_NONE,
            'Keep fixtures related tables uncleared'
        );
    }

    /**
     * @inheritDoc
     * @SuppressWarnings(PHPMD.UnusedFormalParameters)
     */
    protected function execute(
        \Symfony\Component\Console\Input\InputInterface $input,
        \Symfony\Component\Console\Output\OutputInterface $output
    ): int {
        $helper = $this->getHelper('question');
        $question = new \Symfony\Component\Console\Question\ConfirmationQuestion(
            'Do you want to clear fixtures related tables? [Y/n] ',
            false
        );

        $keep = $input->getOption('keep');
        $truncate = $input->getOption('truncate');

        if ($keep && $truncate) {
            throw new \Symfony\Component\Console\Exception\InvalidOptionException(
                'You can indicate only one option'
            );
        }

        $keepRelatedTables = $keep || (!$truncate && !$helper->ask($input, $output, $question));

        try {
            $fixturesPaths = $this->fixturesHelper->getFixturesPaths();
            $fixturesData = $this->fixturesHelper->getFixturesData($fixturesPaths);

            $keepRelatedTables ?: $this->clearRelatedTables(array_keys($fixturesData));

            $this->connection->beginTransaction();

            /** @phpstan-ignore-next-line */
            $schemaBuilder = $this->connection->getSchemaBuilder();
            $schemaBuilder->disableForeignKeyConstraints();

            $this->uploadFixtures($fixturesData);

            $schemaBuilder->enableForeignKeyConstraints();

            $this->connection->commit();
        } catch (\Throwable $exception) {
            try {
                $this->connection->rollBack();
            } catch (\Throwable $exception) {
                $this->throwException($exception);
            }

            $this->throwException($exception);
        }

        $output->writeln('<info>Fixtures have been loaded successfully</info>');

        return self::SUCCESS;
    }

    /**
     * @param array $tables
     * @return void
     */
    private function clearRelatedTables(array $tables): void
    {
        array_walk($tables, fn (string $table) => $this->connection->table($table)->truncate());
    }

    /**
     * @param array $fixturesData
     * @return void
     */
    private function uploadFixtures(array $fixturesData): void
    {
        array_walk(
            $fixturesData,
            fn (array $data, string $table): bool => $this->connection->table($table)->insert($data)
        );
    }

    /**
     * @param \Throwable $throwable
     * @return void
     */
    private function throwException(\Throwable $throwable): void
    {
        throw new \Symfony\Component\Console\Exception\RuntimeException(
            $throwable->getMessage()
        );
    }
}
