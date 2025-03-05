<?php

namespace Tests\Feature\Api;

use App\Models\Fund;
use App\Models\FundManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FundControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        Fund::factory()->count(5)->create();

        $response = $this->getJson('/api/funds');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                        ]
                    ]
                ]
            ]);
    }

    public function testShow()
    {
        $fund = Fund::factory()->create();

        $response = $this->getJson("/api/funds/{$fund->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => '',
                'data' => [
                    'id' => $fund->id,
                    'name' => $fund->name,
                ]
            ]);
    }

    public function testStore()
    {
        $fundManager = FundManager::factory()->create();

        $data = [
            'name' => 'New Fund',
            'start_year' => 2021,
            'fund_manager_id' => $fundManager->id
        ];

        $response = $this->postJson('/api/funds', $data);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Fund created successfully',
                'data' => [
                    'name' => 'New Fund',
                ]
            ]);

        $this->assertDatabaseHas('funds', ['name' => 'New Fund']);
    }

    public function testUpdate()
    {
        $fund = Fund::factory()->create();

        $data = [
            'name' => 'Updated Fund',
        ];

        $response = $this->putJson("/api/funds/{$fund->id}", $data);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Fund updated successfully',
                'data' => [
                    'name' => 'Updated Fund',
                ]
            ]);

        $this->assertDatabaseHas('funds', ['name' => 'Updated Fund']);
    }

    public function testDestroy()
    {
        $fund = Fund::factory()->create();

        $response = $this->deleteJson("/api/funds/{$fund->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Fund Deleted successfully',
            ]);

        $this->assertDatabaseMissing('funds', ['id' => $fund->id]);
    }

    public function testDuplicatedFunds()
    {
        Fund::factory()->count(5)->create();

        $response = $this->getJson('/api/funds/duplicated');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                'data' => [
                    '*' => [
                        'id',
                        'name',
                    ]
                ]
                ]
            ]);
    }
}
