<?php

namespace Tests\Unit;

use App\Models\FundManager;
use App\Services\FundManagerService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FundManagerServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $fundManagerService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->fundManagerService = new FundManagerService();
    }

    public function testCreate()
    {
        $data = [
            'name' => 'New Fund Manager',
        ];

        $fundManager = $this->fundManagerService->create($data);

        $this->assertDatabaseHas('fund_managers', ['name' => 'New Fund Manager']);
        $this->assertEquals('New Fund Manager', $fundManager->name);
    }

    public function testIndex()
    {
        FundManager::factory()->count(5)->create();

        $result = $this->fundManagerService->index(['per_page' => 5]);

        $this->assertCount(5, $result->items());
    }

    public function testUpdate()
    {
        $fundManager = FundManager::factory()->create();
        $data = [
            'name' => 'Updated Fund Manager Name',
        ];

        $updatedFundManager = $this->fundManagerService->update($fundManager, $data);

        $this->assertDatabaseHas('fund_managers', ['name' => 'Updated Fund Manager Name']);
        $this->assertEquals('Updated Fund Manager Name', $updatedFundManager->name);
    }

    public function testDelete()
    {
        $fundManager = FundManager::factory()->create();

        $this->fundManagerService->delete($fundManager);

        $this->assertDatabaseMissing('fund_managers', ['id' => $fundManager->id]);
    }
}