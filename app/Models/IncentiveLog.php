<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncentiveLog extends Model
{
    use HasFactory;

    protected $guarded = ['id'];


    protected $casts = [
        'earning' => 'float',
        'today_earning' => 'float',
        'min_pay_subsidy' => 'float',
        'working_hours' => 'float',
        'incentive' => 'float',
        'delivery_man_id' => 'integer',
        'zone_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function deliveryman()
    {
        return $this->belongsTo(DeliveryMan::class, 'delivery_man_id');
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
}
