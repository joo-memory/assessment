<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'color'];

    public function stores()
    {
        return $this->hasMany(Store::class);
    }

    public function getTotalProfitAttribute()
    {
        return $this->stores()
            ->with('journals')
            ->get()
            ->flatMap(function ($store) {
                return $store->journals;
            })
            ->sum('profit');
    }
    public function getTotalRevenueAttribute()
    {
        return $this->stores()
            ->with('journals')
            ->get()
            ->flatMap(function ($store) {
                return $store->journals;
            })
            ->sum('revenue');
    }
}
