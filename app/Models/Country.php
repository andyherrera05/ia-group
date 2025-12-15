<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    protected $fillable = [
        'iso2',
        'name',
    ];

    public function ports(): HasMany
    {
        return $this->hasMany(Pier::class, 'country_iso2', 'iso2');
    }
}
