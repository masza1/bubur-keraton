<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemStock extends Model
{
    protected $fillable = ['name', 'type'];

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    public function stock_reductions()
    {
        return $this->hasMany(StockReduction::class);
    }
}
