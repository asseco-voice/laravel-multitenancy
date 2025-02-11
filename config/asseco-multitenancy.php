<?php

use Asseco\Multitenancy\App\Models\Tenant;
use Asseco\Multitenancy\App\Tasks\PrefixCacheTask;
use Asseco\Multitenancy\App\Tasks\SwitchTenantDatabase;
use Asseco\Multitenancy\App\TenantResolvers\ByRequestHeaderOrUserToken;

return [

    'enabled' => filter_var(env('MULTI_TENANCY_ENABLED', false), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),

    /**
     * Class responsible for resolving the tenants.
     * Default resolving is by request header or user token.
     */
    'tenant_resolver' => ByRequestHeaderOrUserToken::class,

    /**
     * Header key where Tenant identifier is sent.
     */
    'request_header_tenant_key' => env('ASSECO_MULTITENANCY_REQUEST_TENANT_KEY', 'X-Tenant-ID'),

    /**
     * works with: asseco-voice/laravel-jwt-authentication.
     */
    'token_user_tenant_claim_key' => env('ASSECO_MULTITENANCY_TOKEN_USER_TENANT_CLAIM_KEY', 'tenant-id'),

    /**
     * Tenant model.
     */
    'tenant_model' => Tenant::class,

    /**
     * Key to bound the current tenant to.
     * You'll be able to fetch it with app('currentTenant').
     */
    'current_tenant_container_key' => 'currentTenant',

    /**
     * Tasks to be performed when switching tenants.
     * Task must implement SwitchTenantTask interface.
     */
    'switch_tenant_tasks' => [
        SwitchTenantDatabase::class,
        PrefixCacheTask::class,
    ],
];
