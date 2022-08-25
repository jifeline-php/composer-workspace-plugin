<?php

declare(strict_types=1);

namespace Factorit\ComposerWorkspacePlugin;

use Composer\Plugin\Capability\CommandProvider as ComposerCommandProvider;
use Factorit\ComposerWorkspacePlugin\Command\Initialize;

final class CommandProvider implements ComposerCommandProvider
{
    public function getCommands(): array
    {
        return [
            new Initialize(),
        ];
    }

}
