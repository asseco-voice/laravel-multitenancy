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
        if (!$this->externalConnectionSet($tenant)) {
            $this->setTenantConnectionDatabaseName($tenant->database);

            return;
        }

        $connection = $tenant->db_connection;

        config([
            "database.connections.{$connection}.database" => $tenant->database,
            "database.connections.{$connection}.host"     => $tenant->db_host,
            "database.connections.{$connection}.port"     => $tenant->db_port,
            "database.connections.{$connection}.username" => $tenant->db_username,
            "database.connections.{$connection}.password" => $tenant->db_password,
        ]);

        DB::purge($connection);
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

    /**
     * If all properties are not set, we do not want to even try connecting,
     * and will consider it an invalid configuration, reverting to a default
     * connection.
     *
     * @param Tenant $tenant
     *
     * @return bool
     */
    protected function externalConnectionSet(Tenant $tenant): bool
    {
        return isset(
            $tenant->db_connection,
            $tenant->db_host,
            $tenant->db_port,
            $tenant->db_username,
            $tenant->db_password
        );
    }
}
