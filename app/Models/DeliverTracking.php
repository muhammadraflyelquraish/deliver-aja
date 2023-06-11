<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliverTracking extends Model
{
    use HasFactory;

    protected $fillable = [
        'deliver_id',
        'current_location',
        'date_arrived',
        'type_tracking',
    ];

    function deliver()
    {
        return $this->belongsTo(Deliver::class);
    }
}
