<?php

namespace Tests\Feature\Api;

use App\Models\FundCompany;
use App\Models\Fund;
use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FundCompanyControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        FundCompany::factory()->count(5)->create();

        $response = $this->getJson('/api/fund-companies');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'data' => [
                        '*' => [
                            'id',
                            'fund_id',
                            'company_id',
                        ]
                    ]
                ]
            ]);
    }

    public function testStore()
    {
        $fund = Fund::factory()->create();
        $company = Company::factory()->create();
        $data = [
            'fund_id' => $fund->id,
            'company_id' => $company->id,
        ];

        $response = $this->postJson('/api/fund-companies', $data);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Fund Alias created successfully',
                'data' => [
                    'fund_id' => $fund->id,
                    'company_id' => $company->id,
                ]
            ]);

        $this->assertDatabaseHas('fund_companies', ['fund_id' => $fund->id, 'company_id' => $company->id]);
    }

    public function testDestroy()
    {
        $fundCompany = FundCompany::factory()->create();

        $response = $this->deleteJson("/api/fund-companies/{$fundCompany->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Fund Alias Deleted successfully',
            ]);

        $this->assertDatabaseMissing('fund_companies', ['id' => $fundCompany->id]);
    }
}