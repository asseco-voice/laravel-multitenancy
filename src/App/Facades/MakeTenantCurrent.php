<?php

declare(strict_types=1);

namespace Asseco\Multitenancy\App\Facades;

use Asseco\Multitenancy\App\Models\Tenant;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \Asseco\Multitenancy\App\Actions\MakeTenantCurrent execute(Tenant $tenant)
 *
 * @see \Asseco\Multitenancy\App\Actions\MakeTenantCurrent
 */
class MakeTenantCurrent extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'make-tenant-current';
    }
}
