<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    // user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
