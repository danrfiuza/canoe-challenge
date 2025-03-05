<?php

namespace Tests\Feature\Api;

use App\Models\FundManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FundManagerControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        FundManager::factory()->count(5)->create();

        $response = $this->getJson('/api/fund-managers');

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

    public function testStore()
    {
        $data = [
            'name' => 'New Fund Manager',
            // Add other fields as necessary
        ];

        $response = $this->postJson('/api/fund-managers', $data);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Fund Manager created successfully',
                'data' => [
                    'name' => 'New Fund Manager',
                    // Add other fields as necessary
                ]
            ]);

        $this->assertDatabaseHas('fund_managers', ['name' => 'New Fund Manager']);
    }

    public function testUpdate()
    {
        $fundManager = FundManager::factory()->create();

        $data = [
            'name' => 'Updated Fund Manager',
            // Add other fields as necessary
        ];

        $response = $this->putJson("/api/fund-managers/{$fundManager->id}", $data);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Fund Manager updated successfully',
                'data' => [
                    'name' => 'Updated Fund Manager',
                    // Add other fields as necessary
                ]
            ]);

        $this->assertDatabaseHas('fund_managers', ['name' => 'Updated Fund Manager']);
    }

    public function testDestroy()
    {
        $fundManager = FundManager::factory()->create();

        $response = $this->deleteJson("/api/fund-managers/{$fundManager->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Fund Manager Deleted successfully',
            ]);

        $this->assertDatabaseMissing('fund_managers', ['id' => $fundManager->id]);
    }
}
