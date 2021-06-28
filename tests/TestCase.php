<?php

declare(strict_types=1);

namespace Asseco\Multitenancy\Tests;

use Asseco\Multitenancy\MultitenancyServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->runLaravelMigrations();
    }

    protected function getPackageProviders($app): array
    {
        return [MultitenancyServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        // perform environment setup
    }
}
