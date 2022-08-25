<?php

declare(strict_types=1);

namespace Factorit\ComposerWorkspacePlugin\Command;

use Composer\Command\BaseCommand as ComposerBaseCommand;
use Factorit\ComposerWorkspacePlugin\WorkspaceConfig;

abstract class BaseCommand extends ComposerBaseCommand
{
    public function __construct(protected WorkspaceConfig $workspaceConfig, string $name = null)
    {
        parent::__construct($name);
    }
}
