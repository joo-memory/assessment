<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Store extends Model
{
    use HasFactory;

    protected $fillable = ['brand_id', 'number','address','city','state','zip_code','franchise_owner_id'];
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function journals()
    {
        return $this->hasMany(Journal::class);
    }

    public function franchiseOwner()
    {
        return $this->belongsTo(FranchiseOwner::class);
    }

    public function getTotalProfitAttribute()
    {
        return $this->journals()->sum('profit');
    }
    public function getTotalRevenueAttribute()
    {
        return $this->journals()->sum('revenue');
    }
    public function getTotalLaborCostAttribute()
    {
        return $this->journals()->sum('labor_cost');
    }
    public function getTotalFoodCostAttribute()
    {
        return $this->journals()->sum('food_cost');
    }

      // Accessor to get revenue per month
    public function getProfitPerMonthAttribute()
    {
       return $this->journals()
           ->select(DB::raw('YEAR(date) as year, MONTH(date) as month, SUM(profit) as total_profit'))
           ->groupBy(DB::raw('YEAR(date), MONTH(date)'))
           ->orderBy('year')
           ->orderBy('month')
           ->get()
           ->mapWithKeys(function ($item) {
               return [
                   "{$item->year}-{$item->month}" => $item->total_profit,
               ];
           });
    }




}
