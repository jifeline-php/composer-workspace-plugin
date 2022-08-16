<?php
declare(strict_types=1);

namespace Factorit\ComposerWorkspacePlugin;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\Capability\CommandProvider as ComposerCapabilityCommandProvider;
use Composer\Plugin\Capable;
use Composer\Plugin\CommandEvent;
use Composer\Plugin\PluginEvents;
use Composer\Plugin\PluginInterface;

final class Plugin implements PluginInterface, Capable
{
    private Logger $logger;

    public function activate(Composer $composer, IOInterface $io)
    {
        $this->logger = new Logger($io);

        $this->logger->debug('activated plugin');
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
            ComposerCapabilityCommandProvider::class => CommandProvider::class
        ];
    }


}
