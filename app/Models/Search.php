<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Port;

class Search extends Model
{
    protected $fillable = [
        'searched_at',
        'transport_type',
        'pol_code',
        'pod_code',
        'result_page_url',
        'total_rates_found',
        'success',
    ];

    public function rates()
    {
        return $this->hasMany(Rate::class);
    }
     public function polPort() {
        return $this->belongsTo(Pier::class, 'pol_port_id');
    }

    public function podPort() {
        return $this->belongsTo(Pier::class, 'pod_port_id');
    }
}
