<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Konser extends Model
{
    protected $table = 'konsers';

    protected $fillable = [
        'kategori_id',
        'name',
        'date',
        'location',
        'description',
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
