<?php

declare(strict_types=1);

namespace Asseco\Multitenancy\App\TenantResolvers;

use Asseco\Multitenancy\App\Models\Tenant;
use Asseco\Multitenancy\App\Traits\UsesTenantModel;
use Illuminate\Http\Request;

class ByRequestHeaderOrUserToken implements TenantResolver
{
    use UsesTenantModel;

    public function resolve(Request $request): ?Tenant
    {
        $headerKey = config('asseco-multitenancy.request_header_tenant_key');
        $tenantId = $request->header($headerKey);

        if (empty($tenantId)) {
            $claimKey = config('asseco-multitenancy.token_user_tenant_claim_key');
            $tenantId = optional(auth()->user())->get($claimKey);
            $tenantId = $tenantId ?: 'default';
        }

        /** @var Tenant $tenant */
        $tenant = $this->getTenantModel()::query()
            ->where('id', $tenantId)
            ->orWhere('name', $tenantId)
            ->first();

        return $tenant;
    }
}
