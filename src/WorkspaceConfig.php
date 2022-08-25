<?php

declare(strict_types=1);

namespace Factorit\ComposerWorkspacePlugin;

use Composer\Json\JsonFile;
use InvalidArgumentException;

final class WorkspaceConfig
{
    /** @var string[] */
    private array $packagePaths = [];

    private function __construct()
    {
        // NoOp
    }

    public static function fromJsonFile(JsonFile $composerFile): self
    {
        $config = $composerFile->read();

        return self::fromArray($config['extra']['workspace'] ?? []);
    }

    public static function fromArray(array $data): self
    {
        $config = new self();

        if (isset($data['paths']) && is_array($data['paths'])) {
            $config->setPackagePaths($data['paths']);
        }

        return $config;
    }

    public function addPackagePath(string $path): void
    {
        $this->packagePaths[] = $path;
    }

    public function getPackagePaths(): array
    {
        return $this->packagePaths;
    }

    public function setPackagePaths(array $paths): void
    {
        $this->packagePaths = [];
        foreach ($paths as $path) {
            if (is_string($path)) {
                $this->addPackagePath($path);
            } else {
                throw new InvalidArgumentException(
                    sprintf(
                        'Package path must be a string, got %s',
                        gettype($path)
                    )
                );
            }
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $packagePaths = $this->packagePaths;
        sort($packagePaths);

        return [
            'paths' => $packagePaths,
        ];
    }
}
