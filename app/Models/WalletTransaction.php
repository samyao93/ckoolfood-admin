<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $casts = [
        'user_id' => 'integer',
        'delivery_man_id' => 'integer',
        'credit' => 'float',
        'debit' => 'float',
        'admin_bonus'=>'float',
        'balance'=>'float',
        'reference'=>'string',
        'created_at'=>'string'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function delivery_man()
    {
        return $this->belongsTo(DeliveryMan::class,'delivery_man_id');
    }
}
