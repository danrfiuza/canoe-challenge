<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FundAlias extends Model
{
    use HasFactory;

    protected $fillable = ['fund_id', 'alias'];

    public function fund()
    {
        return $this->belongsTo(Fund::class);
    }
}