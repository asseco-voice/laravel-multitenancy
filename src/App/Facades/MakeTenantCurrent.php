<?php

declare(strict_types=1);

namespace Asseco\Multitenancy\Facades;

use Asseco\Multitenancy\Models\Tenant;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \Asseco\Multitenancy\Actions\MakeTenantCurrent execute(Tenant $tenant)
 *
 * @see \Asseco\Multitenancy\Actions\MakeTenantCurrent
 */
class MakeTenantCurrent extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'make-tenant-current';
    }
}
