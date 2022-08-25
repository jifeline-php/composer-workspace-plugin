<?php

declare(strict_types=1);

namespace Factorit\ComposerWorkspacePlugin;

use Composer\Util\Platform;

final class Factory
{
    public static function findWorkspaceRootOrThrow(): string
    {
        $workspaceRoot = self::findWorkspaceRoot();

        if (!file_exists($workspaceRoot.DIRECTORY_SEPARATOR.self::getComposerFile())) {
            $cwd = Platform::getCwd(true);
            throw new \Exception(
                sprintf(
                    'No %s found in %s or any of its parent directories. Did you run the composer init command?',
                    self::getComposerFile(),
                    '' === $cwd ? '.' : $cwd
                )
            );
        }

        return $workspaceRoot;
    }

    public static function findWorkspaceRoot(): string
    {
        $cwd = Platform::getCwd(true);
        $workspaceFile = self::getComposerFile();

        $dir = dirname($cwd);
        $home = realpath(Platform::getEnv('HOME') ?: Platform::getEnv('USERPROFILE') ?: '/');

        // abort when we reach the home dir or top of the filesystem
        while (dirname($dir) !== $dir && $dir !== $home) {
            if (file_exists($dir.'/'.$workspaceFile)) {
                break;
            }
            $dir = dirname($dir);
        }

        if (!file_exists($dir . DIRECTORY_SEPARATOR . $workspaceFile)) {
            return $cwd;
        }

        return $dir;
    }

    public static function getComposerFile(): string
    {
        return 'composer.json';
    }
}
