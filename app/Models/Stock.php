<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = ['item_stock_id', 'stock', 'date'];
    public $with = ['item_stock'];

    public function item_stock()
    {
        return $this->belongsTo(ItemStock::class);
    }
}
