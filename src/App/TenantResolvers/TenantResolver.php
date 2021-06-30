<?php

declare(strict_types=1);

namespace Asseco\Multitenancy\TenantResolvers;

use Asseco\Multitenancy\Models\Tenant;
use Illuminate\Http\Request;

interface TenantResolver
{
    public function resolve(Request $request): ?Tenant;
}
