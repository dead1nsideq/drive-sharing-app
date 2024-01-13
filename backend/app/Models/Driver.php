<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'make',
        'model',
        'color',
        'licence_plate',
        'user_id',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function trips(): \Illuminate\Database\Eloquent\Relations\HasMany {
        return $this->hasMany(Trip::class);
    }
}
