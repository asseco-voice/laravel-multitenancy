<?php

declare(strict_types=1);

namespace Asseco\Multitenancy\App\Tasks;

use Asseco\Multitenancy\App\Models\Tenant;
use Asseco\Multitenancy\App\Traits\HasDbConnections;
use Exception;
use Illuminate\Support\Facades\DB;

class SwitchTenantDatabase implements SwitchTenantTask
{
    use HasDbConnections;

    /**
     * @param Tenant $tenant
     *
     * @throws Exception
     */
    public function makeCurrent(Tenant $tenant): void
    {
        $this->setTenantConnectionDatabaseName($tenant->database);
    }

    /**
     * @throws Exception
     */
    public function forgetCurrent(): void
    {
        $this->setTenantConnectionDatabaseName(null);
    }

    /**
     * @param string|null $databaseName
     *
     * @throws Exception
     */
    protected function setTenantConnectionDatabaseName(?string $databaseName)
    {
        $tenantDbConnection = $this->getTenantDbConnection();

        if ($tenantDbConnection === $this->getLandlordDbConnection()) {
            throw new Exception('Tenant connection is empty or equals to landlord connection.');
        }

        config([
            "database.connections.{$tenantDbConnection}.database" => $databaseName,
        ]);

        DB::purge($tenantDbConnection);
    }
}
