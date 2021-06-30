<?php

declare(strict_types=1);

namespace Asseco\Multitenancy\Events;

use Asseco\Multitenancy\Models\Tenant;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TenantMadeCurrent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Tenant $tenant;

    public function __construct(Tenant $tenant)
    {
        $this->tenant = $tenant;
    }
}
