<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Konser extends Model
{
    protected $fillable = [
        'kategori_id', 'name', 'date', 'location', 'description', 'image',
    ];

    protected $appends = ['image_url']; 

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }
}