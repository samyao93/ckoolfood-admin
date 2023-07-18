@extends('layouts.admin.app')

@section('title',translate('messages.Subscription_report'))

@push('css_or_js')

@endpush

@section('content')

    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <i class="tio-filter-list"></i>
                    {{-- <img src="{{asset('public/assets/admin/img/report.png')}}" class="w--22" alt=""> --}}
                </span>
                <span>
                    {{translate('messages.Subscription_report')}}
                    @if ($from && $to)
                    <span class="h6 mb-0 badge badge-soft-success ml-2">
                    ( {{$from}} - {{ $to}} )</span>
                    @endif
                </span>
            </h1>
        </div>
        <!-- End Page Header -->
            <div class="card mb-20">
                <div class="card-body">
                    <h4 class="">{{ translate('Search Data') }}</h4>
                    <form  method="get">

                        <div class="row g-3">
                            <div class="col-sm-6 col-md-4">
                                <select name="restaurant_id" onchange="set_restaurant_filter('{{ url()->full() }}',this.value)"
                                    data-placeholder="{{ translate('messages.select') }} {{ translate('messages.restaurant') }}"
                                    class="js-select2-custom form-control">

                                    <option value="all">{{ translate('messages.all') }} {{ translate('messages.restaurants') }}</option>
                                    @foreach (\App\Models\Restaurant::orderBy('name')->get(['id','name']) as $rest)
                                        <option value="{{ $rest['id'] }}"
                                            {{ isset($restaurant) && $restaurant->id  == $rest['id'] ? 'selected' : '' }}>
                                            {{ $rest['name'] }}
                                        </option>
                                    @endforeach
                                    {{-- @if (isset($restaurant))
                                        <option value="{{ $restaurant->id }}" selected>{{ $restaurant->name }}</option>
                                    @endif --}}
                                </select>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <select  onchange="set_filter('{{ url()->full() }}',this.value, 'payment_type')" name="payment_type"
                                    data-placeholder="{{ translate('messages.payment_type') }}"
                                    class="js-select2-custom form-control">
                                        <option value="all" selected>{{ translate('messages.all') }}</option>
                                        <option value="wallet_payment" {{ isset($payment_type) && $payment_type == 'wallet_payment' ? 'selected' : '' }}  >{{ translate('messages.wallet_payment') }}</option>
                                        <option value="manual_payment" {{ isset($payment_type) && $payment_type == 'manual_payment' ? 'selected' : '' }} >{{ translate('messages.manual_payment') }}</option>
                                        {{-- <option value="digital_payment" {{ isset($payment_type) && $payment_type == 'digital_payment' ? 'selected' : '' }} >{{ translate('messages.digital_payment') }}</option> --}}
                                        <option value="free_trial" {{ isset($payment_type) && $payment_type == 'free_trial' ? 'selected' : '' }} >{{ translate('messages.free_trial') }}</option>
                                </select>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <select class="form-control" name="filter"
                                    onchange="set_time_filter('{{ url()->full() }}',this.value)">
                                    <option value="all_time" {{ isset($filter) && $filter == 'all_time' ? 'selected' : '' }}>
                                        {{ translate('messages.All Time') }}</option>
                                    <option value="this_year" {{ isset($filter) && $filter == 'this_year' ? 'selected' : '' }}>
                                        {{ translate('messages.This Year') }}</option>
                                    <option value="previous_year"
                                        {{ isset($filter) && $filter == 'previous_year' ? 'selected' : '' }}>
                                        {{ translate('messages.Previous Year') }}</option>
                                    <option value="this_month"
                                        {{ isset($filter) && $filter == 'this_month' ? 'selected' : '' }}>
                                        {{ translate('messages.This Month') }}</option>
                                    <option value="this_week" {{ isset($filter) && $filter == 'this_week' ? 'selected' : '' }}>
                                        {{ translate('messages.This Week') }}</option>
                                    <option value="custom" {{ isset($filter) && $filter == 'custom' ? 'selected' : '' }}>
                                        {{ translate('messages.Custom') }}</option>
                                </select>
                            </div>
                            @if (isset($filter) && $filter == 'custom')
                                <div class="col-sm-6 col-md-3">
                                    <input type="date" name="from" id="from_date" class="form-control"
                                        placeholder="{{ translate('Start Date') }}"
                                        value={{ $from ? $from  : '' }} required>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <input type="date" name="to" id="to_date" class="form-control"
                                        placeholder="{{ translate('End Date') }}"
                                        value={{ $to ? $to  : '' }} required>
                                </div>
                            @endif
                            <div class="col-sm-6 col-md-3 ml-auto">
                                <button type="submit"
                                    class="btn btn-primary btn-block">{{ translate('Filter') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>




        <!-- End Stats -->
        <!-- Card -->
        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header py-2 border-0">
                        <div class="search--button-wrapper">
                            <h5 class="card-title">
                                <span>
                                    {{ translate('messages.Subscription_report')}}
                                </span>
                                <span class="badge badge-soft-secondary" id="countItems">
                                    ({{ $subscriptions->total() }})
                                </span>
                            </h5>
                            <form  class="search-form">

                                <!-- Search -->
                                <div class="input-group input--group">
                                    <input id="datatableSearch" name="search" type="search" class="form-control h--40px" placeholder="{{translate('Search by ID or Restaurant Name or Email')}}"  value="{{ request()->search ?? null }}" aria-label="{{translate('messages.search_here')}}">
                                    <button type="submit" class="btn btn--secondary h--40px"><i class="tio-search"></i></button>
                                </div>
                                <!-- End Search -->
                            </form>

                            <!-- Unfold -->
                            <div class="hs-unfold mr-2">
                                <a class="js-hs-unfold-invoker btn btn-sm btn-white dropdown-toggle min-height-40" href="javascript:;"
                                    data-hs-unfold-options='{
                                            "target": "#usersExportDropdown",
                                            "type": "css-animation"
                                        }'>
                                    <i class="tio-download-to mr-1"></i> {{ translate('messages.export') }}
                                </a>

                                <div id="usersExportDropdown"
                                    class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-sm-right">
                                    <span class="dropdown-header">{{ translate('messages.download') }}
                                        {{ translate('messages.options') }}</span>
                                    <a id="export-excel" class="dropdown-item" href="{{route('admin.report.subscription-export', ['type'=>'excel',
                                        'payment_type'=>$payment_type,  'restaurant_id'=>$restaurant->id ?? null ,'filter'=>$filter,
                                        'from'=>$from, 'to'=>$to,
                                    ])}}">
                                        <img class="avatar avatar-xss avatar-4by3 mr-2"
                                            src="{{ asset('public/assets/admin') }}/svg/components/excel.svg"
                                            alt="Image Description">
                                        {{ translate('messages.excel') }}
                                    </a>
                                    <a id="export-csv" class="dropdown-item" href="{{route('admin.report.subscription-export', ['type'=>'csv',
                                        'payment_type'=>$payment_type,  'restaurant_id'=>$restaurant->id ?? null ,'filter'=>$filter,
                                        'from'=>$from, 'to'=>$to,
                                    ])}}">
                                        <img class="avatar avatar-xss avatar-4by3 mr-2"
                                            src="{{ asset('public/assets/admin') }}/svg/components/placeholder-csv-format.svg"
                                            alt="Image Description">
                                        .{{ translate('messages.csv') }}
                                    </a>
                                </div>
                            </div>
                            <!-- End Unfold -->
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table id="datatable"
                                class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="w-10px" >{{translate('sl')}}</th>
                                        <th class="w-90px">{{ translate('messages.transaction') }}
                                            {{ translate('messages.id') }}</th>
                                        <th class="w-130px">{{ translate('Transaction Date') }}</th>
                                        <th class="w-130px">{{ translate('messages.restaurant_name') }}</th>
                                        <th class="w-130px">{{ translate('messages.Package_name') }}</th>
                                        <th class="w-130px">{{ translate('messages.Duration') }}</th>
                                        <th class="w-130px">{{ translate('messages.Pricing') }}</th>
                                        <th class="w-130px">{{ translate('messages.Payment Status') }}</th>
                                        <th class="w-130px">{{ translate('messages.Payment_type') }}</th>
                                        {{-- <th class="w-130px">{{ translate('messages.Payment Status') }}</th> --}}
                                        <th class="text-center w-60px">{{ translate('messages.action') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="set-rows">

                                @include('admin-views.report.partials._subscription_table' ,['subscriptions' =>$subscriptions])

                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if(count($subscriptions) !== 0)
                    <hr>
                    @endif
                    <div class="page-area px-4 pb-3">
                        {!! $subscriptions->links() !!}
                    </div>
                    @if(count($subscriptions) === 0)
                    <div class="empty--data">
                        <img src="{{asset('/public/assets/admin/svg/illustrations/sorry.svg')}}" alt="public">
                        <h5>
                            {{translate('no_data_found')}}
                        </h5>
                    </div>
                    @endif
                </div>
            </div>
         </div>
        <!-- End Card -->
    </div>
@endsection

@push('script_2')

    {{-- <script>
        $(document).on('ready', function () {
            $('.js-data-example-ajax').select2({
        ajax: {
            url: '{{url('/')}}/admin/restaurant/get-restaurants',
            data: function (params) {
                return {
                    q: params.term, // search term
                    page: params.page
                };
            },
            processResults: function (data) {
                return {
                results: data
                };
            },
            __port: function (params, success, failure) {
                var $request = $.ajax(params);

                $request.then(success);
                $request.fail(failure);

                return $request;
            }
        }
    });

        });
    </script> --}}

    <script>
        $('#from_date,#to_date').change(function () {
            let fr = $('#from_date').val();
            let to = $('#to_date').val();
            if (fr != '' && to != '') {
                if (fr > to) {
                    $('#from_date').val('');
                    $('#to_date').val('');
                    toastr.error('Invalid date range!', Error, {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            }
        })

    </script>
@endpush
