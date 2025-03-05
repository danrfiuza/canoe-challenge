<?php

namespace Tests\Feature\Api;

use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompanyControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        Company::factory()->count(5)->create();

        $response = $this->getJson('/api/companies');

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
        $company = Company::factory()->create();

        $response = $this->getJson("/api/companies/{$company->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => '',
                'data' => [
                    'id' => $company->id,
                    'name' => $company->name,
                ]
            ]);
    }

    public function testStore()
    {
        $data = [
            'name' => 'New Company',
        ];

        $response = $this->postJson('/api/companies', $data);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Company created successfully',
                'data' => [
                    'name' => 'New Company',
                ]
            ]);

        $this->assertDatabaseHas('companies', ['name' => 'New Company']);
    }

    public function testUpdate()
    {
        $company = Company::factory()->create();

        $data = [
            'name' => 'Updated Company',
        ];

        $response = $this->putJson("/api/companies/{$company->id}", $data);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Company updated successfully',
                'data' => [
                    'name' => 'Updated Company',
                ]
            ]);

        $this->assertDatabaseHas('companies', ['name' => 'Updated Company']);
    }

    public function testDestroy()
    {
        $company = Company::factory()->create();

        $response = $this->deleteJson("/api/companies/{$company->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Company Deleted successfully',
            ]);

        $this->assertDatabaseMissing('companies', ['id' => $company->id]);
    }
}
