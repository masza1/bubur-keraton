<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = ['name', 'price'];

    public function incomes(){
        return $this->hasMany(Income::class);
    }
}
