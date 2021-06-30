<?php

declare(strict_types=1);

namespace Asseco\Multitenancy\Database\Factories;

use Asseco\Multitenancy\App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

class TenantFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Tenant::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'     => $this->faker->name,
            'domain'   => $this->faker->unique()->url,
            'database' => $this->faker->word,
        ];
    }
}
