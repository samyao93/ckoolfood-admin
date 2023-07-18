@extends('layouts.vendor.app')

@section('title',translate('Delivery Man Preview'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">

        <div class="card border-0">
            <div class="card-header border-0">
                <h2 class="page-header-title text-capitalize">
                    <div class="card-header-icon d-inline-flex img">
                        <i class="tio-add-circle-outlined"></i>
                    </div>
                    <span>{{translate('Delivery Man Preview')}}</span>
                </h2>
            </div>
            <div class="card-body pt-0">
                <div class="js-nav-scroller hs-nav-scroller-horizontal mb-4">
                    <!-- Nav -->
                    <ul class="nav nav-tabs page-header-tabs m-0">
                        <li class="nav-item">
                            <a class="nav-link active" href="{{route('vendor.delivery-man.preview', ['id'=>$dm->id, 'tab'=> 'info'])}}"  aria-disabled="true">{{translate('messages.info')}}</a>
                        </li>
                        <!-- <li class="nav-item">
                            <a class="nav-link" href="{{route('vendor.delivery-man.preview', ['id'=>$dm->id, 'tab'=> 'transaction'])}}"  aria-disabled="true">{{translate('messages.transaction')}}</a>
                        </li> -->
                    </ul>
                    <!-- End Nav -->
                </div>
                <div class="row g-3">
                    <!-- Earnings (Monthly) Card Example -->
                    <div class="col-sm-6 col-md-4">
                        <div class="resturant-card dashboard--card bg--2">
                            <h4 class="title">{{$dm->orders->count()}}</h4>
                            <span class="subtitle">
                                {{translate('messages.total')}} {{translate('messages.delivered')}} {{translate('messages.orders')}}
                            </span>
                            <img class="resturant-icon" src="{{asset('public/assets/admin/img/resturant-panel/deliveryman/delivered.png')}}" alt="dashboard">
                        </div>
                    </div>

                    <!-- Collected Cash Card Example -->
                    <div class="col-sm-6 col-md-4">
                        <div class="resturant-card dashboard--card bg--3">
                            <h4 class="title">{{\App\CentralLogics\Helpers::format_currency($dm->wallet?$dm->wallet->collected_cash:0.0)}}</h4>
                            <span class="subtitle">{{translate('messages.cash_in_hand')}}</span>
                            <img class="resturant-icon" src="{{asset('public/assets/admin/img/resturant-panel/deliveryman/cash.png')}}" alt="dashboard">
                        </div>
                    </div>

                    <!-- Total Earning Card Example -->
                    <div class="col-sm-6 col-md-4">
                        <div class="resturant-card dashboard--card bg--1">
                            <h4 class="title">{{\App\CentralLogics\Helpers::format_currency($dm->wallet?$dm->wallet->total_earning:0.00)}}</h4>
                            <span class="subtitle">{{translate('messages.total_earning')}}</span>
                            <img class="resturant-icon" src="{{asset('public/assets/admin/img/resturant-panel/deliveryman/earning.png')}}" alt="dashboard">
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- Card -->
        <div class="card mb-3 mb-lg-5 mt-2">
            <div class="card-header border-0">
                <h4 class="page-header-title">
                    <span class="mr-2">{{$dm['f_name'].' '.$dm['l_name']}}</span>
                    @if($dm['status']) @if($dm['active']) <label class="badge badge-soft-primary m-0">{{translate('messages.online')}}</label> @else <label class="badge badge-soft-success m-0">{{translate('messages.offline')}}</label> @endif  @else <span class="badge badge-danger">{{translate('messages.suspended')}}</span> @endif</h4>

                <a  href="javascript:"  onclick="request_alert('{{route('vendor.delivery-man.status',[$dm['id'],$dm->status?0:1])}}','{{$dm->status?'Want to suspend this deliveryman ?':'Want to unsuspend this deliveryman'}}')" class="btn {{$dm->status?'btn--danger':'btn--success'}}">
                        {{$dm->status?translate('messages.suspend_this_delivery_man'):translate('messages.unsuspend_this_delivery_man')}}
                </a>
            </div>
            <!-- Body -->
            <div class="card-body">
                <div class="row align-items-md-center">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center justify-content-center">
                            <img class="avatar avatar-xxl avatar-4by3 mr-4 mw-120px"
                                 onerror="this.src='{{asset('public/assets/admin/img/160x160/img1.jpg')}}'"
                                 src="{{asset('storage/app/public/delivery-man')}}/{{$dm['image']}}"
                                 alt="Image Description">

                                 <div class="d-block">
                                    <div class="rating--review">
                                        <h1 class="title">{{count($dm->rating)>0?number_format($dm->rating[0]->average, 1, '.', ' '):0}}<span class="out-of">/5</span></h1>
                                        @if (count($dm->rating)>0)
                                        @if ($dm->rating[0]->average == 5)
                                        <div class="rating">
                                            <span><i class="tio-star"></i></span>
                                            <span><i class="tio-star"></i></span>
                                            <span><i class="tio-star"></i></span>
                                            <span><i class="tio-star"></i></span>
                                            <span><i class="tio-star"></i></span>
                                        </div>
                                        @elseif ($dm->rating[0]->average < 5 && $dm->rating[0]->average >= 4.5)
                                        <div class="rating">
                                            <span><i class="tio-star"></i></span>
                                            <span><i class="tio-star"></i></span>
                                            <span><i class="tio-star"></i></span>
                                            <span><i class="tio-star"></i></span>
                                            <span><i class="tio-star-half"></i></span>
                                        </div>
                                        @elseif ($dm->rating[0]->average < 4.5 && $dm->rating[0]->average >= 4)
                                        <div class="rating">
                                            <span><i class="tio-star"></i></span>
                                            <span><i class="tio-star"></i></span>
                                            <span><i class="tio-star"></i></span>
                                            <span><i class="tio-star"></i></span>
                                            <span><i class="tio-star-outlined"></i></span>
                                        </div>
                                        @elseif ($dm->rating[0]->average < 4 && $dm->rating[0]->average >= 3.5)
                                        <div class="rating">
                                            <span><i class="tio-star"></i></span>
                                            <span><i class="tio-star"></i></span>
                                            <span><i class="tio-star"></i></span>
                                            <span><i class="tio-star-half"></i></span>
                                            <span><i class="tio-star-outlined"></i></span>
                                        </div>
                                        @elseif ($dm->rating[0]->average < 3.5 && $dm->rating[0]->average >= 3)
                                        <div class="rating">
                                            <span><i class="tio-star"></i></span>
                                            <span><i class="tio-star"></i></span>
                                            <span><i class="tio-star"></i></span>
                                            <span><i class="tio-star-outlined"></i></span>
                                            <span><i class="tio-star-outlined"></i></span>
                                        </div>
                                        @elseif ($dm->rating[0]->average < 3 && $dm->rating[0]->average >= 2.5)
                                        <div class="rating">
                                            <span><i class="tio-star"></i></span>
                                            <span><i class="tio-star"></i></span>
                                            <span><i class="tio-star-half"></i></span>
                                            <span><i class="tio-star-outlined"></i></span>
                                            <span><i class="tio-star-outlined"></i></span>
                                        </div>
                                        @elseif ($dm->rating[0]->average < 2.5 && $dm->rating[0]->average > 2)
                                        <div class="rating">
                                            <span><i class="tio-star"></i></span>
                                            <span><i class="tio-star"></i></span>
                                            <span><i class="tio-star-outlined"></i></span>
                                            <span><i class="tio-star-outlined"></i></span>
                                            <span><i class="tio-star-outlined"></i></span>
                                        </div>
                                        @elseif ($dm->rating[0]->average < 2 && $dm->rating[0]->average >= 1.5)
                                        <div class="rating">
                                            <span><i class="tio-star"></i></span>
                                            <span><i class="tio-star-half"></i></span>
                                            <span><i class="tio-star-outlined"></i></span>
                                            <span><i class="tio-star-outlined"></i></span>
                                            <span><i class="tio-star-outlined"></i></span>
                                        </div>
                                        @elseif ($dm->rating[0]->average < 1.5 && $dm->rating[0]->average > 1)
                                        <div class="rating">
                                            <span><i class="tio-star"></i></span>
                                            <span><i class="tio-star-outlined"></i></span>
                                            <span><i class="tio-star-outlined"></i></span>
                                            <span><i class="tio-star-outlined"></i></span>
                                            <span><i class="tio-star-outlined"></i></span>
                                        </div>
                                        @elseif ($dm->rating[0]->average < 1 && $dm->rating[0]->average > 0)
                                        <div class="rating">
                                            <span><i class="tio-star-half"></i></span>
                                            <span><i class="tio-star-outlined"></i></span>
                                            <span><i class="tio-star-outlined"></i></span>
                                            <span><i class="tio-star-outlined"></i></span>
                                            <span><i class="tio-star-outlined"></i></span>
                                        </div>
                                        @elseif ($dm->rating[0]->average == 1)
                                        <div class="rating">
                                            <span><i class="tio-star"></i></span>
                                            <span><i class="tio-star-outlined"></i></span>
                                            <span><i class="tio-star-outlined"></i></span>
                                            <span><i class="tio-star-outlined"></i></span>
                                            <span><i class="tio-star-outlined"></i></span>
                                        </div>
                                        @elseif ($dm->rating[0]->average == 0)
                                        <div class="rating">
                                            <span><i class="tio-star-outlined"></i></span>
                                            <span><i class="tio-star-outlined"></i></span>
                                            <span><i class="tio-star-outlined"></i></span>
                                            <span><i class="tio-star-outlined"></i></span>
                                            <span><i class="tio-star-outlined"></i></span>
                                        </div>
                                        @endif
                                    @endif
                                    <div class="info">
                                        {{-- <span class="mr-3">{{$dm->rating->count()}} {{translate('messages.rating')}}</span> --}}
                                        <span>{{$dm->reviews->count()}} {{translate('messages.reviews')}}</span>
                                    </div>
                                    </div>
                                </div>

                            {{-- <div class="d-block">
                                <h4 class="display-2 text-dark mb-0">{{count($dm->rating)>0?number_format($dm->rating[0]->average, 2, '.', ' '):0}}</h4>
                                <p> of {{$dm->reviews->count()}} {{translate('messages.reviews')}}
                                    <span class="badge badge-soft-dark badge-pill ml-1"></span>
                                </p>
                            </div> --}}

                        </div>
                    </div>

                    <div class="col-md-6">
                        <ul class="list-unstyled list-unstyled-py-2 mb-0 rating--review-right py-3">
                        @php($total=$dm->reviews->count())
                        <!-- Review Ratings -->
                            <li class="d-flex align-items-center font-size-sm">
                                @php($five=\App\CentralLogics\Helpers::dm_rating_count($dm['id'],5))
                                <span
                                    class="progress-name mr-3">{{translate('messages.Excellent')}}</span>
                                <div class="progress flex-grow-1">
                                    <div class="progress-bar" role="progressbar"
                                         style="width: {{$total==0?0:($five/$total)*100}}%;"
                                         aria-valuenow="{{$total==0?0:($five/$total)*100}}"
                                         aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="ml-3">{{$five}}</span>
                            </li>
                            <!-- End Review Ratings -->

                            <!-- Review Ratings -->
                            <li class="d-flex align-items-center font-size-sm">
                                @php($four=\App\CentralLogics\Helpers::dm_rating_count($dm['id'],4))
                                <span class="progress-name mr-3">{{translate('messages.Good')}}</span>
                                <div class="progress flex-grow-1">
                                    <div class="progress-bar" role="progressbar"
                                         style="width: {{$total==0?0:($four/$total)*100}}%;"
                                         aria-valuenow="{{$total==0?0:($four/$total)*100}}"
                                         aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="ml-3">{{$four}}</span>
                            </li>
                            <!-- End Review Ratings -->

                            <!-- Review Ratings -->
                            <li class="d-flex align-items-center font-size-sm">
                                @php($three=\App\CentralLogics\Helpers::dm_rating_count($dm['id'],3))
                                <span class="progress-name mr-3">{{translate('messages.Average')}}</span>
                                <div class="progress flex-grow-1">
                                    <div class="progress-bar" role="progressbar"
                                         style="width: {{$total==0?0:($three/$total)*100}}%;"
                                         aria-valuenow="{{$total==0?0:($three/$total)*100}}"
                                         aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="ml-3">{{$three}}</span>
                            </li>
                            <!-- End Review Ratings -->

                            <!-- Review Ratings -->
                            <li class="d-flex align-items-center font-size-sm">
                                @php($two=\App\CentralLogics\Helpers::dm_rating_count($dm['id'],2))
                                <span class="progress-name mr-3">{{translate('messages.Below_Average')}}</span>
                                <div class="progress flex-grow-1">
                                    <div class="progress-bar" role="progressbar"
                                         style="width: {{$total==0?0:($two/$total)*100}}%;"
                                         aria-valuenow="{{$total==0?0:($two/$total)*100}}"
                                         aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="ml-3">{{$two}}</span>
                            </li>
                            <!-- End Review Ratings -->

                            <!-- Review Ratings -->
                            <li class="d-flex align-items-center font-size-sm">
                                @php($one=\App\CentralLogics\Helpers::dm_rating_count($dm['id'],1))
                                <span class="progress-name mr-3">{{translate('messages.poor')}}</span>
                                <div class="progress flex-grow-1">
                                    <div class="progress-bar" role="progressbar"
                                         style="width: {{$total==0?0:($one/$total)*100}}%;"
                                         aria-valuenow="{{$total==0?0:($one/$total)*100}}"
                                         aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="ml-3">{{$one}}</span>
                            </li>
                            <!-- End Review Ratings -->
                        </ul>
                    </div>
                </div>
            </div>
            <!-- End Body -->
        </div>
        <!-- End Card -->

        @php($restaurant=\App\CentralLogics\Helpers::get_restaurant_data())
        @if ($restaurant->restaurant_model == 'commission' && $restaurant->reviews_section || ($restaurant->restaurant_model == 'subscription' && isset($restaurant->restaurant_sub) && $restaurant->restaurant_sub->review))
        <!-- Card -->
        <div class="card">
            <!-- Table -->
            <div class="table-responsive datatable-custom">
                <table id="datatable" class="table table-borderless table-thead-bordered table-nowrap card-table"
                       data-hs-datatables-options='{
                     "columnDefs": [{
                        "targets": [0, 3, 6],
                        "orderable": false
                      }],
                     "order": [],
                     "info": {
                       "totalQty": "#datatableWithPaginationInfoTotalQty"
                     },
                     "search": "#datatableSearch",
                     "entries": "#datatableEntries",
                     "pageLength": 25,
                     "isResponsive": false,
                     "isShowPaging": false,
                     "pagination": "datatablePagination"
                   }'>
                    <thead class="thead-light">
                    <tr>
                        <th>{{translate('messages.reviewer')}}</th>
                        <th>Order ID</th>
                        <th>{{translate('messages.review')}}</th>
                        <th>{{translate('messages.date')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($reviews as $review)
                        <tr>
                            <td>
                                @if ($review->customer)
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-circle">
                                        <img class="avatar-img" width="75" height="75"
                                             onerror="this.src='{{asset('public/assets/admin/img/160x160/img1.jpg')}}'"
                                             src="{{asset('storage/app/public/profile/'.$review->customer->image)}}"
                                             alt="Image Description">
                                    </div>
                                    <div class="ml-3">
                                    <span class="d-block h5 text-hover-primary mb-0">{{$review->customer['f_name']." ".$review->customer['l_name']}} <i
                                            class="tio-verified text-primary" data-toggle="tooltip" data-placement="top"
                                            title="Verified Customer"></i></span>
                                        <span class="d-block font-size-sm text-body">{{$review->customer->email}}</span>
                                    </div>
                                </div>
                                @else
                                    {{translate('messages.customer_not_found')}}
                                @endif
                            </td>
                            <td>
                                <a href="{{route('vendor.order.details',['id'=>$review->order_id])}}">{{$review->order_id}}</a>

                            </td>
                            <td>
                                <div class="text-wrap w-18rem">
                                    <div class="d-flex mb-2">
                                        <label class="badge badge-soft-info">
                                            {{$review->rating}} <i class="tio-star"></i>
                                        </label>
                                    </div>

                                    <p>
                                        {{$review['comment']}}
                                    </p>
                                </div>
                            </td>
                            {{--<td>
                                @foreach(json_decode($review['attachment'],true) as $attachment)
                                    <img width="100" onerror="this.src='{{asset('public/assets/admin/img/160x160/img2.jpg')}}'" src="{{asset('storage/app/public')}}/{{$attachment}}">
                                @endforeach
                            </td>--}}
                            <td>
                                {{-- {{  Carbon\Carbon::parse($review->created_at)->locale(app()->getLocale())->translatedFormat('d M Y'.config('timeformat')) }} --}}

                                {{date('d M Y '. config('timeformat'),strtotime($review['created_at']))}}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @if(count($reviews) === 0)
                <div class="empty--data">
                    <img src="{{asset('/public/assets/admin/img/empty.png')}}" alt="public">
                    <h5>
                        {{translate('no_data_found')}}
                    </h5>
                </div>
                @endif
            </div>
            <!-- End Table -->

            <!-- Footer -->
            <div class="card-footer border-0">
                <!-- Pagination -->
                <div class="row justify-content-center justify-content-sm-between align-items-sm-center">
                    <div class="col-12">
                        {!! $reviews->links() !!}
                    </div>
                </div>
                <!-- End Pagination -->
            </div>
            <!-- End Footer -->
        </div>
        <!-- End Card -->
        @endif


    </div>
@endsection

@push('script_2')
<script>
    function request_alert(url, message) {
        Swal.fire({
            title: '{{ translate('Are you sure?') }}',
            text: message,
            type: 'warning',
            showCancelButton: true,
            cancelButtonColor: 'default',
            confirmButtonColor: '#FC6A57',
            cancelButtonText: '{{ translate('no') }}',
            confirmButtonText: '{{ translate('yes') }}',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                location.href = url;
            }
        })
    }
</script>
@endpush
