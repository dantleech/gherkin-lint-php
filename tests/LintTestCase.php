<?php

namespace DTL\GherkinLint\Tests;

use DTL\GherkinLint\Tests\Util\Workspace;
use PHPUnit\Framework\TestCase;

class LintTestCase extends TestCase
{
    protected function setUp(): void
    {
        $this->workspace()->reset();
    }
    public function workspace(): Workspace
    {
        return new Workspace(__DIR__ . '/Workspace');
    }
}
