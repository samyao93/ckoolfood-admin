@extends('layouts.admin.app')

@section('title', translate('Food List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-md-auto mb-md-0 mb-3 mr-auto">
                    <h1 class="page-header-title"> {{ translate('messages.food') }} {{ translate('messages.list') }}<span
                            class="badge badge-soft-dark ml-2" id="foodCount">{{ $foods->total() }}</span></h1>
                </div>
                @if ($toggle_veg_non_veg)
                    <!-- Veg/NonVeg filter -->
                    <div class="col-md-auto mb-3 mb-md-0">
                        <select name="category_id" onchange="set_filter('{{ url()->full() }}',this.value, 'type')"
                            data-placeholder="{{ translate('messages.all') }}" class="form-control">
                            <option value="all" {{ $type == 'all' ? 'selected' : '' }}>{{ translate('messages.all') }}</option>
                            <option value="veg" {{ $type == 'veg' ? 'selected' : '' }}>{{ translate('messages.veg') }}</option>
                            <option value="non_veg" {{ $type == 'non_veg' ? 'selected' : '' }}>{{ translate('messages.non_veg') }}
                            </option>
                        </select>
                    </div>
                    <!-- End Veg/NonVeg filter -->
                @endif
                <div class="col-md-auto mb-3 mb-md-0 min-240">
                    <select name="restaurant_id" id="restaurant"
                        onchange="set_restaurant_filter('{{ url()->full() }}',this.value)"
                        data-placeholder="{{ translate('messages.select') }} {{ translate('messages.restaurant') }}"
                        class="js-data-example-ajax form-control"
                        onchange="getRestaurantData('{{ url('/') }}/admin/restaurant/get-addons?data[]=0&restaurant_id=',this.value,'add_on')"
                        required title="Select Restaurant"
                        oninvalid="this.setCustomValidity('{{ translate('messages.please_select_restaurant') }}')">
                        @if ($restaurant)
                            <option value="{{ $restaurant->id }}" selected>{{ $restaurant->name }}</option>
                        @else
                            <option value="all" selected>{{ translate('messages.all_restaurants') }}</option>
                        @endif
                    </select>
                </div>
                <div class="col-md-auto mb-3 mb-md-0 min-240">
                    <!-- Unfold -->
                    <div class="hs-unfold w-100">
                        <select name="category_id" id="category"
                            onchange="set_filter('{{ url()->full() }}',this.value, 'category_id')"
                            data-placeholder="{{ translate('messages.select_category') }}"
                            class="js-data-example-ajax form-control">
                            @if ($category)
                                <option value="{{ $category->id }}" selected>{{ $category->name }}
                                    ({{ $category->position == 0 ? translate('messages.main') : translate('messages.sub') }})
                                </option>
                            @else
                                <option value="all" selected>{{ translate('messages.all_categories') }}</option>
                            @endif
                        </select>
                    </div>
                </div>
                <!-- End Unfold -->
            </div>

        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <!-- Card -->
                <div class="card">
                    <!-- Header -->
                    <div class="card-header border-0 py-2">
                        <div class="search--button-wrapper">
                            <h5 class="card-title d-none d-xl-block"></h5>
                            <form id="search-form">
                                @csrf
                                <!-- Search -->
                                <div class="input--group input-group input-group-merge input-group-flush">
                                    <input id="datatableSearch" name="search" type="search" class="form-control"
                                        placeholder="{{translate('Search_by_name')}}"
                                        aria-label="{{ translate('messages.search_here') }}">
                                    <button type="submit" class="btn btn--secondary">
                                        <i class="tio-search"></i>
                                    </button>
                                </div>
                                <!-- End Search -->
                            </form>
                            <!-- Unfold -->
                            <div class="hs-unfold m-2 ml-lg-3">
                                <a class="js-hs-unfold-invoker btn btn-white" href="javascript:;"
                                    data-hs-unfold-options='{
                                        "target": "#showHideDropdown",
                                        "type": "css-animation"
                                        }'>
                                    <i class="tio-table mr-1"></i> {{ translate('messages.columns') }}
                                </a>

                                <div id="showHideDropdown"
                                    class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-right dropdown-card">
                                    <div class="card card-sm">
                                        <div class="card-body">
                                            {{--<div class="d-flex justify-content-between align-items-center mb-3">
                                                <span class="mr-2">#</span>
                                                <!-- Checkbox Switch -->
                                                <label class="toggle-switch toggle-switch-sm" for="toggleColumn_index">
                                                    <input type="checkbox" class="toggle-switch-input"
                                                        id="toggleColumn_index" checked>
                                                    <span class="toggle-switch-label">
                                                        <span class="toggle-switch-indicator"></span>
                                                    </span>
                                                </label>
                                                <!-- End Checkbox Switch -->
                                            </div>--}}
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <span class="mr-2">{{ translate('messages.name') }}</span>
                                                <!-- Checkbox Switch -->
                                                <label class="toggle-switch toggle-switch-sm" for="toggleColumn_name">
                                                    <input type="checkbox" class="toggle-switch-input"
                                                        id="toggleColumn_name" checked>
                                                    <span class="toggle-switch-label">
                                                        <span class="toggle-switch-indicator"></span>
                                                    </span>
                                                </label>
                                                <!-- End Checkbox Switch -->
                                            </div>

                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <span class="mr-2">{{ translate('messages.category') }}</span>

                                                <!-- Checkbox Switch -->
                                                <label class="toggle-switch toggle-switch-sm" for="toggleColumn_type">
                                                    <input type="checkbox" class="toggle-switch-input"
                                                        id="toggleColumn_type" checked>
                                                    <span class="toggle-switch-label">
                                                        <span class="toggle-switch-indicator"></span>
                                                    </span>
                                                </label>
                                                <!-- End Checkbox Switch -->
                                            </div>

                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <span class="mr-2">{{ translate('messages.restaurant') }}</span>

                                                <!-- Checkbox Switch -->
                                                <label class="toggle-switch toggle-switch-sm" for="toggleColumn_vendor">
                                                    <input type="checkbox" class="toggle-switch-input"
                                                        id="toggleColumn_vendor" checked>
                                                    <span class="toggle-switch-label">
                                                        <span class="toggle-switch-indicator"></span>
                                                    </span>
                                                </label>
                                                <!-- End Checkbox Switch -->
                                            </div>


                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <span class="mr-2">{{ translate('messages.status') }}</span>

                                                <!-- Checkbox Switch -->
                                                <label class="toggle-switch toggle-switch-sm" for="toggleColumn_status">
                                                    <input type="checkbox" class="toggle-switch-input"
                                                        id="toggleColumn_status" checked>
                                                    <span class="toggle-switch-label">
                                                        <span class="toggle-switch-indicator"></span>
                                                    </span>
                                                </label>
                                                <!-- End Checkbox Switch -->
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <span class="mr-2">{{ translate('messages.price') }}</span>

                                                <!-- Checkbox Switch -->
                                                <label class="toggle-switch toggle-switch-sm" for="toggleColumn_price">
                                                    <input type="checkbox" class="toggle-switch-input"
                                                        id="toggleColumn_price" checked>
                                                    <span class="toggle-switch-label">
                                                        <span class="toggle-switch-indicator"></span>
                                                    </span>
                                                </label>
                                                <!-- End Checkbox Switch -->
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <span class="mr-2">{{ translate('messages.action') }}</span>

                                                <!-- Checkbox Switch -->
                                                <label class="toggle-switch toggle-switch-sm" for="toggleColumn_action">
                                                    <input type="checkbox" class="toggle-switch-input"
                                                        id="toggleColumn_action" checked>
                                                    <span class="toggle-switch-label">
                                                        <span class="toggle-switch-indicator"></span>
                                                    </span>
                                                </label>
                                                <!-- End Checkbox Switch -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Unfold -->
                        </div>
                        <!-- End Row -->
                    </div>
                    <!-- End Header -->

                    <!-- Table -->
                    <div class="table-responsive datatable-custom" id="table-div">
                        <table id="datatable"
                            class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                            data-hs-datatables-options='{
                                    "columnDefs": [{
                                        "targets": [],
                                        "width": "5%",
                                        "orderable": false
                                    }],
                                    "order": [],
                                    "info": {
                                    "totalQty": "#datatableWithPaginationInfoTotalQty"
                                    },

                                    "entries": "#datatableEntries",

                                    "isResponsive": false,
                                    "isShowPaging": false,
                                    "paging":false
                                }'>
                            <thead class="thead-light">
                                <tr>
                                    <th class="w-60px">{{ translate('messages.sl') }}</th>
                                    <th class="w-100px">{{ translate('messages.name') }}</th>
                                    <th class="w-120px">{{ translate('messages.category') }}</th>
                                    <th class="w-120px">{{ translate('messages.restaurant') }}</th>
                                    <th class="w-100px">{{ translate('messages.price') }}</th>
                                    <th class="w-100px">{{ translate('messages.status') }}</th>
                                    <th class="w-120px text-center">
                                        {{ translate('messages.action') }}
                                    </th>
                                </tr>
                            </thead>

                            <tbody id="set-rows">
                                @foreach ($foods as $key => $food)
                                    <tr>
                                        <td>{{ $key + $foods->firstItem() }}</td>
                                        <td>
                                            <a class="media align-items-center"
                                                href="{{ route('admin.food.view', [$food['id']]) }}">
                                                <img class="avatar avatar-lg mr-3"
                                                    src="{{ asset('storage/app/public/product') }}/{{ $food['image'] }}"
                                                    onerror="this.src='{{ asset('public/assets/admin/img/100x100/food-default-image.png') }}'"
                                                    alt="{{ $food->name }} image">
                                                <div class="media-body">
                                                    <h5 class="text-hover-primary mb-0">
                                                        {{ Str::limit($food['name'], 20, '...') }}</h5>
                                                </div>
                                            </a>
                                        </td>
                                        <td>
                                            {{ Str::limit($food->category, 20, '...') }}
                                        </td>
                                        <td>
                                            @if ($food->restaurant)
                                                <a class="text--title" href="{{route('admin.restaurant.view',['restaurant'=>$food->restaurant_id])}}" title="{{translate('view_restaurant')}}">
                                                    {{ Str::limit($food->restaurant->name, 20, '...') }}
                                                </a>
                                            @else
                                                <span class="text--danger text-capitalize">{{ Str::limit( translate('messages.Restaurant deleted!'), 20, '...') }}<span>
                                            @endif
                                        </td>
                                        <td>{{ \App\CentralLogics\Helpers::format_currency($food['price']) }}</td>
                                        <td>
                                            <label class="toggle-switch toggle-switch-sm"
                                                for="stocksCheckbox{{ $food->id }}">
                                                <input type="checkbox"
                                                    onclick="location.href='{{ route('admin.food.status', [$food['id'], $food->status ? 0 : 1]) }}'"
                                                    class="toggle-switch-input" id="stocksCheckbox{{ $food->id }}"
                                                    {{ $food->status ? 'checked' : '' }}>
                                                <span class="toggle-switch-label">
                                                    <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                        </td>
                                        <td>
                                            <div class="btn--container justify-content-center">
                                                <a class="btn btn-sm btn--primary btn-outline-primary action-btn"
                                                    href="{{ route('admin.food.edit', [$food['id']]) }}"
                                                    title="{{ translate('messages.edit') }} {{ translate('messages.food') }}"><i
                                                        class="tio-edit"></i>
                                                </a>
                                                <a class="btn btn-sm btn--warning btn-outline-warning action-btn" href="javascript:"
                                                    onclick="form_alert('food-{{ $food['id'] }}','{{ translate('messages.Want_to_delete_this_item') }}')"
                                                    title="{{ translate('messages.delete') }} {{ translate('messages.food') }}"><i
                                                        class="tio-delete-outlined"></i>
                                                </a>
                                            </div>
                                            <form action="{{ route('admin.food.delete', [$food['id']]) }}" method="post"
                                                id="food-{{ $food['id'] }}">
                                                @csrf @method('delete')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if(count($foods) === 0)
                    <div class="empty--data">
                        <img src="{{asset('/public/assets/admin/img/empty.png')}}" alt="public">
                        <h5>
                            {{translate('no_data_found')}}
                        </h5>
                    </div>
                    @endif
                    <div class="page-area px-4 pb-3">
                        <div class="d-flex align-items-center justify-content-end">
                                                {{-- <div>
                        1-15 of 380
                    </div> --}}
                            <div>
                                {!! $foods->withQueryString()->links() !!}
                            </div>
                        </div>
                    </div>
                    <!-- End Table -->
                </div>
                <!-- End Card -->
            </div>
        </div>
    </div>

