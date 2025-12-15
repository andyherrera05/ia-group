<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingLine extends Model
{
    protected $fillable = [
        'code',
        'name',
        'logo',
        'description',
    ];

    public function rates()
    {
        return $this->hasMany(Rate::class);
    }
}
