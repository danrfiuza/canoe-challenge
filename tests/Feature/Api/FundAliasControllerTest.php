<?php

namespace Tests\Feature\Api;

use App\Models\FundAlias;
use App\Models\Fund;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FundAliasControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        FundAlias::factory()->count(5)->create();

        $response = $this->getJson('/api/fund-aliases');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'data' => [
                        '*' => [
                            'id',
                            'alias',
                            'fund_id',
                        ]
                    ]
                ]
            ]);
    }

    public function testShow()
    {
        $fundAlias = FundAlias::factory()->create();

        $response = $this->getJson("/api/fund-aliases/{$fundAlias->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => '',
                'data' => [
                    'id' => $fundAlias->id,
                    'alias' => $fundAlias->alias,
                    'fund_id' => $fundAlias->fund_id,
                ]
            ]);
    }

    public function testStore()
    {
        $fund = Fund::factory()->create();
        $data = [
            'alias' => 'New Alias',
            'fund_id' => $fund->id,
        ];

        $response = $this->postJson('/api/fund-aliases', $data);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Fund Alias created successfully',
                'data' => [
                    'alias' => 'New Alias',
                    'fund_id' => $fund->id,
                ]
            ]);

        $this->assertDatabaseHas('fund_aliases', ['alias' => 'New Alias']);
    }

    public function testUpdate()
    {
        $fundAlias = FundAlias::factory()->create();
        $data = [
            'alias' => 'Updated Alias',
        ];

        $response = $this->putJson("/api/fund-aliases/{$fundAlias->id}", $data);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Fund Alias updated successfully',
                'data' => [
                    'alias' => 'Updated Alias',
                ]
            ]);

        $this->assertDatabaseHas('fund_aliases', ['alias' => 'Updated Alias']);
    }

    public function testDestroy()
    {
        $fundAlias = FundAlias::factory()->create();

        $response = $this->deleteJson("/api/fund-aliases/{$fundAlias->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Fund Alias Deleted successfully',
            ]);

        $this->assertDatabaseMissing('fund_aliases', ['id' => $fundAlias->id]);
    }
}