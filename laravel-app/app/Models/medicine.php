<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $fillable = ['name'];

    public function pharmacies()
    {
        return $this->belongsToMany(Pharmacy::class)->withPivot('price', 'quantity');
    }
}