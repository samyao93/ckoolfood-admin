@extends('layouts.admin.app')

@section('title',translate('Restaurant List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')

    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title"><i class="tio-filter-list"></i> {{translate('messages.restaurants')}} <span class="badge badge-soft-dark ml-2" id="itemCount">{{$restaurants->total()}}</span></h1>
        </div>
        <!-- End Page Header -->


        <!-- Filters -->
        <div class="card shadow--card p-0 mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-sm-6 col-md-3">
                        <div class="select-item">
                        <!-- Veg/NonVeg filter -->
                        <select name="type"
                        onchange="set_filter('{{url()->full()}}',this.value, 'type')"
                        data-placeholder="{{translate('messages.all')}}" class="form-control js-select2-custom">
                            <option value="all" {{$type=='all'?'selected':''}}>{{translate('messages.all')}}</option>
                            @if ($toggle_veg_non_veg)
                            <option value="veg" {{$type=='veg'?'selected':''}}>{{translate('messages.veg')}}</option>
                            <option value="non_veg" {{$type=='non_veg'?'selected':''}}>{{translate('messages.non_veg')}}</option>
                            @endif
                        </select>
                        <!-- End Veg/NonVeg filter -->
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="select-item">
                        <!-- Veg/NonVeg filter -->
                        <select name="restaurant_model"
                        onchange="set_filter('{{url()->full()}}',this.value, 'restaurant_model')"
                        data-placeholder="{{translate('messages.all')}}" class="form-control js-select2-custom">
                            <option selected disabled>{{translate('messages.Business_model')}}</option>
                            <option value="all" {{$typ=='all'?'selected':''}}>{{translate('messages.all')}}</option>
                            <option value="commission" {{$typ=='commission'?'selected':''}}>{{translate('messages.Commission')}}</option>
                            <option value="subscribed" {{$typ=='subscribed'?'selected':''}}>{{translate('messages.Subscribed')}}</option>
                            <option value="unsubscribed" {{$typ=='unsubscribed'?'selected':''}}>{{translate('messages.Unsubscribed')}}</option>

                        </select>

                    <!-- End Veg/NonVeg filter -->
                    </div>
                </div>

                <div class="col-sm-6 col-md-3">
                    <div class="select-item">
                        <select name="cuisine_id" id="cuisine"
                        onchange="set_filter('{{url()->full()}}',this.value,'cuisine_id')"
                        data-placeholder="{{ translate('messages.select') }} {{ translate('messages.Cuisine') }}"
                        class="form-control h--45px js-select2-custom">
                        <option value="all" selected >{{ translate('messages.select') }} {{ translate('messages.Cuisine') }}</option>
                        @foreach (\App\Models\Cuisine::orderBy('name')->get(['id','name']) as $cu)
                            <option value="{{ $cu['id'] }}"
                                {{ $cuisine_id ==  $cu['id']? 'selected' : '' }}>
                                {{ $cu['name'] }}</option>
                        @endforeach
                    </select>
                    </div>
                </div>
                @if(!isset(auth('admin')->user()->zone_id))
                    <div class="col-sm-6 col-md-3">
                        <div class="select-item">
                            <select name="zone_id" class="form-control js-select2-custom"
                                    onchange="set_zone_filter('{{url()->full()}}',this.value)">
                                <option selected disabled>{{translate('messages.select_zone')}}</option>
                                <option value="all">{{translate('messages.all_zones')}}</option>
                                @foreach(\App\Models\Zone::orderBy('name')->get() as $z)
                                    <option
                                        value="{{$z['id']}}" {{isset($zone) && $zone->id == $z['id']?'selected':''}}>
                                        {{$z['name']}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endif
                    {{-- <div class="col-sm-6 col-md-3 text-right ml-auto">
                        <button type="submit" class="btn btn--primary">
                            {{translate('messages.filter')}}
                        </button>
                    </div> --}}
                </div>
            </div>
        </div>
        <!-- Filters -->


        <!-- Resturent Card Wrapper -->
        <div class="row g-3 mb-3">
            <div class="col-xl-3 col-sm-6">
                <div class="resturant-card bg--1">
                    {{-- @php($total_retaurants = \App\Models\Restaurant::count())
                    @php($total_retaurants = isset($total_retaurants) ? $total_retaurants : 0) --}}
                    <h4 class="title" id="itemCount" >{{$restaurants->total()}}</h4>
                    <span class="subtitle">{{translate('messages.total_restaurants')}}</span>
                    <img class="resturant-icon" src="{{asset('/public/assets/admin/img/resturant/map-pin.png')}}" alt="resturant">
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">

                <div class="resturant-card bg--2">
                    @php($active_restaurants = \App\Models\Restaurant::where(['status'=>1])
                    ->when( isset($zone) && ($zone->id), function ($query) use ($zone) {
                                    return $query->where('zone_id', $zone->id);
                                    })
                    ->type($type)->RestaurantModel($typ)
                    ->count())
                    @php($active_restaurants = isset($active_restaurants) ? $active_restaurants : 0)
                    <h4 class="title">{{$active_restaurants}}</h4>
                    <span class="subtitle">{{translate('messages.active_restaurants')}}</span>
                    <img class="resturant-icon" src="{{asset('/public/assets/admin/img/resturant/active-rest.png')}}" alt="resturant">
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="resturant-card bg--3">
                    @php($inactive_restaurants = \App\Models\Restaurant::where(['status'=>0])
                    ->when( isset($zone) && ($zone->id), function ($query) use ($zone) {
                                    return $query->where('zone_id', $zone->id);
                                    })
                    ->type($type)->RestaurantModel($typ)
                    ->count())
                    @php($inactive_restaurants = isset($inactive_restaurants) ? $inactive_restaurants : 0)
                    <h4 class="title">{{$inactive_restaurants}}</h4>
                    <span class="subtitle">{{translate('messages.inactive_restaurants')}}</span>
                    <img class="resturant-icon" src="{{asset('/public/assets/admin/img/resturant/inactive-rest.png')}}" alt="resturant">
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="resturant-card bg--4">
                    @php($data = \App\Models\Restaurant::where('created_at', '<=', now()->subDays(30)->toDateTimeString())
                    ->when( isset($zone) && ($zone->id), function ($query) use ($zone) {
                                    return $query->where('zone_id', $zone->id);
                                    })
                    ->type($type)->RestaurantModel($typ)
                    ->count())
                    <h4 class="title">{{$data}}</h4>
                    <span class="subtitle">{{translate('messages.newly_joined_restaurants')}}</span>
                    <img class="resturant-icon" src="{{asset('/public/assets/admin/img/resturant/new-rest.png')}}" alt="resturant">
                </div>
            </div>
        </div>
        <!-- Resturent Card Wrapper -->
        <!-- Transaction Information -->
        <ul class="transaction--information text-uppercase">
            <li class="text--info">
                <i class="tio-document-text-outlined"></i>
                <div>
                    @php($total_transaction = \App\Models\OrderTransaction::count())
                    @php($total_transaction = isset($total_transaction) ? $total_transaction : 0)
                    <span>{{translate('messages.total_transactions')}}</span> <strong>{{$total_transaction}}</strong>
                </div>
            </li>
            <li class="seperator"></li>
            <li class="text--success">
                <i class="tio-checkmark-circle-outlined success--icon"></i>
                <div>
                    @php($comission_earned = \App\Models\AdminWallet::sum('total_commission_earning'))
                    @php($comission_earned = isset($comission_earned) ? $comission_earned : 0)
                    <span>{{translate('messages.commission_earned')}}</span> <strong>{{\App\CentralLogics\Helpers::format_currency($comission_earned)}}</strong>
                </div>
            </li>
            <li class="seperator"></li>
            <li class="text--danger">
                <i class="tio-atm"></i>
                <div>
                    @php($restaurant_withdraws = \App\Models\WithdrawRequest::where(['approved'=>1])->sum('amount'))
                    @php($restaurant_withdraws = isset($restaurant_withdraws) ? $restaurant_withdraws : 0)
                    <span>{{translate('messages.total_restaurant_withdraws')}}</span> <strong>{{\App\CentralLogics\Helpers::format_currency($restaurant_withdraws)}}</strong>
                </div>
            </li>
        </ul>
        <!-- Transaction Information -->
        <!-- Resturent List -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <!-- Card -->
                <div class="card">
                    <!-- Card Header -->

                    <div class="card-header py-2 border-0">
                        <div class="search--button-wrapper">
                            <h3 class="card-title">{{translate('messages.restaurants')}} {{translate('messages.list')}}</h3>
                            <form action="javascript:" id="search-form" class="my-2 ml-auto mr-sm-2 mr-xl-4 ml-sm-auto flex-grow-1 flex-grow-sm-0">
                                <!-- Search -->
                                @csrf
                                <div class="input--group input-group input-group-merge input-group-flush">
                                    <input id="datatableSearch_" type="search" name="search" class="form-control"
                                            placeholder="{{ translate('Ex : search by Restaurant name of Phone number') }}" aria-label="{{translate('messages.search')}}" required>
                                    <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>

                                </div>
                                <!-- End Search -->
                            </form>

                            <!-- Export Button Static -->
                            <div class="hs-unfold ml-3">
                                <a class="js-hs-unfold-invoker btn btn-sm btn-white dropdown-toggle btn export-btn btn-outline-primary btn--primary font--sm" href="javascript:;"
                                    data-hs-unfold-options='{
                                        "target": "#usersExportDropdown",
                                        "type": "css-animation"
                                    }'>
                                    <i class="tio-download-to mr-1"></i> {{translate('messages.export')}}
                                </a>

                                <div id="usersExportDropdown"
                                        class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-sm-right">

                                    <span class="dropdown-header">{{translate('messages.download')}} {{translate('messages.options')}}</span>
                                    <a target="__blank" id="export-excel" class="dropdown-item" href="{{route('admin.restaurant.restaurants-export', ['type'=>'excel',
                    'zone_id'=>request()->zone_id,  'restaurant_model'=>request()->restaurant_model, 'ty'=>request()->type


                                    ])}}">
                                        <img class="avatar avatar-xss avatar-4by3 mr-2"
                                        src="{{asset('public/assets/admin')}}/svg/components/excel.svg"
                                        alt="Image Description">
                                        {{translate('messages.excel')}}
                                    </a>

                                    <a id="export-excel" class="dropdown-item" href="{{route('admin.restaurant.restaurants-export', ['type'=>'csv',
                                     'zone_id'=>request()->zone_id,  'restaurant_model'=>request()->restaurant_model, 'ty'=>request()->type
                                    ])}}">
                                        <img class="avatar avatar-xss avatar-4by3 mr-2"
                                                    src="{{asset('public/assets/admin')}}/svg/components/placeholder-csv-format.svg"
                                                    alt="Image Description">
                                                    {{translate('messages.csv')}}
                                    </a>
                                </div>
                            </div>
                            <!-- Export Button Static -->
                        </div>
                    </div>
                    <!-- Card Header -->

                    <!-- Table -->
                    <div class="table-responsive datatable-custom resturant-list-table">
                        <table id="columnSearchDatatable"
                               class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                               data-hs-datatables-options='{
                                 "order": [],
                                 "orderCellsTop": true,
                                 "paging":false

                               }'>
                            <thead class="thead-light">
                            <tr>
                                <th class="text-uppercase w-90px">{{translate('messages.sl')}}</th>
                                <th class="initial-58">{{translate('messages.restaurant')}} {{translate('messages.info')}}</th>
                                <th class="w-230px text-center">{{translate('messages.owner')}} {{translate('messages.info')}} </th>
                                <th class="w-130px">{{translate('messages.zone')}}</th>
                                <th class="w-130px">{{translate('messages.cuisine')}}</th>
                                <th class="w-100px">{{translate('messages.status')}}</th>
                                <th class="text-center w-60px">{{translate('messages.action')}}</th>
                            </tr>
                            </thead>

                            <tbody id="set-rows">
                            @foreach($restaurants as $key=>$dm)
                                <tr>
                                    <td>{{$key+$restaurants->firstItem()}}</td>
                                    <td>
                                        <a href="{{route('admin.restaurant.view', $dm->id)}}" alt="view restaurant" class="table-rest-info">
                                        <img
                                                onerror="this.src='{{asset('public/assets/admin/img/100x100/food-default-image.png')}}'"
                                                src="{{asset('storage/app/public/restaurant')}}/{{$dm['logo']}}">
                                            <div class="info">
                                                <span class="d-block text-body">
                                                    {{Str::limit($dm->name,20,'...')}}<br>
                                                    <!-- Rating -->
                                                    <span class="rating">
                                                        @if ($dm->reviews_count)
                                                        @php($reviews_count = $dm->reviews_count)
                                                        @php($reviews = 1)
                                                        @else
                                                        @php($reviews = 0)
                                                        @php($reviews_count = 1)
                                                        @endif
                                                    <i class="tio-star"></i> {{ round($dm->reviews_sum_rating /$reviews_count,1) }}
                                                </span>
                                                    <!-- Rating -->
                                                </span>
                                            </div>
                                        </a>
                                    </td>
                                    <td>
                                        <span class="d-block owner--name text-center">
                                            {{$dm->vendor->f_name.' '.$dm->vendor->l_name}}
                                        </span>
                                        <span class="d-block font-size-sm text-center">
                                            {{$dm['phone']}}
                                        </span>
                                    </td>
                                    <td>
                                        {{$dm->zone?$dm->zone->name:translate('messages.zone').' '.translate('messages.deleted')}}
                                        {{--<span class="d-block font-size-sm">{{$banner['image']}}</span>--}}
                                    </td>
                                    <td>
                                        <div class="white-space-initial">
                                            @if ($dm->cuisine)
                                            @forelse($dm->cuisine as $c)
                                                {{$c->name.','}}
                                                @empty
                                                {{ translate('Cuisine_not_found') }}
                                            @endforelse
                                            @endif
                                        </div>
                                </td>
                                    <td>
                                        @if(isset($dm->vendor->status))
                                            @if($dm->vendor->status)
                                            <label class="toggle-switch toggle-switch-sm" for="stocksCheckbox{{$dm->id}}">
                                                <input type="checkbox" onclick="status_change_alert('{{route('admin.restaurant.status',[$dm->id,$dm->status?0:1])}}', '{{translate('messages.you_want_to_change_this_restaurant_status')}}', event)" class="toggle-switch-input" id="stocksCheckbox{{$dm->id}}" {{$dm->status?'checked':''}}>
                                                <span class="toggle-switch-label">
                                                    <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                            @else
                                            <span class="badge badge-soft-danger">{{translate('messages.denied')}}</span>
                                            @endif
                                        @else
                                            <span class="badge badge-soft-danger">{{translate('messages.not_approved')}}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn--container justify-content-center">
                                            <a class="btn btn-sm btn--primary btn-outline-primary action-btn"
                                                href="{{route('admin.restaurant.edit',[$dm['id']])}}" title="{{translate('messages.edit')}} {{translate('messages.restaurant')}}"><i class="tio-edit"></i>
                                            </a>
                                            <a class="btn btn-sm btn--warning btn-outline-warning action-btn"
                                                href="{{route('admin.restaurant.view',[$dm['id']])}}" title="{{translate('messages.view')}} {{translate('messages.restaurant')}}"><i class="tio-invisible"></i>
                                            </a>
                                        </div>
                                        {{--<a class="btn btn-sm btn-white" href="javascript:"
                                        onclick="form_alert('vendor-{{$dm['id']}}','Want to remove this information ?')" title="{{translate('messages.delete')}} {{translate('messages.restaurant')}}"><i class="tio-delete-outlined text-danger"></i>
                                        </a>
                                        <form action="{{route('admin.vendor.delete',[$dm['id']])}}" method="post" id="vendor-{{$dm['id']}}">
                                            @csrf @method('delete')
                                        </form>--}}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        @if(count($restaurants) === 0)
                        <div class="empty--data">
                            <img src="{{asset('/public/assets/admin/img/empty.png')}}" alt="public">
                            <h5>
                                {{translate('no_data_found')}}
                            </h5>
                        </div>
                        @endif
                        <div class="page-area px-4 pb-3">
                            <div class="d-flex align-items-center justify-content-end">
                                <div>
                                    {!! $restaurants->appends(request()->all())->links() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Table -->
                </div>
                <!-- End Card -->
            </div>
        </div>
        <!-- Resturent List -->
    </div>

@endsection

@push('script_2')
    <script>
        function status_change_alert(url, message, e) {
            e.preventDefault();
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
                    location.href=url;
                }
            })
        }
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

            $('#column2_search').on('keyup', function () {
                datatable
                    .columns(2)
                    .search(this.value)
                    .draw();
            });

            $('#column3_search').on('keyup', function () {
                datatable
                    .columns(3)
                    .search(this.value)
                    .draw();
            });

            $('#column4_search').on('keyup', function () {
                datatable
                    .columns(4)
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
                url: '{{route('admin.restaurant.search')}}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    $('#set-rows').html(data.view);
                    $('#itemCount').html(data.total);
                    $('.page-area').hide();
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        });
    </script>
@endpush
