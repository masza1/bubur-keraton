<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    protected $fillable = ['item_id', 'quantity', 'platform', 'date'];
    public $with = ['item'];

    public function item(){
        return $this->belongsTo(Item::class);
    }
}
