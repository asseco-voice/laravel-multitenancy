<?php

declare(strict_types=1);

namespace Asseco\Multitenancy\App\Traits;

use Asseco\Multitenancy\App\Models\Tenant;

trait UsesTenantModel
{
    public function getTenantModel(): Tenant
    {
        $tenantModelClass = config('asseco-multitenancy.tenant_model');

        return new $tenantModelClass();
    }
}
