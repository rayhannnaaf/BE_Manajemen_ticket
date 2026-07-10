<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategori extends Model
{
    protected $table = 'kategori';

    protected $fillable = [
        'name',
        'description',
    ];

    public function konsers(): HasMany
    {
        return $this->hasMany(Konser::class);
    }
}
