<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'date',
        'capacity',
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    public function getRegisteredCountAttribute(): int
    {
        return $this->registrations()->count();
    }

    public function isFull(): bool
    {
        return $this->registrations()->count() >= $this->capacity;
    }
}
