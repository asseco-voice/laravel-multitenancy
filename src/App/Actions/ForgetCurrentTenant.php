<?php

declare(strict_types=1);

namespace Asseco\Multitenancy\Actions;

use Asseco\Multitenancy\Events\ForgettingCurrentTenant;
use Asseco\Multitenancy\Events\ForgotCurrentTenant;
use Asseco\Multitenancy\Models\Tenant;
use Asseco\Multitenancy\Tasks\SwitchTenantTask;

class ForgetCurrentTenant extends AbstractAction
{
    public function execute(Tenant $tenant): self
    {
        ForgettingCurrentTenant::dispatch($tenant);

        $this->tasksCollection->each(
            fn(SwitchTenantTask $task) => $task->forgetCurrent()
        );

        $this->clearBoundCurrentTenant();

        ForgotCurrentTenant::dispatch($tenant);

        return $this;
    }

    private function clearBoundCurrentTenant()
    {
        $containerKey = config('asseco-multitenancy.current_tenant_container_key');

        app()->forgetInstance($containerKey);
    }
}
