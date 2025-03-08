<?php

namespace Tests\Unit;

use App\Models\FundCompany;
use App\Models\Fund;
use App\Models\Company;
use App\Services\FundCompanyService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FundCompanyServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $fundCompanyService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->fundCompanyService = new FundCompanyService();
    }

    public function testCreate()
    {
        $fund = Fund::factory()->create();
        $company = Company::factory()->create();
        $data = [
            'fund_id' => $fund->id,
            'company_id' => $company->id,
        ];

        $fundCompany = $this->fundCompanyService->create($data);

        $this->assertDatabaseHas('fund_companies', ['fund_id' => $fund->id, 'company_id' => $company->id]);
        $this->assertEquals($fund->id, $fundCompany->fund_id);
        $this->assertEquals($company->id, $fundCompany->company_id);
    }

    public function testIndex()
    {
        FundCompany::factory()->count(5)->create();

        $result = $this->fundCompanyService->index(['per_page' => 5]);

        $this->assertCount(5, $result->items());
    }

    public function testUpdate()
    {
        $fundCompany = FundCompany::factory()->create();
        $newFund = Fund::factory()->create();
        $newCompany = Company::factory()->create();
        $data = [
            'fund_id' => $newFund->id,
            'company_id' => $newCompany->id,
        ];

        $updatedFundCompany = $this->fundCompanyService->update($fundCompany, $data);

        $this->assertDatabaseHas('fund_companies', ['fund_id' => $newFund->id, 'company_id' => $newCompany->id]);
        $this->assertEquals($newFund->id, $updatedFundCompany->fund_id);
        $this->assertEquals($newCompany->id, $updatedFundCompany->company_id);
    }

    public function testDelete()
    {
        $fundCompany = FundCompany::factory()->create();

        $this->fundCompanyService->delete($fundCompany);

        $this->assertDatabaseMissing('fund_companies', ['id' => $fundCompany->id]);
    }

    public function testIsFundCompanyUnique()
    {
        $fund = Fund::factory()->create();
        $company = Company::factory()->create();
        FundCompany::factory()->create(['fund_id' => $fund->id, 'company_id' => $company->id]);

        $isUnique = $this->fundCompanyService->isFundCompanyUnique($fund->id, $company->id);

        $this->assertTrue($isUnique);
    }
}