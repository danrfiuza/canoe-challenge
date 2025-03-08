<?php

namespace App\Services;

use App\Events\DuplicatedFundWarning;
use App\Models\Fund;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class FundService
{
    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $fund = Fund::create($data);
            if (isset($data['aliases'])) {
                $aliases = array_map(fn($alias) => ['alias' => $alias['alias']], $data['aliases']);
                $fund->aliases()->createMany($aliases);
            }

            $this->dispatchDuplicatedFundWarningEvent($fund);
            return $fund;
        });
    }

    public function index(array $data = [])
    {
        return Fund::with($data['with'] ?? [])
            ->when(Arr::exists($data, 'name'), fn($query) => $query->where('name', 'like', '%' . $data['name'] . '%'))
            ->when(Arr::exists($data, 'fund_manager_name'), fn($query) => $query->whereHas('fundManager', fn($q) => $q->where('name', 'like', '%' . $data['fund_manager_name'] . '%')))
            ->when(Arr::exists($data, 'start_year'), fn($query) => $query->where('start_year', $data['start_year']))
            ->paginate($data['per_page'] ?? 20)
            ->appends(Arr::except($data, ['page']));
    }

    public function update(Fund $fund, array $data)
    {
        return DB::transaction(function () use ($data, $fund) {
            $fund->update($data);
            if (isset($data['aliases'])) {
                (new FundAliasService())->upsertMany($data['aliases']);
            }

            $fund->refresh();
            $this->dispatchDuplicatedFundWarningEvent($fund);
            return $fund;
        });
    }

    public function delete(Fund $fund)
    {
        return DB::transaction(fn() => $fund->delete());
    }

    public function dispatchDuplicatedFundWarningEvent(Fund $fund)
    {
        if ($this->isFundDuplicated($fund)) {
            DuplicatedFundWarning::dispatch($fund);
            // event(new DuplicatedFundWarning($fund));
            // broadcast(new DuplicatedFundWarning($fund));
        }
    }

    public function isFundDuplicated(Fund $fund): bool
    {
        $fund = $fund ?? new Fund();
        return Fund::duplicatedFunds($fund)->exists();
    }

    public function duplicatedFunds(?Fund $fund = null)
    {
        $fund = $fund ?? new Fund();
        return Fund::duplicatedFunds($fund)
            ->get()
            ->groupBy(function ($fund) {
                return $fund->fund_manager_id . '-' . $fund->aliases->pluck('alias')->join(',');
            });
    }


    public static function duplicatedFundWarningMessage($fund)
    {
        return "Warning: The fund " . $fund->name . " has duplicated Alias and Fund Manager.";
    }
}
