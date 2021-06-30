<?php

declare(strict_types=1);

namespace Asseco\Multitenancy\Database\Seeders;

use Asseco\Multitenancy\App\Models\Tenant;
use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'name'       => 'foo',
                'domain'     => 'foo.localhost',
                'database'   => 'foo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'bar',
                'domain'     => 'bar.localhost',
                'database'   => 'bar',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        Tenant::query()->insert($data);

        Tenant::factory()->count(20)->create();
    }
}
