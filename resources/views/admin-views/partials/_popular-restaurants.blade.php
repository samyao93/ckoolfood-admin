<!-- Header -->
<div class="card-header">
    <h5 class="card-header-title">
        <img src="{{ asset('/public/assets/admin/img/dashboard/most-popular.png') }}" alt="dashboard"
            class="card-header-icon">
        {{ translate('Most Popular Restaurants') }}
    </h5>
    @php($params = session('dash_params'))
    @if ($params['zone_id'] != 'all')
        @php($zone_name = \App\Models\Zone::where('id', $params['zone_id'])->first()->name)
    @else
        @php($zone_name = translate('All'))
    @endif
    <span class="badge badge-soft--info my-2">{{ translate('messages.zone') }} : {{ $zone_name }}</span>
</div>
<!-- End Header -->

<!-- Body -->
<div class="card-body">
    <ul class="most-popular">
        @foreach ($popular as $key => $item)
            <li onclick="location.href='{{ route('admin.restaurant.view', $item->restaurant_id) }}'" class="cursor-pointer">
                <div class="img-container">
                    <img onerror="this.src='{{ asset('public/assets/admin/img/100x100/1.png') }}'"
                        src="{{ asset('storage/app/public/restaurant') }}/{{ $item->restaurant['logo'] }}">
                    <span class="ml-2">
                        {{ Str::limit($item->restaurant->name ?? translate('messages.Restaurant deleted!'), 20, '...') }} </span>
                </div>
                <span class="count">
                    {{ $item['count'] }} <i class="tio-heart"></i>
                </span>
            </li>
        @endforeach
    </ul>
</div>
