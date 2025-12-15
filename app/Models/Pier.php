<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pier extends Model
{
    protected $fillable = [
        'unlocode',
        'name',
        'country_iso2',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_iso2', 'iso2');
    }

    public function searchesPol(): HasMany
    {
        return $this->hasMany(Search::class, 'pol_port_id');
    }

    public function searchesPod(): HasMany
    {
        return $this->hasMany(Search::class, 'pod_port_id');
    }
}
