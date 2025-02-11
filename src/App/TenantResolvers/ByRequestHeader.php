<?php

declare(strict_types=1);

namespace Asseco\Multitenancy\App\TenantResolvers;

use Asseco\Multitenancy\App\Models\Tenant;
use Asseco\Multitenancy\App\Traits\UsesTenantModel;
use Illuminate\Http\Request;

class ByRequestHeader implements TenantResolver
{
    use UsesTenantModel;

    public function resolve(Request $request): ?Tenant
    {
        $headerKey = config('asseco-multitenancy.request_header_tenant_key');
        $tenantId = $request->header($headerKey, Tenant::DEFAULT_TENANTS_NAME);

        /** @var Tenant $tenant */
        $tenant = $this->getTenantModel()::query()
            ->where('id', $tenantId)
            ->orWhere('name', $tenantId)
            ->first();

        return $tenant;
    }
}
