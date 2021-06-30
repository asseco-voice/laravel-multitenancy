<?php

declare(strict_types=1);

namespace Asseco\Multitenancy\Actions;

use Asseco\Multitenancy\Models\Tenant;
use Asseco\Multitenancy\Tasks\TasksCollection;

abstract class AbstractAction
{
    protected TasksCollection $tasksCollection;

    public function __construct(TasksCollection $tasksCollection)
    {
        $this->tasksCollection = $tasksCollection;
    }

    abstract public function execute(Tenant $tenant): self;
}
