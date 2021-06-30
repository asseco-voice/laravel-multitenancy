<?php

declare(strict_types=1);

namespace Asseco\Multitenancy\App\Actions;

use Asseco\Multitenancy\App\Models\Tenant;
use Asseco\Multitenancy\App\Tasks\TasksCollection;

abstract class AbstractAction
{
    protected TasksCollection $tasksCollection;

    public function __construct(TasksCollection $tasksCollection)
    {
        $this->tasksCollection = $tasksCollection;
    }

    abstract public function execute(Tenant $tenant): self;
}
