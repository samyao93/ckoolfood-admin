<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionLog extends Model
{
    use HasFactory;
    protected $casts = [
        'subscription_id'=>'integer',
        'order_id'=>'integer',
        'delivery_man_id'=>'integer',
    ];


    public function delivery_man()
    {
        return $this->belongsTo(DeliveryMan::class, 'delivery_man_id');
    }

}
