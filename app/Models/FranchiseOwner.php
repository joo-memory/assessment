<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FranchiseOwner extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email'
    ];

    public function stores()
    {
        return $this->hasMany(Store::class);
    }
}
