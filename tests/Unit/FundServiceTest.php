<?php

namespace Tests\Feature;

use App\Events\DuplicatedFundWarning;
use App\Models\Fund;
use App\Models\FundAlias;
use App\Models\FundManager;
use App\Services\FundService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;
use Tests\Unit\Helpers\FundTestHelper;

class FundServiceTest extends TestCase
{
    use RefreshDatabase;
    use FundTestHelper;

    protected $fundService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->fundService = new FundService();
    }

    public function testCreate()
    {
        $fundManager = $this->createFundManager();
        $data = [
            'name' => 'New Fund',
            'fund_manager_id' => $fundManager->id,
            'aliases' => [
                ['alias' => 'Alias1', 'fund_id' => null, 'id' => null],
                ['alias' => 'Alias2', 'fund_id' => null, 'id' => null],
            ],
            'start_year' => 2021,
        ];

        $fund = $this->fundService->create($data);

        $this->assertDatabaseHas('funds', ['name' => 'New Fund']);
        $this->assertDatabaseHas('fund_aliases', ['alias' => 'Alias1']);
        $this->assertDatabaseHas('fund_aliases', ['alias' => 'Alias2']);
    }

    public function testIndex()
    {
        Fund::factory()->count(5)->create();

        $result = $this->fundService->index();
        $this->assertCount(5, $result->items());
    }

    public function testUpdate()
    {
        $fund = $this->createFund();
        $data = ['name' => 'Updated Fund Name'];

        $updatedFund = $this->fundService->update($fund, $data);
        $this->assertEquals('Updated Fund Name', $updatedFund->name);
    }

    public function testDelete()
    {
        $fund = $this->createFund();
        $this->fundService->delete($fund);
        $this->assertDatabaseMissing('funds', ['id' => $fund->id]);
    }

    public function testDispatchDuplicatedFundWarningEvent()
    {
        Event::fake();
        $duplicateFund = $this->createDuplicatedFunds();
        $this->fundService->dispatchDuplicatedFundWarningEvent($duplicateFund);

        Event::assertDispatched(DuplicatedFundWarning::class);
    }

    public function testIsFundDuplicated()
    {
        $duplicateFund = $this->createDuplicatedFunds();
        $this->assertTrue($this->fundService->isFundDuplicated($duplicateFund));
    }

    public function testDuplicatedFunds()
    {
        $fundManager = $this->createFundManager();
        $fund1 = $this->createFundWithAlias($fundManager, 'Alias1');
        $fund2 = $this->createFund(['fund_manager_id' => $fundManager->id, 'name' => $fund1->name]);
        FundAlias::factory()->create(['fund_id' => $fund2->id, 'alias' => 'Alias1']);

        $duplicatedFunds = $this->fundService->duplicatedFunds();

        $this->assertCount(1, $duplicatedFunds);
    }
}
