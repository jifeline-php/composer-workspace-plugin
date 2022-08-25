<?php

declare(strict_types=1);

namespace Factorit\ComposerWorkspacePlugin;

use Composer\Plugin\Capability\CommandProvider as ComposerCommandProvider;
use Factorit\ComposerWorkspacePlugin\Command\Initialize;

final class CommandProvider implements ComposerCommandProvider
{
    private Plugin $plugin;

    public function __construct(array $args)
    {
        ['plugin' => $this->plugin] = $args;
    }

    public function getCommands(): array
    {
        $workspaceConfig = $this->plugin->getWorkspaceConfig();

        return [
            new Initialize($workspaceConfig),
        ];
    }

}
