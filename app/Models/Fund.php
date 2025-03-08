<?php

namespace App\Models;

use App\Observers\FundObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

// #[ObservedBy([FunsadObserver::class])]
class Fund extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'start_year',
        'fund_manager_id',
    ];

    protected $with = ['fundManager', 'aliases', 'companies'];

    public function fundManager()
    {
        return $this->belongsTo(FundManager::class);
    }

    public function aliases()
    {
        return $this->hasMany(FundAlias::class);
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'fund_companies', 'fund_id', 'company_id');
    }

    public static function duplicatedFunds(Fund $fund)
    {
        return $fund::whereIn('fund_manager_id', function ($query) use ($fund) {
            $query->select('fund_manager_id')
                ->from('funds')
                ->groupBy('fund_manager_id')
                ->havingRaw('COUNT(id) > ?', [1]);
        })
            ->orWhereHas('aliases', function ($query) {
                $query->select('alias')
                    ->groupBy('alias')
                    ->havingRaw('COUNT(alias) > ?', [1]);
            })
            ->distinct()
            ->orderBy('fund_manager_id')
            ->orderBy('id');
    }
}
