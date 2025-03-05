<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FundCompany extends Model
{
    use HasFactory;

    protected $fillable = ['fund_id', 'company_id'];

    public function fund()
    {
        return $this->belongsTo(Fund::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
