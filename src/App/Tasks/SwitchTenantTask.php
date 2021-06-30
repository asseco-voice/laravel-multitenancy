<?php

declare(strict_types=1);

namespace Asseco\Multitenancy\Tasks;

use Asseco\Multitenancy\Models\Tenant;

interface SwitchTenantTask
{
    public function makeCurrent(Tenant $tenant): void;

    public function forgetCurrent(): void;
}
