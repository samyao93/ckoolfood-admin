<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionSchedule extends Model
{
    use HasFactory;
    protected $casts = [
        'subscription_id'=>'integer',
        'day'=>'integer'
    ];
}
