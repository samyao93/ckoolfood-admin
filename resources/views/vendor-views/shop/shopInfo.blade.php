@extends('layouts.vendor.app')
@section('title',translate('messages.restaurant_view'))
@section('content')
<div class="content container-fluid">
    <!-- Page Header -->
     <div class="page-header">
        <div class="d-flex flex-wrap justify-content-between">
            <h2 class="page-header-title text-capitalize my-2">
                <div>
                    <div class="card-header-icon d-inline-flex mr-2 img">
                        <img src="{{asset('/public/assets/admin/img/resturant-panel/page-title/resturant.png')}}" alt="public">
                    </div>
                    <span>
                        {{translate('messages.my_shop')}} {{translate('messages.info')}}
                    </span>
                </div>
            </h2>
            <div class="my-2">
                <a class="btn btn--primary" href="{{route('vendor.shop.edit')}}"><i class="tio-edit"></i> {{translate('Edit Restaurant Info')}}</a>
            </div>
        </div>
    </div>
    <!-- End Page Header -->
    <div class="card border-0">
        <div class="card-body p-0">
            @if($shop->cover_photo)
                <div>
                    <img src="{{asset('storage/app/public/restaurant/cover/'.$shop->cover_photo)}}" onerror="this.src='{{asset('public/assets/admin/img/900x400/img1.jpg')}}'" class="initial-93">
                </div>
            @endif
            <div class="my-resturant--card">
                @if($shop->image=='def.png')
                <div class="my-resturant--avatar">
                    <img src="{{asset('public/assets/back-end')}}/img/shop.png"
                    class="border"
                    onerror="this.src='{{asset('public/assets/admin/img/160x160/img1.jpg')}}'"
                    alt="User Pic">
                </div>

                @else

                    <div class="my-resturant--avatar">
                        <img src="{{asset('storage/app/public/restaurant/'.$shop->logo)}}" class="border"
                        onerror="this.src='{{asset('public/assets/admin/img/160x160/img1.jpg')}}'" alt="">
                    </div>


                @endif

                <!-- http://localhost/Food-multivendor/public/assets/admin/img/restaurant_cover.jpg -->
                <div class="my-resturant--content">
                    <span class="d-block mb-1 pb-1">
                        <strong>{{translate('messages.name')}} :</strong> {{$shop->name}}
                    </span>
                    <span class="d-block mb-1 pb-1">
                        <strong>{{translate('messages.phone')}} :</strong> <a href="tel:{{$shop->phone}}">{{$shop->phone}}</a>
                    </span>
                    <span class="d-block mb-1 pb-1">
                        <strong>{{translate('messages.address')}} :</strong> {{$shop->address}}
                    </span>
                    <span class="d-block mb-1 pb-1">
                        <strong>{{translate('messages.admin_commission')}} :</strong> {{(isset($shop->comission)?$shop->comission:\App\Models\BusinessSetting::where('key','admin_commission')->first()->value)}}%
                    </span>
                    <span class="d-block mb-1 pb-1">
                        <strong>{{translate('messages.vat/tax')}} :</strong> {{$shop->tax}}%
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
    <!-- Page level plugins -->
@endpush
