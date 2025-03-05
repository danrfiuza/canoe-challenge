<?php

namespace Database\Factories;

use App\Models\Fund;
use App\Models\FundAlias;
use App\Models\FundManager;
use Illuminate\Database\Eloquent\Factories\Factory;

class FundFactory extends Factory
{
    protected $model = Fund::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'fund_manager_id' => FundManager::factory(),
            'start_year' => $this->faker->year,
        ];
    }

    public function withAliases(int $count = 1)
    {
        return $this->has(FundAlias::factory()->count($count), 'aliases');
    }
}