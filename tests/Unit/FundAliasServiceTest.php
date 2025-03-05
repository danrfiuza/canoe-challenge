<?php

namespace Tests\Unit;

use App\Models\Fund;
use App\Models\FundAlias;
use App\Services\FundAliasService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Unit\Helpers\FundTestHelper;

class FundAliasServiceTest extends TestCase
{
    use RefreshDatabase;
    use FundTestHelper;

    protected $fundAliasService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->fundAliasService = new FundAliasService();
    }

    public function testCreate()
    {
        $fund = Fund::factory()->create();
        $data = [
            'alias' => 'New Alias',
            'fund_id' => $fund->id,
        ];

        $fundAlias = $this->fundAliasService->create($data);

        $this->assertDatabaseHas('fund_aliases', ['alias' => 'New Alias']);
        $this->assertEquals('New Alias', $fundAlias->alias);
        $this->assertEquals(1, $fundAlias->fund_id);
    }

    public function testIndex()
    {
        FundAlias::factory()->count(5)->create();

        $result = $this->fundAliasService->index(['per_page' => 5]);

        $this->assertCount(5, $result->items());
    }

    public function testUpdate()
    {
        $fundAlias = FundAlias::factory()->create();
        $data = [
            'alias' => 'Updated Alias',
        ];

        $updatedFundAlias = $this->fundAliasService->update($fundAlias, $data);

        $this->assertDatabaseHas('fund_aliases', ['alias' => 'Updated Alias']);
        $this->assertEquals('Updated Alias', $updatedFundAlias->alias);
    }

    public function testDelete()
    {
        $fundAlias = FundAlias::factory()->create();

        $this->fundAliasService->delete($fundAlias);

        $this->assertDatabaseMissing('fund_aliases', ['id' => $fundAlias->id]);
    }
}
