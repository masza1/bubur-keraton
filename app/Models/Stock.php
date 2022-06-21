<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = ['item_stock_id', 'stock', 'remaining_stock', 'date'];
    public $with = ['item_stock'];

    public function item_stock()
    {
        return $this->belongsTo(ItemStock::class);
    }

    public function stock_reductions(){
        return $this->hasMany(StockReduction::class);
    }
}
