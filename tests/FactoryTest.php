<?php

declare(strict_types=1);

namespace Factorit\ComposerWorkspacePlugin\Tests;

use Factorit\ComposerWorkspacePlugin\Factory;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    use FilesystemTestTrait;

    public function testCanFindWorkspaceRootWhenItIsCurrentDirectory(): void
    {
        $workspaceRoot = Factory::findWorkspaceRoot();

        $this->assertSame($this->workspaceRoot, $workspaceRoot);
    }

    public function testCanFindWorkspaceRootWhenItIsParentDirectory(): void
    {
        $this->createComposerFile();
        $this->createComposerFile([], 'packages/package');
        $this->changeWorkingDir('packages/package');

        $workspaceRoot = Factory::findWorkspaceRoot();

        $this->assertSame($this->workspaceRoot, $workspaceRoot);
    }

    protected function setUp(): void
    {
        $this->setupWorkspaceRoot();
    }

    protected function tearDown(): void
    {
        $this->teardownWorkspaceRoot();
    }
}
