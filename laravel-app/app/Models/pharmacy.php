<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pharmacy extends Model
{
    public function medicines()
    {
        return $this->belongsToMany(Medicine::class)->withPivot('price', 'quantity');
    }
}