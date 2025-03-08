<?php

namespace Tests\Unit;

use App\Models\Company;
use App\Services\CompanyService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompanyServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $companyService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->companyService = new CompanyService();
    }

    public function testCreate()
    {
        $data = [
            'name' => 'New Company',
        ];

        $company = $this->companyService->create($data);

        $this->assertDatabaseHas('companies', ['name' => 'New Company']);
        $this->assertEquals('New Company', $company->name);
    }

    public function testIndex()
    {
        Company::factory()->count(5)->create();

        $result = $this->companyService->index(['per_page' => 5]);

        $this->assertCount(5, $result->items());
    }

    public function testUpdate()
    {
        $company = Company::factory()->create();
        $data = [
            'name' => 'Updated Company Name',
        ];

        $updatedCompany = $this->companyService->update($company, $data);

        $this->assertDatabaseHas('companies', ['name' => 'Updated Company Name']);
        $this->assertEquals('Updated Company Name', $updatedCompany->name);
    }

    public function testDelete()
    {
        $company = Company::factory()->create();

        $this->companyService->delete($company);

        $this->assertDatabaseMissing('companies', ['id' => $company->id]);
    }
}