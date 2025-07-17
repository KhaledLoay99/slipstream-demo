<?php

namespace Database\Factories;

use App\Models\CustomerCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'reference' => fake()->unique()->lexify('REF-???'),
            'customer_category_id' => CustomerCategory::factory(),
            'start_date' => fake()->date(),
            'description' => fake()->paragraph(),
        ];
    }
}
