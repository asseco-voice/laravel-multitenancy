<?php

declare(strict_types=1);

namespace Asseco\Multitenancy\App\Actions;

use Asseco\Multitenancy\App\Events\ForgettingCurrentTenant;
use Asseco\Multitenancy\App\Events\ForgotCurrentTenant;
use Asseco\Multitenancy\App\Models\Tenant;
use Asseco\Multitenancy\App\Tasks\SwitchTenantTask;

class ForgetCurrentTenant extends AbstractAction
{
    public function execute(Tenant $tenant): self
    {
        ForgettingCurrentTenant::dispatch($tenant);

        $this->tasksCollection->each(
            fn (SwitchTenantTask $task) => $task->forgetCurrent()
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
