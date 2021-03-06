<?php

use Asseco\Multitenancy\App\Models\Tenant;
use Asseco\Multitenancy\App\Tasks\SwitchTenantDatabase;
use Asseco\Multitenancy\App\TenantResolvers\ByDomain;

return [

    /**
     * Class responsible for resolving the tenants.
     * Default resolving is by domain.
     */
    'tenant_resolver'              => ByDomain::class,

    /**
     * Tenant model.
     */
    'tenant_model'                 => Tenant::class,

    /**
     * Key to bound the current tenant to.
     * You'll be able to fetch it with app('currentTenant').
     */
    'current_tenant_container_key' => 'currentTenant',

    /**
     * Tasks to be performed when switching tenants.
     * Task must implement SwitchTenantTask interface.
     */
    'switch_tenant_tasks'          => [
        SwitchTenantDatabase::class,
    ],
];
