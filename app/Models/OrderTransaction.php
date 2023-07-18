<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderTransaction extends Model
{
    use HasFactory;

    protected $casts = [
        'admin_expense' => 'float',
        'restaurant_expense' => 'float',
        'commission_percentage' => 'float',
        'discount_amount_by_restaurant' => 'float',
        //restaurant business model
        'is_subscribed'=>'integer',
        //subscription order
        'is_subscription'=>'boolean'
    ];

    protected $fillable = array('delivery_man_id');

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function delivery_man()
    {
        return $this->belongsTo(DeliveryMan::class, 'delivery_man_id');
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class,'vendor_id','vendor_id');
    }

    public function scopeNotRefunded($query)
    {
        return $query->where(function($query){
            $query->whereNotIn('status', ['refunded_with_delivery_charge', 'refunded_without_delivery_charge'])->orWhereNull('status');
        });
    }
}
