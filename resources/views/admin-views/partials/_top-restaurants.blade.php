<!-- Header -->
<div class="card-header">
    <h5 class="card-header-title">
        <img src="{{asset('/public/assets/admin/img/dashboard/top-resturant.png')}}" alt="dashboard" class="card-header-icon">
        <span>{{ translate('Top Restaurants') }}</span>
    </h5>
    @php($params=session('dash_params'))
    @if($params['zone_id']!='all')
        @php($zone_name=\App\Models\Zone::where('id',$params['zone_id'])->first()->name)
    @else
    @php($zone_name=translate('All'))
    @endif
    <span class="badge badge-soft--info my-2">{{translate('messages.zone')}} : {{$zone_name}}</span>
</div>
<!-- End Header -->

<!-- Body -->
<div class="card-body">
    <ul class="top--resturant">
    @foreach($top_restaurants as $key=>$item)
        <li>
            <div class="top--resturant-item" onclick="location.href='{{route('admin.restaurant.view', $item->id)}}'">
                <img onerror="this.src='{{asset('public/assets/admin/img/100x100/1.png')}}'" src="{{asset('storage/app/public/restaurant')}}/{{$item['logo']}}">
                <div class="top--resturant-item-content">
                    <h5 class="name m-0">
                            {{Str::limit($item->name??translate('messages.Restaurant deleted!'), 20, '...')}}
                    </h5>
                    <h5 class="info m-0"><span class="text-warning">{{$item['order_count']}}</span> <small>{{ translate('Orders') }}</small></h5>
                </div>
            </div>
        </li>
    @endforeach
    </ul>
</div>
<!-- End Body -->
