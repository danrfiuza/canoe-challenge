<?php

namespace App\Services;

use App\Models\Fund;
use App\Models\FundManager;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class FundManagerService
{
    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            return FundManager::create($data);
        });
    }

    public function index(array $data = [])
    {
        return FundManager::with($data['with'] ?? [])
            ->paginate($data['per_page'] ?? 20)
            ->appends(Arr::except($data, ['page']));
    }

    public function update(FundManager $fundManager, array $data)
    {
        return DB::transaction(fn() => tap($fundManager)->update($data)->refresh());
    }

    public function delete(FundManager $fundManager)
    {
        return DB::transaction(fn () => $fundManager->delete());
    }
}
?>