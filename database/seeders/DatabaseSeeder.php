<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Fund;
use App\Models\FundAlias;
use App\Models\FundCompany;
use App\Models\FundManager;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $fundManagers = FundManager::factory(10)->create();

        $funds = Fund::factory(10)->make()->each(function ($fund) use ($fundManagers) {
            $fund->fund_manager_id = $fundManagers->random()->id;
            $fund->save();
        });

        FundAlias::factory(10)->create()->each(function ($alias) use ($funds) {
            $alias->update(['fund_id' => $funds->random()->id]);
            $alias->fund_id = $funds->random()->id;
            $alias->save();
        });

        $companies = Company::factory(10)->create();

        foreach (range(1, 10) as $index) {
            FundCompany::create([
                'fund_id' => $funds->random()->id,
                'company_id' => $companies->random()->id,
            ]);
        }
    }
}