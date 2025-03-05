<?php

namespace App\Services;

use App\Models\FundCompany;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class FundCompanyService
{
    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $fundCompany = FundCompany::create($data);
            return $fundCompany;
        });
    }

    public function index(array $data = [])
    {
        return FundCompany::with($data['with'] ?? [])
        ->when(Arr::exists($data, 'company_id'), function ($query) use ($data) {
            return $query->where('company_id', $data['company_id']);
        })
        ->when(Arr::exists($data, 'fund_id'), function ($query) use ($data) {
            return $query->where('fund_id', $data['fund_id']);
        })
            ->paginate($data['per_page'] ?? 20)
            ->appends(Arr::except($data, ['page']));
    }

    public function update(FundCompany $fundCompany, array $data)
    {
        return DB::transaction(fn() => tap($fundCompany)->update($data)->refresh());
    }

    public function delete(FundCompany $fundCompany)
    {
        return DB::transaction(fn() => $fundCompany->delete());
    }

    public function isFundCompanyUnique(int $fundId, int $companyId): bool
    {
        return FundCompany::where('fund_id', $fundId)
        ->where('company_id', $companyId)
        ->exists();
    }
}
