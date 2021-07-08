<?php

declare(strict_types=1);

namespace Asseco\Multitenancy\App\Commands;

use Asseco\Multitenancy\App\Commands\Traits\TenantAware;
use Illuminate\Database\Console\Migrations\MigrateCommand as BaseCommand;

class MigrateCommand extends BaseCommand
{
    use TenantAware;

    public BaseCommand $command;

    public function __construct(BaseCommand $command)
    {
        $this->command = $command;

        parent::__construct($command->migrator, $command->dispatcher);
    }

    public function __call($method, $parameters)
    {
        return $this->command->{$method}($parameters);
    }
}
