@extends('layouts.admin.app')

@section('title', translate('messages.New_joining') . ' ' . translate('messages.deliverymen'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-12">
                    <h1 class="page-header-title text-capitalize">
                        <div class="card-header-icon d-inline-flex mr-2 img">
                            <img src="{{ asset('/public/assets/admin/img/delivery-man.png') }}" alt="public">
                        </div>
                        <span>
                            {{ translate('messages.New_joining_request') }}
                        </span>
                    </h1>
                </div>
            </div>
        </div>

        <div class="js-nav-scroller hs-nav-scroller-horizontal">
            <!-- Nav -->
            <ul class="nav nav-tabs page-header-tabs">
                <li class="nav-item">
                    <a class="nav-link active"
                        href="{{ route('admin.delivery-man.pending') }}">{{ translate('messages.Pending_delivery_man') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link"
                        aria-disabled="true"href="{{ route('admin.delivery-man.denied') }}">{{ translate('messages.denied') }} {{ translate('messages.deliveryman') }}</a>
                </li>
            </ul>
            <!-- End Nav -->
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <!-- Card -->
                <div class="card">
                    <!-- Header -->
                    <div class="card-header py-2">
                        <div class="search--button-wrapper">
                            <h5 class="card-title">{{ translate('messages.deliveryman') }}<span
                                    class="badge badge-soft-dark ml-2" id="itemCount">{{ $delivery_men->total() }}</span>
                            </h5>
                            <form>
                                <!-- Search -->
                                <div class="input--group input-group input-group-merge input-group-flush">
                                    <input id="datatableSearch_" type="search" name="search" class="form-control"
                                        placeholder="{{ translate('Search by name...') }}" aria-label="Search">
                                    <button type="submit" class="btn btn--secondary">
                                        <i class="tio-search"></i>
                                    </button>
                                </div>
                                <!-- End Search -->
                            </form>

                            <div class="hs-unfold ml-3">
                                <div class="select-item">
                                    <select name="zone_id" class="form-control js-select2-custom"
                                        onchange="set_zone_filter('{{ url()->full() }}',this.value)">
                                        <option selected disabled>{{ translate('messages.select_zone') }}</option>
                                        <option value="all">{{ translate('messages.all_zones') }}</option>
                                        @foreach (\App\Models\Zone::orderBy('name')->get() as $z)
                                            <option value="{{ $z['id'] }}"
                                                {{ isset($zone) && $zone->id == $z['id'] ? 'selected' : '' }}>
                                                {{ $z['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- End Header -->

                    <!-- Table -->
                    <div class="table-responsive datatable-custom fz--14px">
                        <table id="columnSearchDatatable"
                            class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                            data-hs-datatables-options='{
                                 "order": [],
                                 "orderCellsTop": true,
                                 "paging":false
                               }'>
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-capitalize">{{ translate('messages.sl') }}</th>
                                    <th class="text-capitalize w-20p">{{ translate('messages.name') }}</th>
                                    <th class="text-capitalize">{{ translate('messages.contact') }}</th>
                                    <th class="text-capitalize">{{ translate('messages.zone') }}</th>
                                    {{-- <th class="text-capitalize text-center">{{ translate('Total Orders') }}</th> --}}
                                    <th class="text-capitalize">{{ translate('messages.availability') }}
                                        {{ translate('messages.status') }}</th>
                                    <th class="text-capitalize text-center w-110px">{{ translate('messages.action') }}</th>
                                </tr>
                            </thead>

                            <tbody id="set-rows">
                                @foreach ($delivery_men as $key => $dm)
                                    <tr>
                                        <td>{{ $key + $delivery_men->firstItem() }}</td>
                                        <td>
                                            <a class="table-rest-info"
                                                href="{{ route('admin.delivery-man.preview', [$dm['id']]) }}">
                                                <img onerror="this.src='{{ asset('public/assets/admin/img/160x160/img1.jpg') }}'"
                                                    src="{{ asset('storage/app/public/delivery-man') }}/{{ $dm['image'] }}"
                                                    alt="{{ $dm['f_name'] }} {{ $dm['l_name'] }}">
                                                <div class="info">
                                                    <h5 class="text-hover-primary mb-0">
                                                        {{ $dm['f_name'] . ' ' . $dm['l_name'] }}</h5>
                                                    <span class="d-block text-body">
                                                        <!-- Rating -->
                                                        <span class="rating">
                                                            <i class="tio-star"></i>
                                                            {{ count($dm->rating) > 0 ? number_format($dm->rating[0]->average, 1, '.', ' ') : 0 }}
                                                        </span>
                                                        <!-- Rating -->
                                                    </span>
                                                </div>
                                            </a>
                                        </td>
                                        <td>
                                            <a class="deco-none" href="tel:{{ $dm['phone'] }}">{{ $dm['phone'] }}</a>
                                        </td>
                                        <td>
                                            @if ($dm->zone)
                                                <span>{{ $dm->zone->name }}</span>
                                            @else
                                                <span>{{ translate('messages.zone') . ' ' . translate('messages.deleted') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($dm->application_status == 'denied')
                                                <div>
                                                    <strong
                                                        class="text-danger text-capitalize">{{ translate('messages.denied') }}</strong>
                                                </div>
                                            @else
                                                <div>
                                                    <strong
                                                        class="text-info text-capitalize">{{ translate('messages.pending') }}</strong>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn--container justify-content-center">
                                                <a class="btn btn-sm btn--primary btn-outline-primary action-btn"
                                                data-toggle="tooltip" data-placement="top" title="{{translate('Approve')}}"
                                                    onclick="request_alert('{{ route('admin.delivery-man.application', [$dm['id'], 'approved']) }}','{{ translate('messages.you_want_to_approve_this_application') }}')"
                                                    href="javascript:"><i class="tio-done font-weight-bold"></i></a>
                                                @if ($dm->application_status != 'denied')
                                                    <a class="btn btn-sm btn--danger btn-outline-danger action-btn" data-toggle="tooltip" data-placement="top" title="{{translate('Deny')}}"
                                                        onclick="request_alert('{{ route('admin.delivery-man.application', [$dm['id'], 'denied']) }}','{{ translate('messages.you_want_to_deny_this_application') }}')"
                                                        href="javascript:"><i
                                                        class="tio-clear"></i></a>
                                                @endif

                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @if (count($delivery_men) === 0)
                            <div class="empty--data">
                                <img src="{{ asset('/public/assets/admin/img/empty.png') }}" alt="public">
                                <h5>
                                    {{ translate('no_data_found') }}
                                </h5>
                            </div>
                        @endif
                        <div class="page-area px-4 pb-3">
                            <div class="d-flex align-items-center justify-content-end">
                                <div>
                                    {!! $delivery_men->appends(request()->all())->links() !!}
                                </div>
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
            var datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'));

            $('#column1_search').on('keyup', function() {
                datatable
                    .columns(1)
                    .search(this.value)
                    .draw();
            });

            $('#column2_search').on('keyup', function() {
                datatable
                    .columns(2)
                    .search(this.value)
                    .draw();
            });

            $('#column3_search').on('keyup', function() {
                datatable
                    .columns(3)
                    .search(this.value)
                    .draw();
            });

            $('#column4_search').on('keyup', function() {
                datatable
                    .columns(4)
                    .search(this.value)
                    .draw();
            });
            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function() {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });

        });
    </script>
    <script>
        function request_alert(url, message) {
            Swal.fire({
                title: '{{ translate('messages.are_you_sure') }}',
                text: message,
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#FC6A57',
                cancelButtonText: '{{ translate('messages.no') }}',
                confirmButtonText: '{{ translate('messages.yes') }}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    location.href = url;
                }
            })
        }
    </script>
@endpush
