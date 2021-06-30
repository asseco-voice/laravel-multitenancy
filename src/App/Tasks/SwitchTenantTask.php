<?php

declare(strict_types=1);

namespace Asseco\Multitenancy\App\Tasks;

use Asseco\Multitenancy\App\Models\Tenant;

interface SwitchTenantTask
{
    public function makeCurrent(Tenant $tenant): void;

    public function forgetCurrent(): void;
}
