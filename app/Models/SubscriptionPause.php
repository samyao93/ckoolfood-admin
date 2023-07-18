<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPause extends Model
{
    use HasFactory;
    protected $casts = [
        'subscription_id'=>'integer',
    ];

    public function scopeCheckdate($query, $start_date, $end_date)
    {
        $query->where(function($query)use($start_date){
            $query->whereDate('from','<=', $start_date)->whereDate('to', '>=', $start_date);
        })->orWhere(function($query)use($end_date){
            $query->whereDate('from','<=', $end_date)->whereDate('to', '>=', $end_date);
        });
    }
}
