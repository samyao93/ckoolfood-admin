<!-- Header -->
<div class="card-header">
    <h5 class="card-header-title align-items-center d-flex">
        <img src="{{asset('/public/assets/admin/img/dashboard/top-selling.png')}}" alt="dashboard" class="card-header-icon mr-2 mb-1">
        <span>{{translate('messages.top_selling_foods')}}</span>
    </h5>
</div>
<!-- End Header -->

<!-- Body -->
<div class="card-body">
    <div class="row g-2">
        @foreach($top_sell as $key=>$item)
            <div class="col-md-4 col-sm-6">
                <div class="grid-card top-selling-food-card pt-0" onclick="location.href='{{route('vendor.food.view',[$item['id']])}}'">
                    <div class="position-relative">
                        <span class="sold--count-badge">
                            {{translate('messages.sold')}} : {{$item['order_count']}}
                        </span>
                        <img class="initial-43"
                            src="{{asset('storage/app/public/product')}}/{{$item['image']}}"
                            onerror="this.src='{{asset('public/assets/admin/img/100x100/food.png')}}'" alt="{{$item->name}} image">
                    </div>
                    <div class="text-center mt-2">
                        <span>{{Str::limit($item->name??translate('messages.Food deleted!'),20,'...')}}</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
<!-- End Body -->
