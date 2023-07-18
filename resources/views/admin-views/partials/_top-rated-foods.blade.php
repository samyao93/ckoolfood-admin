<!-- Header -->
<div class="card-header">
    <h5 class="card-header-title">
        <img src="{{asset('/public/assets/admin/img/dashboard/most-rated.png')}}" alt="dashboard" class="card-header-icon">
        <span>{{translate('messages.top_rated_foods')}}</span>
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
    <div class="row g-2">
    @foreach($top_rated_foods as $key=>$item)

        <div class="col-md-4 col-6">
            <div class="grid-card top--rated-food pb-4 cursor-pointer" onclick="location.href='{{route('admin.food.view',[$item['id']])}}'">
                <center>
                    <img class="initial-42"
                        src="{{asset('storage/app/public/product')}}/{{$item['image']}}"
                        onerror="this.src='{{asset('public/assets/admin/img/100x100/2.png')}}'"
                        alt="{{$item->name}} image">
                </center>
                <div class="text-center mt-3">
                    <h5 class="name m-0 mb-1">{{Str::limit($item->name??translate('messages.Food deleted!'),20,'...')}}</h5>
                    <div class="rating">
                        <span class="text-warning"><i class="tio-star"></i> {{round($item['avg_rating'],1)}}</span>
                        <span class="text--title">({{$item['rating_count']}}  {{ translate('Reviews') }})</span>
                    </div>
                </div>
            </div>
        </div>

    @endforeach
</div>
</div>
<!-- End Body -->
