<?php

namespace App\Models;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $casts = [
        'id'=>'integer',
        'billing_amount'=>'float',
        'paid_amount'=>'float',
        'quantity'=>'integer',
        'user_id'=>'integer',
        'restaurant_id'=>'integer',
    ];

    protected $appends = ['is_paused_today'];

    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id');
    }

    public function logs()
    {
        return $this->hasMany(SubscriptionLog::class);
    }
    public function log()
    {
        return $this->hasOne(SubscriptionLog::class)->latest();
    }

    public function schedules()
    {
        return $this->hasMany(SubscriptionSchedule::class);
    }

    public function schedule_today()
    {
        return $this->hasOne(SubscriptionSchedule::class,'subscription_id')->where(function($query){
            $query->where('type', 'weekly')->where('day', (int)now()->format('w'))
            ->orWhere(function($query){
                $query->where('type', 'monthly')->where('day', (int)now()->format('d'));
            })->orWhere('type', 'daily');
        });
    }
    public function schedule()
    {
        return $this->hasOne(SubscriptionSchedule::class,'subscription_id')->latest();
    }

    public function order()
    {
        return $this->hasOne(Order::class);
    }

    public function pause()
    {
        return $this->hasMany(SubscriptionPause::class);
    }

    public function scopeCheckdate($query, $start_date, $end_date)
    {
        $query->where(function($query)use($start_date){
            $query->whereDate('start_at','<=', $start_date)->whereDate('end_at', '>=', $start_date);
        })->orWhere(function($query)use($end_date){
            $query->whereDate('start_at','<=', $end_date)->whereDate('end_at', '>=', $end_date);
        });
    }

    public function scopeExpired($query)
    {
        $query->whereDate('end_at', '<', now()->format('Y-m-d'))->where('status','active');
    }

    public function getIsPausedTodayAttribute()
    {
        return (bool)$this->pause()->whereDate('from','<=', now()->format('Y-m-d'))->whereDate('to','>=', now()->format('Y-m-d'))->count();
    }

    public function getStatusAttribute($value)
    {
        return $this->IsPausedToday ? 'paused' : (now()->today()->gt(Carbon::parse($this->end_at)) ? 'expired' : $value);
    }
}
