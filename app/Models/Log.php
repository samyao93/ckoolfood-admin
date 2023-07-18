<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $casts = [
        'logable_id' => 'integer',
        'model_id' => 'integer',
    ];

    public function logable()
    {
        return $this->morphTo();
    }
    public function food()
    {
        return $this->belongsTo(Food::class, 'model_id');
    }
    public function order()
    {
        return $this->belongsTo(Order::class, 'model_id');
    }
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id');
    }
}
