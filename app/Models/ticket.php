<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'konser_id',
        'ticket_name',
        'price',
        'stock',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function konser(): BelongsTo
    {
        return $this->belongsTo(Konser::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}