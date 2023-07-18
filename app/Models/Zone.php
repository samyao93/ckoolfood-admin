<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use MatanYadaev\EloquentSpatial\Objects\Polygon;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;
use App\Scopes\ZoneScope;
use Illuminate\Database\Eloquent\Builder;

class Zone extends Model
{
    use HasFactory;
    use HasSpatial;

    protected $casts = [
        'id'=>'integer',
        'status'=>'integer',
        'minimum_shipping_charge'=>'float',
        'maximum_shipping_charge'=>'float',
        'per_km_shipping_charge'=>'float',
        'max_cod_order_amount'=>'float',
        'increased_delivery_fee'=>'float',
        'increased_delivery_fee_status'=>'integer',
        'coordinates' => Polygon::class,
    ];

    protected $fillable = [
        'coordinates'
    ];

    public function scopeContains($query,$abc){
        return $query->whereRaw("ST_Distance_Sphere(coordinates, POINT({$abc}))");
    }
    public function translations()
    {
        return $this->morphMany(Translation::class, 'translationable');
    }

    public function restaurants()
    {
        return $this->hasMany(Restaurant::class);
    }

    public function deliverymen()
    {
        return $this->hasMany(DeliveryMan::class);
    }

    public function orders()
    {
        return $this->hasManyThrough(Order::class, Restaurant::class);
    }


    public function campaigns()
    {
        return $this->hasManyThrough(Campaigns::class, Restaurant::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', '=', 1);
    }

    protected static function booted()
    {
        static::addGlobalScope(new ZoneScope);
        static::addGlobalScope('translate', function (Builder $builder) {
            $builder->with(['translations' => function($query){
                return $query->where('locale', app()->getLocale());
            }]);
        });
    }
    public function incentives()
    {
        return $this->hasMany(Incentive::class)->orderBy('earning');
    }

    public function incentive_logs()
    {
        return $this->hasMany(IncentiveLog::class);
    }
    public static function query()
    {
        return parent::query();
    }
    public function getNameAttribute($value){
        if (count($this->translations) > 0) {
            foreach ($this->translations as $translation) {
                if ($translation['key'] == 'name') {
                    return $translation['value'];
                }
            }
        }

        return $value;
    }

}
