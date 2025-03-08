<?php

namespace App\Services;

use App\Models\Company;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class CompanyService
{
    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            return Company::create($data);
        });
    }

    public function index(array $data = [])
    {
        return Company::with($data['with'] ?? [])
            ->paginate($data['per_page'] ?? 20)
            ->appends(Arr::except($data, ['page']));
    }

    public function update(Company $company, array $data)
    {
        return DB::transaction(fn() => tap($company)->update($data)->refresh());
    }

    public function delete(Company $company)
    {
        return DB::transaction(fn () => $company->delete());
    }
}
