<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FundManager extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function funds()
    {
        return $this->hasMany(Fund::class, 'fund_manager_id');
    }
}