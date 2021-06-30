<?php

declare(strict_types=1);

namespace Asseco\Multitenancy\Database\Seeders;

use Asseco\Multitenancy\Models\Tenant;
use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'name'     => 'foo',
                'domain'   => 'foo.localhost',
                'database' => 'foo',
            ],
            [
                'name'     => 'bar',
                'domain'   => 'bar.localhost',
                'database' => 'bar',
            ],
        ];

        Tenant::query()->insert($data);

        Tenant::factory()->count(20)->create();
    }
}
