<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incentive extends Model
{
    use HasFactory;


    protected $guarded = ['id'];


    protected $casts = [
        'id' => 'integer',
        'zone_id' => 'integer',
        'earning' => 'float',
        'incentive' =>'float',
    ];

}
