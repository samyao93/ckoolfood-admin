<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitorLog extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $casts = [
        'visitor_log_id' => 'integer',
        'user_id' => 'integer',
        'visit_count' => 'integer',
        'order_count' => 'integer',
    ];

    public function visitor_log()
    {
        return $this->morphTo();
    }
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
