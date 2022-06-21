<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockReduction extends Model
{
    protected $fillable = ['item_stock_id', 'expense', 'description', 'date'];
    public $with = ['item_stock'];

    public function item_stock()
    {
        return $this->belongsTo(ItemStock::class);
    }

    public function stock(){
        return $this->belongsTo(Stock::class);
    }
}
