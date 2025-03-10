<?php

namespace App\Services;

use App\Models\FundAlias;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class FundAliasService
{
    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $fundAlias = FundAlias::create($data);
            return $fundAlias;
        });
    }

    public function index(array $data = [])
    {
        return FundAlias::with($data['with'] ?? [])
            ->paginate($data['per_page'] ?? 20)
            ->appends(Arr::except($data, ['page']));
    }

    public function update(FundAlias $fundAlias, array $data)
    {
        return DB::transaction(fn() => tap($fundAlias)->update($data)->refresh());
    }

    public function delete(FundAlias $fundAlias)
    {
        return DB::transaction(fn() => $fundAlias->delete());
    }

    public function upsertMany(array $aliases)
    {
        return DB::transaction(function () use ($aliases) {
            $results = [];
            foreach ($aliases as $aliasData) {
                if (isset($aliasData['id'])) {
                    $fundAlias = FundAlias::find($aliasData['id']);
                    if ($fundAlias) {
                        $fundAlias->update($aliasData);
                        $results[] = $fundAlias->refresh();
                    }
                } else {
                    $results[] = FundAlias::create($aliasData);
                }
            }
            return $results;
        });
    }
}
