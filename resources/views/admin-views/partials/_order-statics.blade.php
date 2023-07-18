<div class="d-flex flex-wrap justify-content-between statistics--title-area">
    <div class="statistics--title pr-sm-3">
        <h4 class="m-0 mr-1">
            {{translate('order_statistics')}}
        </h4>
        @php($params=session('dash_params'))
        @if($params['zone_id']!='all')
            @php($zone_name=\App\Models\Zone::where('id',$params['zone_id'])->first()->name)
        @else
            @php($zone_name=translate('All'))
        @endif
        <span class="badge badge-soft--info my-2">{{translate('messages.zone')}} : {{$zone_name}}</span>
    </div>
    <div class="statistics--select">
        <select class="custom-select" name="statistics_type" onchange="order_stats_update(this.value)">
            <option
                value="overall" {{$params['statistics_type'] == 'overall'?'selected':''}}>
                {{translate('messages.Overall Statistics')}}
            </option>
            <option
                value="today" {{$params['statistics_type'] == 'today'?'selected':''}}>
                {{__("messages.Today's Statistics")}}
            </option>
        </select>
    </div>
</div>

<div class="row g-2">
    <div class="col-xl-3 col-sm-6">
        <div class="resturant-card dashboard--card bg--2 cursor-pointer" onclick="location.href='{{route('admin.order.list',['delivered'])}}'">

            <h4 class="title">{{$data['delivered']}}</h4>
            <span class="subtitle">{{translate('messages.delivered')}} {{translate('messages.orders')}}</span>
            <img class="resturant-icon" src="{{asset('/public/assets/admin/img/dashboard/1.png')}}" alt="dashboard">
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="resturant-card dashboard--card bg--3 cursor-pointer" onclick="location.href='{{route('admin.order.list',['canceled'])}}'">
            <h4 class="title">{{$data['canceled']}}</h4>
            <span class="subtitle">{{translate('messages.canceled')}} {{translate('messages.orders')}}</span>
            <img class="resturant-icon" src="{{asset('/public/assets/admin/img/dashboard/2.png')}}" alt="dashboard">
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="resturant-card dashboard--card bg--5 cursor-pointer" onclick="location.href='{{route('admin.order.list',['refunded'])}}'">
            <h4 class="title">{{$data['refunded']}}</h4>
            <span class="subtitle">{{translate('messages.refunded')}} {{translate('messages.orders')}}</span>
            <img class="resturant-icon" src="{{asset('/public/assets/admin/img/dashboard/3.png')}}" alt="dashboard">
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="resturant-card dashboard--card bg--14 cursor-pointer" onclick="location.href='{{route('admin.order.list',['failed'])}}'">
            <h4 class="title">{{$data['refund_requested']}}</h4>
            <span class="subtitle">{{translate('messages.payment')}} {{translate('messages.failed')}} {{translate('messages.orders')}}</span>
            <img class="resturant-icon" src="{{asset('/public/assets/admin/img/dashboard/4.png')}}" alt="dashboard">
        </div>
    </div>
</div>
