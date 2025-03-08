<?php

namespace Tests\Unit\Helpers;

use App\Models\Fund;
use App\Models\FundAlias;
use App\Models\FundManager;

trait FundTestHelper
{
    protected function createFundManager()
    {
        return FundManager::factory()->create();
    }

    protected function createFund($attributes = [])
    {
        return Fund::factory()->create($attributes);
    }

    protected function createFundWithAlias($fundManager, $alias)
    {
        $fund = $this->createFund(['fund_manager_id' => $fundManager->id]);
        FundAlias::factory()->create(['fund_id' => $fund->id, 'alias' => $alias]);
        return $fund;
    }

    protected function createDuplicatedFunds()
    {
        $fundManager = $this->createFundManager();
        $this->createFundWithAlias($fundManager, 'Alias1');

        $duplicateFund = $this->createFund(['name' => 'Duplicated fund', 'fund_manager_id' => $fundManager->id]);
        FundAlias::factory()->create(['fund_id' => $duplicateFund->id, 'alias' => 'Alias1']);

        return $duplicateFund;
    }
}
