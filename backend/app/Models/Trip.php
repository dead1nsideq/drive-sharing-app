<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'origin',
        'destination',
        'destination_name',
        'driver_location',
        'user_id',
        'driver_id',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function driver(): \Illuminate\Database\Eloquent\Relations\BelongsTo {
        return $this->belongsTo(Driver::class);
    }
}
