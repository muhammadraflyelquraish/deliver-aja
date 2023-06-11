<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deliver extends Model
{
    use HasFactory;

    protected $fillable = [
        'code_deliver',
        'type_deliver',
        'user_id',
        'address_id',
        'service_id',
        'destination_address',
        'kilometer',
        'weight',
        'total_price',
        'date_pickup',
        'date_sent',
        'date_received',
        'status_deliver'
    ];

    function user()
    {
        return $this->belongsTo(User::class);
    }

    function address()
    {
        return $this->belongsTo(Address::class);
    }

    function service()
    {
        return $this->belongsTo(Service::class);
    }

    function tracking()
    {
        return $this->hasMany(DeliverTracking::class);
    }
}
