<?php

declare(strict_types=1);

namespace Asseco\Multitenancy\Facades;

use Asseco\Multitenancy\Models\Tenant;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \Asseco\Multitenancy\Actions\ForgetCurrentTenant execute(Tenant $tenant)
 *
 * @see \Asseco\Multitenancy\Actions\ForgetCurrentTenant
 */
class ForgetCurrentTenant extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'forget-current-tenant';
    }
}
