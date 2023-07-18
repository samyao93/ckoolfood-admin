<!-- Header -->
<div class="card-header">
    <h5 class="card-header-title align-items-center d-flex">
        <img src="{{asset('/public/assets/admin/img/dashboard/most-rated.png')}}" alt="dashboard" class="card-header-icon mr-2 mb-1">
        <span>{{translate('messages.top_rated_foods')}}</span>
    </h5>
</div>
<!-- End Header -->

<!-- Body -->
<div class="card-body">
    <div class="row g-2">
        @foreach($most_rated_foods as $key=>$item)
        <div class="col-md-4 col-6">
            <a href="{{route('vendor.food.view',[$item['id']])}}" class="grid-card top--rated-food pb-4">
                <center>
                    <img class="initial-42" src="{{asset('storage/app/public/product')}}/{{$item['image']}}" onerror="this.src='{{asset('public/assets/admin/img/100x100/2.png')}}'" alt="{{$item->name}} image">
                </center>
                <div class="text-center mt-3">
                    <h5 class="name m-0 mb-1">
                        {{Str::limit($item->name??translate('messages.Food deleted!'),20,'...')}}
                    </h5>
                    <div class="rating">
                        <span class="text-warning"><i class="tio-star"></i> {{round($item['avg_rating'],1)}}</span>
                        <span class="text--title">({{$item['rating_count']}}  {{ translate('Reviews') }})</span>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</div>
<!-- End Body -->
