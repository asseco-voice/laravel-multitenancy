<?php

declare(strict_types=1);

namespace Asseco\Multitenancy\App\Facades;

use Asseco\Multitenancy\App\Models\Tenant;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \Asseco\Multitenancy\App\Actions\ForgetCurrentTenant execute(Tenant $tenant)
 *
 * @see \Asseco\Multitenancy\App\Actions\ForgetCurrentTenant
 */
class ForgetCurrentTenant extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'forget-current-tenant';
    }
}
