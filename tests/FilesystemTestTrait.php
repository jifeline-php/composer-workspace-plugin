<?php

declare(strict_types=1);

namespace Factorit\ComposerWorkspacePlugin\Tests;

use Composer\Util\Filesystem;

trait FilesystemTestTrait
{
    private string|false $workspaceRoot;
    private string|false $previousCwd;

    private function setupWorkspaceRoot(): void
    {
        $this->previousCwd = getcwd();

        $tmpDir = sys_get_temp_dir().DIRECTORY_SEPARATOR.uniqid('workspace-');
        $this->ensureDirectoryExistsAndClear($tmpDir);

        $realTmpDir = realpath($tmpDir);

        chdir($realTmpDir);
        $this->workspaceRoot = $realTmpDir;
    }

    private function ensureDirectoryExistsAndClear(string $directory): void
    {
        if (is_dir($directory)) {
            $fs = new Filesystem();
            $fs->removeDirectory($directory);
        }

        $this->ensureDirectoryExists($directory);
    }

    private function ensureDirectoryExists(string $directory): void
    {
        $fs = new Filesystem();
        $fs->ensureDirectoryExists($directory);
    }

    private function teardownWorkspaceRoot(): void
    {
        chdir($this->previousCwd);
        exec('rm -rf '.$this->workspaceRoot);
    }

    private function createComposerFile(array $contents = [], string $directory = '.'): void
    {
        $this->ensureDirectoryExists($directory);
        file_put_contents($directory.DIRECTORY_SEPARATOR.'composer.json', json_encode($contents, JSON_PRETTY_PRINT));
    }

    private function createDirectory(string $path): void
    {
        $this->ensureDirectoryExists($path);
    }

    private function changeWorkingDir(string $directory): void
    {
        $this->ensureDirectoryExists($directory);
        chdir($directory);
    }
}
