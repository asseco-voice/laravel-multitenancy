<?php

declare(strict_types=1);

namespace Asseco\Multitenancy\App\Traits;

trait HasDbConnections
{
    public function getLandlordDbConnection()
    {
        return config('landlord-database.same') ?
            config('database.default') : config('landlord-database.default');
    }

    public function getTenantDbConnection()
    {
        return config('database.default');
    }
}
