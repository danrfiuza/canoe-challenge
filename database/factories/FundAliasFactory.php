<?php

namespace Database\Factories;

use App\Models\Fund;
use Illuminate\Database\Eloquent\Factories\Factory;

class FundAliasFactory extends Factory
{
    public function definition(): array
    {
        return [
            'alias' => fake()->name(),
            'fund_id' => Fund::factory(),
        ];
    }
}
