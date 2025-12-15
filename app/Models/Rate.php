<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    protected $fillable = [
        'search_id',
        'shipping_line_id',
        'valid_until',
        'gp20',
        'gp40',
        'hq40',
        'closing',
        'transit_time',
    ];

    public function shippingLine()
    {
        return $this->belongsTo(ShippingLine::class);
    }
}
