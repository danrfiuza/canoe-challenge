<?php

namespace Tests\Unit;

use App\Models\Fund;
use App\Models\FundAlias;
use App\Services\FundAliasService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FundAliasServiceTest extends TestCase
{
    use RefreshDatabase;

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
            'alias' => 'Alias 1',
            'fund_id' => $fund->id,
        ];

        $fundAlias = $this->fundAliasService->create($data);

        $this->assertDatabaseHas('fund_aliases', ['alias' => 'Alias 1', 'fund_id' => 1]);
        $this->assertEquals('Alias 1', $fundAlias->alias);
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

    public function testUpsertMany()
    {
        $fund = Fund::factory()->create();
        $aliases = [
            ['alias' => 'Alias 1', 'fund_id' => $fund->id],
            ['alias' => 'Alias 2', 'fund_id' => $fund->id],
        ];

        $results = $this->fundAliasService->upsertMany($aliases);

        $this->assertCount(2, $results);
        $this->assertDatabaseHas('fund_aliases', ['alias' => 'Alias 1', 'fund_id' => 1]);
        $this->assertDatabaseHas('fund_aliases', ['alias' => 'Alias 2', 'fund_id' => 1]);

        $existingAlias = $results[0];
        $updatedAliases = [
            ['id' => $existingAlias->id, 'alias' => 'Updated Alias 1', 'fund_id' => 1],
            ['alias' => 'Alias 3', 'fund_id' => 1],
        ];

        $updatedResults = $this->fundAliasService->upsertMany($updatedAliases);

        $this->assertCount(2, $updatedResults);
        $this->assertDatabaseHas('fund_aliases', ['alias' => 'Updated Alias 1', 'fund_id' => 1]);
        $this->assertDatabaseHas('fund_aliases', ['alias' => 'Alias 3', 'fund_id' => 1]);
    }
}
