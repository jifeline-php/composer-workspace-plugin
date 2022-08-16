<?php

declare(strict_types=1);

namespace Factorit\ComposerWorkspacePlugin\Command;

use Composer\Command\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class Merge extends BaseCommand
{
    protected function configure(): void
    {
        $this->setName('workspace:merge');
        $this->setDescription('Merge all workspace package dependencies into the root composer');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Executing merge');

        return self::SUCCESS;
    }
}
