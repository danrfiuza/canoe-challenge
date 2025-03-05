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
}
