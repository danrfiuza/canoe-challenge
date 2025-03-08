<?php

namespace Database\Factories;

use App\Models\FundCompany;
use App\Models\Fund;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class FundCompanyFactory extends Factory
{
    protected $model = FundCompany::class;

    public function definition()
    {
        return [
            'fund_id' => Fund::factory(),
            'company_id' => Company::factory(),
        ];
    }
}