@endsection

@push('script_2')
    <script>
        $(document).on('ready', function() {
            // INITIALIZATION OF DATATABLES
            // =======================================================
            var datatable = $.HSCore.components.HSDatatables.init($('#datatable'), {
                select: {
                    style: 'multi',
                    classMap: {
                        checkAll: '#datatableCheckAll',
                        counter: '#datatableCounter',
                        counterInfo: '#datatableCounterInfo'
                    }
                },
                language: {
                    zeroRecords: '<div class="text-center p-4">' +
                        '<img class="w-7rem mb-3" src="{{ asset('public/assets/admin/svg/illustrations/sorry.svg') }}" alt="Image Description">' +
                        '<p class="mb-0">{{ translate('No data to show') }}</p>' +
                        '</div>'
                }
            });

            $('#datatableSearch').on('mouseup', function(e) {
                var $input = $(this),
                    oldValue = $input.val();

                if (oldValue == "") return;

                setTimeout(function() {
                    var newValue = $input.val();

                    if (newValue == "") {
                        // Gotcha
                        datatable.search('').draw();
                    }
                }, 1);
            });

            $('#toggleColumn_index').change(function(e) {
                datatable.columns(0).visible(e.target.checked)
            })
            $('#toggleColumn_name').change(function(e) {
                datatable.columns(1).visible(e.target.checked)
            })

            $('#toggleColumn_type').change(function(e) {
                datatable.columns(2).visible(e.target.checked)
            })

            $('#toggleColumn_vendor').change(function(e) {
                datatable.columns(3).visible(e.target.checked)
            })

            $('#toggleColumn_status').change(function(e) {
                datatable.columns(5).visible(e.target.checked)
            })
            $('#toggleColumn_price').change(function(e) {
                datatable.columns(4).visible(e.target.checked)
            })
            $('#toggleColumn_action').change(function(e) {
                datatable.columns(6).visible(e.target.checked)
            })

            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function() {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });

        $('#restaurant').select2({
            ajax: {
                url: '{{ url('/') }}/admin/restaurant/get-restaurants',
                data: function(params) {
                    return {
                        q: params.term, // search term
                        all: true,
                        page: params.page
                    };
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                __port: function(params, success, failure) {
                    var $request = $.ajax(params);

                    $request.then(success);
                    $request.fail(failure);

                    return $request;
                }
            }
        });

        $('#category').select2({
            ajax: {
                url: '{{ route('admin.category.get-all') }}',
                data: function(params) {
                    return {
                        q: params.term, // search term
                        all: true,
                        page: params.page
                    };
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                __port: function(params, success, failure) {
                    var $request = $.ajax(params);

                    $request.then(success);
                    $request.fail(failure);

                    return $request;
                }
            }
        });

        $('#search-form').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('admin.food.search') }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#loading').show();
                },
                success: function(data) {
                    $('#set-rows').html(data.view);
                    $('.page-area').hide();
                    $('#foodCount').html(data.count);
                },
                complete: function() {
                    $('#loading').hide();
                },
            });
        });
    </script>
@endpush
