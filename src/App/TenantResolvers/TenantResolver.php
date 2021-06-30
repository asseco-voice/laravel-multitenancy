<?php

declare(strict_types=1);

namespace Asseco\Multitenancy\App\TenantResolvers;

use Asseco\Multitenancy\App\Models\Tenant;
use Illuminate\Http\Request;

interface TenantResolver
{
    public function resolve(Request $request): ?Tenant;
}
