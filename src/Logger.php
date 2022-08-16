<?php

declare(strict_types=1);

namespace Factorit\ComposerWorkspacePlugin;

use Composer\IO\IOInterface;

final class Logger
{
    public function __construct(private IOInterface $io)
    {
    }

    public function debug(string ...$messages): void
    {
        foreach ($messages as $message) {
            $this->io->debug(sprintf('[workspace] %s', $message));
        }
    }
}
