<?php

declare(strict_types=1);

namespace Factorit\ComposerWorkspacePlugin;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Json\JsonFile;
use Composer\Package\RootPackageInterface;
use Composer\Plugin\Capable;
use Composer\Plugin\PluginInterface;
use Composer\Util\Platform;

final class Plugin implements PluginInterface, Capable
{
    private WorkspaceConfig $workspaceRoot;

    public function activate(Composer $composer, IOInterface $io)
    {
        $package = $composer->getPackage();
        if ($this->isWorkspaceRoot($package)) {
            $this->workspaceRoot = WorkspaceConfig::fromArray(
                Platform::getCwd(),
                $package->getExtra()['workspace']
            );
        } else {
            $workspaceRoot = Factory::findWorkspaceRootOrThrow();
            $composerFile = new JsonFile($workspaceRoot . DIRECTORY_SEPARATOR . Factory::getComposerFile());

            $this->workspaceRoot = WorkspaceConfig::fromJsonFile($composerFile);
        }
    }

    private function isWorkspaceRoot(RootPackageInterface $package): bool
    {
        return isset($package->getExtra()['workspace']);
    }

    public function deactivate(Composer $composer, IOInterface $io)
    {
        // NoOp
    }

    public function uninstall(Composer $composer, IOInterface $io)
    {
        // NoOp
    }

    public function getCapabilities(): array
    {
        return [
            \Composer\Plugin\Capability\CommandProvider::class => CommandProvider::class,
        ];
    }

    public function getWorkspaceConfig(): WorkspaceConfig
    {
        return $this->workspaceRoot;
    }
}
