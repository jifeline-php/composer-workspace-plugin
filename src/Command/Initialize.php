<?php

declare(strict_types=1);

namespace Factorit\ComposerWorkspacePlugin\Command;

use Composer\Command\BaseCommand;
use Composer\Config\JsonConfigSource;
use Composer\Json\JsonFile;
use Composer\Util\Filesystem;
use Composer\Util\Platform;
use Factorit\ComposerWorkspacePlugin\Factory;
use Factorit\ComposerWorkspacePlugin\WorkspaceConfig;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class Initialize extends BaseCommand
{
    protected function configure(): void
    {
        $this->setName('workspace:init');
        $this->setDescription('Initialize workspace in current directory');
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $io = $this->getIO();

        $question = 'Would you like to define the package locations interactively [<comment>yes</comment>]? ';
        $packages = $input->getOption('packages');
        $packagePaths = [];
        if (count($packages) > 0 || $io->askConfirmation($question)) {
            $packagePaths = $this->determinePackagePaths($packages);
        }
        $input->setOption('packages', $packagePaths);
    }

    private function determinePackagePaths(array $packages): array
    {
        if (count($packages) > 0) {
            return $this->normalizePackagePaths($packages);
        }

        $io = $this->getIO();
        while (null !== $package = $io->ask('Add path to packages: ')) {
            $packages[] = $package;
        }

        return $packages;
    }

    private function normalizePackagePaths(array $packagePaths): array
    {
        $fs = new Filesystem();

        return array_filter(
            array_map(
                static function ($packagePath) use ($fs): string {
                    return Platform::expandPath($fs->normalizePath($packagePath));
                },
                $packagePaths
            )
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = $this->getIO();

        $composerFile = new JsonFile(Factory::getComposerFile());
        $configSource = new JsonConfigSource($composerFile);

        $workspaceRootConfig = WorkspaceConfig::fromJsonFile($composerFile);
        $workspaceRootConfig->setPackagePaths($input->getOption('packages'));

        $configSource->addProperty('extra.workspace', $workspaceRootConfig->toArray());

        $io->write('<info>Workspace configuration added to composer.json</info>');

        return self::SUCCESS;
    }
}
