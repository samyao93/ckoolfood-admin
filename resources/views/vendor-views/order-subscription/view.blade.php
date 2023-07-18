@extends('layouts.vendor.app')

@section('title',translate('messages.subscription_preview'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="d-print-none pb-2">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item">
                                <a class="breadcrumb-link"
                                   href="{{route('vendor.order.subscription.index')}}">
                                    {{translate('messages.subscription')}} {{ translate('messages.order') }}
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page"> {{translate('messages.subscription')}} {{ translate('messages.order') }} {{translate('messages.preview')}}</li>
                        </ol>
                    </nav>
                    <div class="d-sm-flex align-items-sm-center">
                        <h1 class="page-header-title">{{translate('messages.subscription')}} {{ translate('messages.order') }} {{translate('messages.id')}} # <a href="{{route('vendor.order.details',['id'=>$subscription->order->id])}}">{{$subscription->order->id}}</a>
                        </h1>
                        <span class="badge badge-primary ml-sm-3 p-1">
                            {{ translate('messages.'.$subscription->type) }}
                        </span>
                        <span class="ml-2 ml-sm-3">
                            <i class="tio-date-range">
                            </i> {{translate('messages.subscription_period')}} : <strong>
                                {{  Carbon\Carbon::parse($subscription->start_at)->locale(app()->getLocale())->translatedFormat('d M Y ') }}
                                -
                    {{-- {{date('d F Y' ,strtotime($subscription->start_at))}} -  --}}
                    {{-- {{date('d F Y', strtotime($subscription->end_at))}} --}}
                                {{  Carbon\Carbon::parse($subscription->end_at)->locale(app()->getLocale())->translatedFormat('d M Y ') }}

                            </strong>
                        </span>
                        @if (in_array($subscription->status, ['paused', 'canceled']))
                            <span class="badge badge-{{$subscription->status=='canceled'?'danger':'warning'}} ml-sm-3 p-1">
                                {{ translate('messages.'.$subscription->status) }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="page-header mb-3 border-bottom">
            <!-- Nav Scroller -->
            <div class="js-nav-scroller hs-nav-scroller-horizontal">
                <span class="hs-nav-scroller-arrow-prev" style="display: none;">
                    <a class="hs-nav-scroller-arrow-link" href="javascript:;">
                        <i class="tio-chevron-left"></i>
                    </a>
                </span>

                <span class="hs-nav-scroller-arrow-next" style="display: none;">
                    <a class="hs-nav-scroller-arrow-link" href="javascript:;">
                        <i class="tio-chevron-right"></i>
                    </a>
                </span>

                <!-- Nav -->
                <ul class="nav nav-tabs page-header-tabs">
                    <li class="nav-item">
                        <a class="nav-link {{$tab=='info'?'active':''}}" href="{{route('vendor.order.subscription.show', ['subscription'=>$subscription->id])}}">{{translate('messages.subscription')}} {{ translate('messages.order') }} {{ translate('messages.info') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{$tab=='delivery-log'?'active':''}}" href="{{route('vendor.order.subscription.show', ['subscription'=>$subscription->id])}}?tab=delivery-log"  aria-disabled="true">{{translate('messages.delivery_log')}}
                        <span data-toggle="tooltip" data-placement="right" data-original-title="{{translate('See_all_completed_subscription_deliveries_of_this_order_ID.')}}" class="input-label-secondary"><img src="{{asset('public/assets/admin/img/info-circle.svg')}}" alt="i"></span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{$tab=='pause-log'?'active':''}}" href="{{route('vendor.order.subscription.show', ['subscription'=>$subscription->id])}}?tab=pause-log"  aria-disabled="true">{{translate('messages.pause_log')}}
                            <span data-toggle="tooltip" data-placement="right" data-original-title="{{translate('See_all_paused_subscription_deliveries_of_this_order_ID_and_who_paused_it.')}}" class="input-label-secondary"><img src="{{asset('public/assets/admin/img/info-circle.svg')}}" alt="i"></span></a>
                    </li>
                </ul>
                <!-- End Nav -->
            </div>
            <!-- End Nav Scroller -->
        </div>
        <!-- End Page Header -->

        <div class="row">
            <div class="col-lg-8 mb-3 mb-lg-0">
                @include("vendor-views.order-subscription.partials._{$tab}")
            </div>

            <div class="col-lg-4">
                {{-- <div class="card mb-2">
                    <!-- Header -->
                    <div class="card-header border-0 justify-content-center pt-4 pb-0">
                        <h4 class="card-header-title">{{translate('messages.subscription_setup')}}</h4>
                    </div>
                    <!-- End Header -->

                    <!-- Body -->
                    <div class="card-body">
                        <!-- Static -->
                        <label class="form-label">{{translate('change_subscription_status')}}</label>

                        <!-- Unfold -->
                        <div>
                            <div class="dropdown">
                                <button class="form-control h--45px dropdown-toggle d-flex justify-content-between align-items-center" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{translate("messages.{$subscription->status}")}}</button>
                                <div class="dropdown-menu text-capitalize w-100" aria-labelledby="dropdownMenuButton">
                                    <button class="dropdown-item {{$subscription->status=='active' ? 'active' : ''}}" type="button" @if($subscription->status=='canceled') onclick="update_subscription_status('active')" @else disabled @endif>{{translate('messages.Active')}}</button>
                                    <button class="dropdown-item {{$subscription->status=='canceled' ? 'active' : ''}}" type="button" @if($subscription->status=='active') onclick="update_subscription_status('canceled')" @else disabled @endif>{{translate('messages.cancel')}}</button>
                                </div>
                            </div>
                        </div>
                        <!-- End Unfold -->
                        <button class="btn btn-sm btn-outline-danger w-100 text-capitalize mt-3" type="button" @if($subscription->status != 'canceled') onclick="update_subscription_status('paused')" @else disabled @endif>{{translate('messages.add_new_pause_log')}}</button>
                        <!-- Static -->
                    </div>

                    <!-- End Body -->
                </div> --}}
                <!-- Card -->
                <div class="card">
                    <!-- Header -->
                    <div class="card-header">
                        <h4 class="card-header-title">{{translate('messages.customer')}}</h4>
                    </div>
                    <!-- End Header -->

                    <!-- Body -->
                    @if($subscription->customer)
                        <div class="card-body">
                            <div class="media align-items-center" href="javascript:">
                                <div class="avatar avatar-circle mr-3">
                                    <img
                                        class="avatar-img"
                                        onerror="this.src='{{asset('public/assets/admin/img/160x160/img1.jpg')}}'"
                                        src="{{asset('storage/app/public/profile/'.$subscription->customer->image)}}"
                                        alt="Image Description">
                                </div>
                                <div class="media-body">
                                    <a class="text-body text-capitalize" href="#">{{$subscription->customer['f_name'].' '.$subscription->customer['l_name']}}</a>
                                </div>
                                <div class="media-body text-right">
                                    {{--<i class="tio-chevron-right text-body"></i>--}}
                                </div>
                            </div>

                            <hr>

                            <div class="media align-items-center" href="javascript:">
                                <div class="icon icon-soft-info icon-circle mr-3">
                                    <i class="tio-shopping-basket-outlined"></i>
                                </div>
                                <div class="media-body">
                                    <span
                                        class="text-body text-hover-primary">{{$subscription->customer->order_count}} {{translate('messages.orders')}}</span>
                                </div>
                                <div class="media-body text-right">
                                    {{--<i class="tio-chevron-right text-body"></i>--}}
                                </div>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between align-items-center">
                                <h5>{{translate('messages.contact')}} {{translate('messages.info')}}</h5>
                            </div>

                            <ul class="list-unstyled list-unstyled-py-2">
                                <li>
                                    <i class="tio-online mr-2"></i>
                                    {{$subscription->customer['email']}}
                                </li>
                                <li>
                                    <i class="tio-android-phone-vs mr-2"></i>
                                    {{$subscription->customer['phone']}}
                                </li>
                            </ul>

                            <div class="d-flex justify-content-between align-items-center">
                                <h5>{{translate('messages.addresses')}}</h5>
                            </div>

                            @foreach($subscription->customer->addresses as $address)
                                <ul class="list-unstyled list-unstyled-py-2">
                                    <li>
                                        <i class="tio-tab mr-2"></i>
                                        {{$address['address_type']}}
                                    </li>
                                    <li>
                                        <i class="tio-android-phone-vs mr-2"></i>
                                        {{$address['contact_person_number']}}
                                    </li>
                                    <li style="cursor: pointer">
                                        <a target="_blank" href="http://maps.google.com/maps?z=12&t=m&q=loc:{{$address['latitude']}}+{{$address['longitude']}}">
                                            <i class="tio-map mr-2"></i>
                                            {{$address['address']}}
                                        </a>
                                    </li>
                                </ul>
                                <hr>
                            @endforeach

                        </div>
                @endif
                <!-- End Body -->
                </div>
                <!-- End Card -->
            </div>
        </div>
        <!-- End Row -->
    </div>

            <!-- How it Works -->
            <div class="modal fade" id="how-it-works">
                <div class="modal-dialog status-warning-modal">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true" class="tio-clear"></span>
                            </button>
                        </div>
                        <div class="modal-body pb-5 pt-0">
                            <div class="single-item-slider owl-carousel">
                                <div class="item">
                                    <div class="max-349 mx-auto mb-20 text-center">
                                        <img src="{{asset('/public/assets/admin/img/landing-how.png')}}" alt="" class="mb-20">
                                        <h5 class="modal-title">{{translate('Receive_Order')}}</h5>
                                        <p>
                                            {{translate("Receive_and_see_the_requisitions_of_subscription-based_orders_from_customers.")}}
                                        </p>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="max-349 mx-auto mb-20 text-center">
                                        <img src="{{asset('/public/assets/admin/img/page-loader.gif')}}" alt="" class="mb-20">
                                        <h5 class="modal-title">{{translate('Prepare_Food')}}</h5>
                                        <p>
                                            {{translate("As_per_the_order,_prepare_food_for_customers_on_the_requested_date.")}}
                                        </p>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="max-349 mx-auto mb-20 text-center">
                                        <img src="{{asset('/public/assets/admin/img/notice-3.png')}}" alt="" class="mb-20">
                                        <h5 class="modal-title">{{translate('Deliver_Food')}}</h5>
                                        <p>
                                            {{translate('On_the_requested_date,_ensure_home_delivery_or_takeaway_delivery_on_time')}}
                                        </p>
                                        <div class="btn-wrap">
                                            <button type="button" data-dismiss="modal" class="btn btn--primary w-100" >{{ translate('Got_it') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center">
                                <div class="slide-counter"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
@endsection

@push('script_2')

    <script>
        $(document).on('ready', function () {
            // INITIALIZATION OF DATERANGEPICKER
            // =======================================================
            // $('.js-daterangepicker').daterangepicker(
            //     {
            //         zIndex: 1100,
            //     });

            // INITIALIZATION OF DATATABLES
            // =======================================================
            var datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'));

            $('#column1_search').on('keyup', function () {
                datatable
                    .columns(1)
                    .search(this.value)
                    .draw();
            });


            $('#column3_search').on('change', function () {
                datatable
                    .columns(2)
                    .search(this.value)
                    .draw();
            });


            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function () {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });
        // function update_subscription_status(status)
        // {
        //     if(status == 'paused'){
        //         Swal.fire( {
        //             title: "{{translate('messages.please_select_a_date_range')}}",
        //             html:'<input type="text" id="swal-input2" class="swal2-input form-control text-center" required/>',
        //             confirmButtonText: "{{translate('messages.Submit')}}",
        //             onOpen: function() {
        //                 $('#swal-input2').daterangepicker({
        //                     minDate: new Date(),
        //                     autoUpdateInput: false,
        //                     locale: {
        //                         cancelLabel: 'Clear'
        //                     }
        //                 });
        //                 $('.daterangepicker').css('z-index', 9999);
        //                 $('#swal-input2').on('apply.daterangepicker', function(ev, picker) {
        //                     $(this).val(picker.startDate.format('DD MMM YYYY') + ' - ' + picker.endDate.format('DD MMM YYYY'));
        //                 });

        //                 $('#swal-input2').on('cancel.daterangepicker', function(ev, picker) {
        //                     $(this).val('');
        //                 });
        //             },
        //             preConfirm: () => {
        //                 if((document.getElementById('swal-input2').value == "") || (document.getElementById('swal-input2').value == '') || ((document.getElementById('swal-input2').value == null)) ){
        //                     Swal.showValidationMessage(`{{translate('messages.please_select_a_date_range')}}`)
        //                 }
        //             }
        //         }).then((result) => {
        //             if(result.value){
        //                 let startDate = $('#swal-input2').data('daterangepicker').startDate.format('YYYY-MM-DD');
        //                 let endDate = $('#swal-input2').data('daterangepicker').endDate.format('YYYY-MM-DD')
        //                 $(`<form action="{{route('admin.order.subscription.update',['subscription'=>$subscription->id])}}" method="POST">
        //                 @csrf
        //                 @method('PUT')
        //                 <input type="hidden" name="status" value="` + status + `" >
        //                 <input type="hidden" name="start_date" value="` + startDate + `" >
        //                 <input type="hidden" name="end_date" value="` + endDate + `" >
        //                 </form>`).appendTo('body').submit();
        //             }
        //         });
        //     } else {
        //         Swal.fire({
        //             title: "{{translate('messages.are_you_sure')}}",
        //             text: status=='active' ? "{{translate('you_want_to_active_this_subscription')}}" : "{{translate('you_want_to_cancel_this_subscription')}}" ,
        //             type: 'warning',
        //             showCancelButton: true,
        //             cancelButtonColor: 'default',
        //             confirmButtonColor: '#FC6A57',
        //             cancelButtonText: '{{translate('messages.no')}}',
        //             confirmButtonText: '{{translate('messages.Yes')}}',
        //             reverseButtons: true
        //         }).then((result) => {
        //             console.log(result, result.value);
        //             if (result.value) {
        //                 $(`<form action="{{route('admin.order.subscription.update',['subscription'=>$subscription->id])}}" method="POST">
        //                 @csrf
        //                 @method('PUT')
        //                 <input type="hidden" name="status" value="` + status + `" >
        //                 </form>`).appendTo('body').submit();
        //             }
        //         })
        //     }

        // }
    </script>
@endpush
