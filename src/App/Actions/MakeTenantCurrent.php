<?php

declare(strict_types=1);

namespace Asseco\Multitenancy\Actions;

use Asseco\Multitenancy\Events\MakingTenantCurrent;
use Asseco\Multitenancy\Events\TenantMadeCurrent;
use Asseco\Multitenancy\Models\Tenant;
use Asseco\Multitenancy\Tasks\SwitchTenantTask;

class MakeTenantCurrent extends AbstractAction
{
    public function execute(Tenant $tenant): self
    {
        MakingTenantCurrent::dispatch($tenant);

        $this->tasksCollection->each(
            fn (SwitchTenantTask $task) => $task->makeCurrent($tenant)
        );

        $this->bindAsCurrentTenant($tenant);

        TenantMadeCurrent::dispatch($tenant);

        return $this;
    }

    protected function bindAsCurrentTenant(Tenant $tenant): self
    {
        $containerKey = config('asseco-multitenancy.current_tenant_container_key');

        app()->forgetInstance($containerKey);

        app()->instance($containerKey, $tenant);

        return $this;
    }
}
