@extends('layouts.admin.app')

@section('title',translate('Customer Details'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="d-print-none pb-2">
            <div class="row align-items-center">
                <div class="col-auto mb-2 mb-sm-0">
                    <h1 class="page-header-title">{{translate('messages.customer')}} {{translate('messages.id')}} #{{$customer['id']}}</h1>
                    <span class="d-block">
                        <i class="tio-date-range"></i> {{translate('messages.joined_at')}} : {{date('d M Y '.config('timeformat'),strtotime($customer['created_at']))}}
                    </span>
                </div>

                <div class="col-auto ml-auto">
                    <a class="btn btn-icon btn-sm btn-soft-secondary rounded-circle mr-1"
                       href="{{route('admin.customer.view',[$customer['id']-1])}}"
                       data-toggle="tooltip" data-placement="top" title="{{ translate('Previous customer') }}">
                        <i class="tio-arrow-backward"></i>
                    </a>
                    <a class="btn btn-icon btn-sm btn-soft-secondary rounded-circle"
                       href="{{route('admin.customer.view',[$customer['id']+1])}}" data-toggle="tooltip"
                       data-placement="top" title="{{ translate('Next customer') }}">
                        <i class="tio-arrow-forward"></i>
                    </a>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row mb-2 g-2">
            <!-- Collected Cash Card Example -->
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="resturant-card bg--2">
                    <img class="resturant-icon" src="{{asset('/public/assets/admin/img/dashboard/1.png')}}" alt="dashboard">
                    <div class="for-card-text font-weight-bold  text-uppercase mb-1">{{translate('messages.wallet')}} {{translate('messages.balance')}}</div>
                    <div class="for-card-count">{{$customer->wallet_balance??0}}</div>
                </div>
            </div>

            <!-- Pending Requests Card Example -->
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="resturant-card bg--3">
                    <img class="resturant-icon" src="{{asset('/public/assets/admin/img/dashboard/3.png')}}" alt="dashboard">
                    <div class="for-card-text font-weight-bold  text-uppercase mb-1">{{translate('messages.loyalty_point')}} {{translate('messages.balance')}}</div>
                    <div class="for-card-count">{{$customer->loyalty_point??0}}</div>
                </div>
            </div>
        </div>

        <div class="row" id="printableArea">
            <div class="col-lg-8 mb-3 mb-lg-0">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-header-title">{{ translate('messages.Order List') }} <span class="badge badge-soft-secondary" id="itemCount">{{ $orders->total() }}</span></h5>
                        <div>


                            <form  action="javascript:" id="search-form">
                                @csrf
                                <!-- Search -->
                                <input type="hidden" name="id"   value="{{ $customer->id }}" id="">
                                <div class="input--group input-group input-group-merge input-group-flush">
                                    <input id="datatableSearch_" type="search" name="search" class="form-control" value="{{request()->get('search')}}"
                                            placeholder="{{  translate('Ex: Search Here by ID...') }}" aria-label="Search" required>
                                    <button type="submit" class="btn btn--secondary">
                                        <i class="tio-search"></i>
                                    </button>

                                </div>
                                <!-- End Search -->
                            </form>



{{--
                            <div class="input--group input-group">
                                <input type="text" id="column1_search" class="form-control form-control-sm"
                                            placeholder="{{ }}">
                                <button type="button" class="btn btn--secondary">
                                    <i class="tio-search"></i>
                                </button>
                            </div> --}}
                        </div>
                    </div>
                    <!-- Table -->
                    <div class="table-responsive datatable-custom">
                        <table id="columnSearchDatatable"
                               class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                               data-hs-datatables-options='{
                                 "order": [],
                                 "orderCellsTop": true,
                                 "paging":false
                               }'>
                            <thead class="thead-light">
                                <tr>
                                    <th>{{ translate('messages.sl') }}</th>
                                    <th class="text-center w-50p">{{translate('messages.order')}} {{translate('messages.id')}}</th>
                                    <th class="w-50p text-center">{{translate('messages.total')}} {{translate('messages.amount')}}</th>
                                    <th class="text-center w-100px">{{translate('messages.action')}}</th>
                                </tr>
                            </thead>


                            <tbody id="set-rows">
                                @include('admin-views.customer.partials._list_table')
                            </tbody>

                        </table>
                        @if(count($orders) === 0)
                        <div class="empty--data">
                            <img src="{{asset('/public/assets/admin/img/empty.png')}}" alt="public">
                            <h5>
                                {{translate('no_data_found')}}
                            </h5>
                        </div>
                        @endif
                        <!-- Pagination -->
                        <div class="page-area px-4 pb-3">
                            <div class="d-flex align-items-center justify-content-end">
                                {{-- <div>
                                    1-15 of 380
                                </div> --}}
                                <div class="hide-page">
                                    {!! $orders->links() !!}
                                </div>
                            </div>
                        </div>
                        <!-- Pagination -->
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Card -->
                <div class="card">
                    <!-- Header -->
                    <div class="card-header">
                        <h4 class="card-header-title">
                            <span class="card-header-icon">
                                <i class="tio-user"></i>
                            </span>
                            <span>
                                @if($customer)
                                    {{$customer['f_name'].' '.$customer['l_name']}}
                                    @else
                                    {{ translate('messages.Customer') }}
                                @endif
                            </span>
                        </h4>
                    </div>
                    <!-- End Header -->

                    <!-- Body -->
                    @if($customer)
                        <div class="card-body">
                            <div class="media align-items-center customer--information-single" href="javascript:">
                                <div class="avatar avatar-circle">
                                    <img
                                        class="avatar-img"
                                        onerror="this.src='{{asset('public/assets/admin/img/160x160/img1.jpg')}}'"
                                        src="{{asset('storage/app/public/profile/'.$customer->image)}}"
                                        alt="Image Description">
                                </div>
                                <div class="media-body">
                                    <ul class="list-unstyled m-0">
                                        <li class="pb-1">
                                            <i class="tio-email mr-2"></i>
                                            {{$customer['email']}}
                                        </li>
                                        <li class="pb-1">
                                            <i class="tio-call-talking-quiet mr-2"></i>
                                            {{$customer['phone']}}
                                        </li>
                                        <li class="pb-1">
                                            <i class="tio-shopping-basket-outlined mr-2"></i>
                                            {{$customer->order_count}} {{translate('messages.orders')}}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5>{{translate('messages.contact')}} {{translate('messages.info')}}</h5>
                            </div>
                            @foreach($customer->addresses as $address)
                                <ul class="list-unstyled list-unstyled-py-2">
                                    @if($address['contact_person_umber'])
                                        <li>
                                            <i class="tio-call-talking-quiet mr-2"></i>
                                            {{$address['contact_person_umber']}}
                                        </li>
                                    @endif
                                    <li class="quick--address-bar">
                                        <div class="quick-icon badge-soft-secondary">
                                            <i class="tio-home"></i>
                                        </div>
                                        <div class="info">
                                            <h6>{{$address['address_type']}}</h6>
                                            <a target="_blank" href="http://maps.google.com/maps?z=12&t=m&q=loc:{{$address['latitude']}}+{{$address['longitude']}}" class="text--title">
                                                {{$address['address']}}
                                            </a>
                                        </div>
                                    </li>
                                </ul>
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
@endsection

@push('script_2')

    <script>
        $(document).on('ready', function () {
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

    </script>

<script>
    $('#search-form').on('submit', function () {
        var formData = new FormData(this);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.post({
            url: '{{route('admin.customer.order_search')}}',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $('#loading').show();
            },
            success: function (data) {
                $('#set-rows').html(data.view);
                $('.hide-page').hide();
                $('#itemCount').html(data.total);
            },
            complete: function () {
                $('#loading').hide();
            },
        });
    });
</script>
@endpush
