@extends('layouts.admin.app')

@section('title',$restaurant->name."'s".translate('messages.settings'))

@push('css_or_js')
    <!-- Custom styles for this page -->
    <link href="{{asset('public/assets/admin/css/croppie.css')}}" rel="stylesheet">

@endpush

@section('content')
<div class="content container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex flex-wrap justify-content-between align-items-center">
            <h1 class="page-header-title text-break">
                <i class="tio-museum"></i> <span>{{$restaurant->name}}</span>
            </h1>
        </div>
        <!-- Nav Scroller -->
        <div class="js-nav-scroller hs-nav-scroller-horizontal">
            <span class="hs-nav-scroller-arrow-prev initial-hidden">
                <a class="hs-nav-scroller-arrow-link" href="javascript:;">
                    <i class="tio-chevron-left"></i>
                </a>
            </span>

            <span class="hs-nav-scroller-arrow-next initial-hidden">
                <a class="hs-nav-scroller-arrow-link" href="javascript:;">
                    <i class="tio-chevron-right"></i>
                </a>
            </span>

            <!-- Nav -->
            <ul class="nav nav-tabs page-header-tabs">
            <li class="nav-item">
                    <a class="nav-link" href="{{route('admin.restaurant.view', $restaurant->id)}}">{{translate('messages.overview')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('admin.restaurant.view', ['restaurant'=>$restaurant->id, 'tab'=> 'order'])}}"  aria-disabled="true">{{translate('messages.orders')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('admin.restaurant.view', ['restaurant'=>$restaurant->id, 'tab'=> 'product'])}}"  aria-disabled="true">{{translate('messages.foods')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('admin.restaurant.view', ['restaurant'=>$restaurant->id, 'tab'=> 'reviews'])}}"  aria-disabled="true">{{translate('messages.reviews')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{route('admin.restaurant.view', ['restaurant'=>$restaurant->id, 'tab'=> 'discount'])}}"  aria-disabled="true">{{translate('discounts')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('admin.restaurant.view', ['restaurant'=>$restaurant->id, 'tab'=> 'transaction'])}}"  aria-disabled="true">{{translate('messages.transactions')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('admin.restaurant.view', ['restaurant'=>$restaurant->id, 'tab'=> 'settings'])}}"  aria-disabled="true">{{translate('messages.settings')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('admin.restaurant.view', ['restaurant'=>$restaurant->id, 'tab'=> 'conversations'])}}"  aria-disabled="true">{{translate('messages.conversations')}}</a>
                </li>
                @if ($restaurant->restaurant_model != 'none' && $restaurant->restaurant_model != 'commission' )
                <li class="nav-item">
                    <a class="nav-link" href="{{route('admin.restaurant.view', ['restaurant'=>$restaurant->id, 'tab'=> 'subscriptions'])}}"  aria-disabled="true">{{translate('messages.subscription')}}</a>
                </li>
                @endif
            </ul>
            <!-- End Nav -->
        </div>
        <!-- End Nav Scroller -->
    </div>
        <!-- End Page Header -->
    <!-- Page Heading -->
    <div class="card">
        <div class="card-header py-2">
            <div class="search--button-wrapper">
                <h5 class="card-title">
                    <span class="card-header-icon mr-2"><i class="tio-new-release"></i></span>
                    <span>{{translate('messages.discount')}} {{translate('messages.info')}}</span>
                </h5>
                <button type="button" class="btn-sm btn--primary" data-toggle="modal" data-target="#updatesettingsmodal">
                    <i class="tio-open-in-new"></i>
                    {{$restaurant->discount? translate('messages.update') : translate('messages.add').' '.translate('messages.discount')}}
                </button>

                @if($restaurant->discount)
                <button type="button" onclick="form_alert('discount-{{$restaurant->id}}','{{ translate('Want to remove discount?') }}')" class="btn btn--danger"><i class="tio-delete-outlined"></i> {{translate('messages.delete')}}</button>
                @endif
            </div>
        </div>
        <div class="card-body">
            @if($restaurant->discount)
            <div class="text--primary mb-3">
                {{translate('messages.*_this_discount_is_applied_on_all_the_foods_in_your_restaurant')}}
            </div>
            <div class="row gy-3">
                <div class="col-md-4 align-self-center text-center">
                    <div class="discount-item text-center">
                        <h5 class="subtitle">{{translate('messages.discount')}} {{translate('messages.amount')}}</h5>
                        <h4 class="amount">{{$restaurant->discount?round($restaurant->discount->discount):0}}%</h4>
                    </div>
                </div>
                <div class="col-md-4 text-center text-md-left">
                    <div class="discount-item">
                        <h5 class="subtitle">{{translate('messages.duration')}}</h5>
                        <ul class="list-unstyled list-unstyled-py-3 text-dark">
                            <li class="p-0 pt-1 justify-content-center justify-content-md-start">
                                <span>{{translate('messages.start')}} {{translate('messages.date')}} :</span> <strong>

                                    {{-- {{ Carbon\Carbon::parse($restaurant->discount->start_date)->locale(app()->getLocale())->translatedFormat('d M Y ') }} --}}

                                    {{$restaurant->discount?Carbon\Carbon::parse($restaurant->discount->start_date)->locale(app()->getLocale())->translatedFormat('d M Y ' .config('timeformat')) :''}}
                                    
                                    {{-- {{$restaurant->discount?date(config('timeformat'), strtotime($restaurant->discount->start_time)):''}} --}}
                                
                                
                                
                                </strong></li>
                            <li class="p-0 pt-1 justify-content-center justify-content-md-start">
                                <span>{{translate('messages.end')}} {{translate('messages.date')}} :</span> <strong>

                                    {{$restaurant->discount?Carbon\Carbon::parse($restaurant->discount->end_time)->locale(app()->getLocale())->translatedFormat('d M Y ' .config('timeformat')):''}} 
                                    {{-- {{$restaurant->discount?date(config('timeformat'), strtotime($restaurant->discount->end_time)):''}} --}}
                                </strong></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4 text-center text-md-left">
                    <div class="discount-item style-2">
                        <h5 class="subtitle">{{translate('messages.purchase')}} {{translate('messages.conditions')}}</h5>
                        <ul class="list-unstyled list-unstyled-py-3 text-dark">
                            <li class="p-0 pt-1 justify-content-center justify-content-md-start">
                                <span>{{translate('messages.max')}} {{translate('messages.purchase')}} {{translate('messages.discount')}} :</span> <strong>{{\App\CentralLogics\Helpers::format_currency($restaurant->discount?$restaurant->discount->max_discount:0)}}</strong></li>
                            <li class="p-0 pt-1 justify-content-center justify-content-md-start">
                                <span>{{translate('messages.min')}} {{translate('messages.purchase')}} {{translate('messages.amount')}} :</span> <strong>{{\App\CentralLogics\Helpers::format_currency($restaurant->discount?$restaurant->discount->min_purchase:0)}}</strong></li>
                        </ul>
                    </div>
                </div>
            </div>
            @else
            <div class="form-group">
                <label class="d-flex justify-content-center rounded px-4 form-control" for="restaurant_status">
                    <span class="card-subtitle">{{translate('messages.no_discount')}}</span>
                </label>
            </div>
            @endif



        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="updatesettingsmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header pb-3 shadow">
        <h4 class="modal-title m-0" id="exampleModalCenterTitle">{{$restaurant->discount?translate('messages.update'):translate('messages.add')}} {{translate('messages.discount')}}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{route('admin.restaurant.discount',[$restaurant['id']])}}" method="post" id="discount-form">
            @csrf
            <div class="row gx-2">
                <div class="col-md-4 col-6">
                    <div class="form-group">
                        <label class="form-label font-medium text-capitalize" for="title">{{translate('messages.discount')}} {{translate('messages.amount')}} (%)</label>
                        <input type="number" min="0" max="100" step="0.01" name="discount" class="form-control" required value="{{$restaurant->discount?$restaurant->discount->discount:'0'}}">
                    </div>
                </div>
                <div class="col-md-4 col-6">
                    <div class="form-group">
                        <label class="form-label font-medium text-capitalize" for="title">{{translate('messages.min')}} {{translate('messages.purchase')}} ({{\App\CentralLogics\Helpers::currency_symbol()}})</label>
                        <input type="number" name="min_purchase" step="0.01" min="0" max="100000" class="form-control" placeholder="100" value="{{$restaurant->discount?$restaurant->discount->min_purchase:'0'}}">
                    </div>
                </div>
                <div class="col-md-4 col-6">
                    <div class="form-group">
                        <label class="form-label font-medium text-capitalize" for="title">{{translate('messages.max')}} {{translate('messages.discount')}} ({{\App\CentralLogics\Helpers::currency_symbol()}})</label>
                        <input type="number" min="0" max="1000000" step="0.01" name="max_discount" class="form-control" value="{{$restaurant->discount?$restaurant->discount->max_discount:'0'}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-6">
                    <div class="form-group">
                        <label class="form-label font-medium text-capitalize" for="title">{{translate('messages.start')}} {{translate('messages.date')}}</label>
                        <input type="date" id="date_from" class="form-control" required name="start_date" value="{{$restaurant->discount?date('Y-m-d',strtotime($restaurant->discount->start_date)):''}}">
                    </div>
                </div>
                <div class="col-md-6 col-6">
                    <div class="form-group">
                        <label class="form-label font-medium text-capitalize" for="title">{{translate('messages.end')}} {{translate('messages.date')}}</label>
                        <input type="date" id="date_to" class="form-control" required name="end_date" value="{{$restaurant->discount?date('Y-m-d', strtotime($restaurant->discount->end_date)):''}}">
                    </div>

                </div>
                <div class="col-md-6 col-6">
                    <div class="form-group">
                        <label class="form-label font-medium text-capitalize" for="title">{{translate('messages.start')}} {{translate('messages.time')}}</label>
                        <input type="time" id="start_time" class="form-control" required name="start_time" value="{{$restaurant->discount?date('H:i',strtotime($restaurant->discount->start_time)):'00:00'}}">
                    </div>
                </div>
                <div class="col-md-6 col-6">
                    <label class="form-label font-medium text-capitalize" for="title">{{translate('messages.end')}} {{translate('messages.time')}}</label>
                    <input type="time" id="end_time" class="form-control" required name="end_time" value="{{$restaurant->discount?date('H:i', strtotime($restaurant->discount->end_time)):'23:59'}}">
                </div>
            </div>
            <div class="form-group text-right mb-0">
                @if($restaurant->discount)
                <button type="reset" class="btn btn--reset mr-2 h--37px">{{translate('messages.reset')}}</button>
                @endif
                <button type="submit" class="btn btn--primary h--37px">{{$restaurant->discount?translate('messages.update'):translate('messages.add')}}</button>
            </div>
        </form>
        <form action="{{route('admin.restaurant.clear-discount',[$restaurant->id])}}" method="post" id="discount-{{$restaurant->id}}">
            @csrf @method('delete')
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@push('script_2')
    <script>
        $(document).on('ready', function () {
            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function () {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
            $('#date_from').attr('min',(new Date()).toISOString().split('T')[0]);
            $('#date_to').attr('min',(new Date()).toISOString().split('T')[0]);

            $("#date_from").on("change", function () {
                $('#date_to').attr('min',$(this).val());
            });

            $("#date_to").on("change", function () {
                $('#date_from').attr('max',$(this).val());
            });
        });

        $('#discount-form').on('submit', function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: "{{route('admin.restaurant.discount',[$restaurant['id']])}}",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    if (data.errors) {
                        for (var i = 0; i < data.errors.length; i++) {
                            toastr.error(data.errors[i].message, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        }
                    } else {
                        toastr.success(data.message, {
                            CloseButton: true,
                            ProgressBar: true
                        });

                        setTimeout(function () {
                            location.href = "{{route('admin.restaurant.view', ['restaurant'=>$restaurant->id, 'tab'=> 'discount'])}}";
                        }, 2000);
                    }
                }
            });
        });
    </script>
@endpush
