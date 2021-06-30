<?php

declare(strict_types=1);

namespace Asseco\Multitenancy;

use Asseco\Multitenancy\Models\Tenant;
use Asseco\Multitenancy\TenantResolvers\TenantResolver;
use Asseco\Multitenancy\Traits\UsesTenantModel;
use Illuminate\Http\Request;

class ByDomain implements TenantResolver
{
    use UsesTenantModel;

    public function resolve(Request $request): ?Tenant
    {
        $host = $request->getHost();

        /** @var Tenant $tenant */
        $tenant = $this->getTenantModel()::query()
            ->where('domain', $host)
            ->first();

        return $tenant;
    }
}
