@extends('layouts.admin.app')

@section('title', translate('messages.transactions'))

@section('content')

    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex flex-wrap justify-content-between align-items-center">
                <h1 class="page-header-title text-break">
                    {{-- <i class="tio-museum"></i> <span>{{ $restaurant->name }}'s --}}
                        {{ translate('messages.transactions') }} </span>
                </h1>
            </div>
            <ul class="nav nav-tabs page-header-tabs mb-0 mt-3">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.subscription.package_details',$package_id) }}">{{ translate('messages.package_details') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link font-bold active" href="#">{{ translate('messages.transactions') }}</a>
                </li>
            </ul>

        </div>


        <div class="mb-4 mt-2">
            <form class="row g-4 justify-content-end align-items-end" method="post" action="{{ route('admin.subscription.trans_search_by_date') }} ">
            @csrf
            @method('POST')
                <div class="col-lg-3 col-sm-6">
                    <select class="form-control"
                    onchange="set_filter('{{route('admin.subscription.transcation_list',$package_id)}}',this.value, 'filter')"

                    >
                        <option {{$filter=='all'?'selected':''}} value="all">{{translate('messages.all_time')}}</option>
                        <option {{$filter=='month'?'selected':''}} value="month">{{translate('messages.this_month')}}</option>
                        <option {{$filter=='year'?'selected':''}} value="year">{{translate('messages.this_year')}}</option>
                    </select>
                </div>
                <input type="hidden" value="{{ $package_id }}" name="package_id">

                <div class="col-lg-3 col-sm-6">
                    <label for="start-date" class="__floating-date-label">
                        <span>{{translate('Start Date')}}</span>
                    </label>
                    <input type="date" id="start-date" value="{{ $from ?? '' }}" name="start_date" required class="form-control">
                </div>
                <div class="col-lg-3 col-sm-6">
                    <label for="end-date" class="__floating-date-label">
                        <span>{{translate('End Date')}}</span>
                    </label>
                    <input type="date" id="end-date"   value="{{ $to ?? '' }}"name="end_date" required class="form-control">
                </div>
                <div class="col-lg-3 col-sm-6">
                    <button class="btn btn--primary w-100" type="submit">{{translate('show data')}}</button>
                </div>
            </form>
        </div>



        <!-- Card -->
        <div class="card">
            <!-- Header -->
            <div class="card-header py-2 border-0">
                <div class="search--button-wrapper justify-content-end">
                    <h5 class="card-title">
                       {{ translate('messages.Transaction List') }}
                        <span class="badge badge-soft-secondary badge-pill" id="itemCount">{{ $total }}</span>
                    </h5>
                    <form action="javascript:" id="search-form">
                        @csrf
                        <!-- Search -->
                        <div class="input--group input-group input-group-merge input-group-flush">
                            <input id="datatableSearch_" type="search" name="search" class="form-control" value="{{request()->get('search')}}"
                                    placeholder="{{ translate('Ex: Search by Transcation id...') }}" aria-label="Search" required>
                            <button type="submit" class="btn btn--secondary">
                                <i class="tio-search"></i>
                            </button>
                            @if(request()->get('search'))
                            <button type="reset" class="btn btn--primary ml-2" onclick="location.reload ">{{translate('messages.reset')}}</button>
                            @endif
                        </div>
                        <!-- End Search -->
                    </form>
                    <!-- Unfold -->
                    <div class="hs-unfold mr-2">
                        <a class="js-hs-unfold-invoker btn btn-sm btn-white dropdown-toggle" href="javascript:;"
                            data-hs-unfold-options='{
                                "target": "#usersExportDropdown",
                                "type": "css-animation"
                            }'>
                            <i class="tio-download-to mr-1"></i> {{translate('messages.export')}}
                        </a>

                        <div id="usersExportDropdown"
                                class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-sm-right">
                            <span class="dropdown-header">{{translate('messages.options')}}</span>
                            <a id="export-copy" class="dropdown-item" href="javascript:;">
                                <img class="avatar avatar-xss avatar-4by3 mr-2"
                                        src="{{asset('public/assets/admin')}}/svg/illustrations/copy.svg"
                                        alt="Image Description">
                                {{translate('messages.copy')}}
                            </a>
                            <a id="export-print" class="dropdown-item" href="javascript:;">
                                <img class="avatar avatar-xss avatar-4by3 mr-2"
                                        src="{{asset('public/assets/admin')}}/svg/illustrations/print.svg"
                                        alt="Image Description">
                                {{translate('messages.print')}}
                            </a>
                            <div class="dropdown-divider"></div>
                            <span class="dropdown-header">{{translate('messages.download')}} {{translate('messages.options')}}</span>
                            <a id="export-excel" class="dropdown-item" href="javascript:;">
                                <img class="avatar avatar-xss avatar-4by3 mr-2"
                                        src="{{asset('public/assets/admin')}}/svg/components/excel.svg"
                                        alt="Image Description">
                                {{translate('messages.excel')}}
                            </a>
                            <a id="export-csv" class="dropdown-item" href="javascript:;">
                                <img class="avatar avatar-xss avatar-4by3 mr-2"
                                        src="{{asset('public/assets/admin')}}/svg/components/placeholder-csv-format.svg"
                                        alt="Image Description">
                                .{{translate('messages.csv')}}
                            </a>
                            <a id="export-pdf" class="dropdown-item" href="javascript:;">
                                <img class="avatar avatar-xss avatar-4by3 mr-2"
                                        src="{{asset('public/assets/admin')}}/svg/components/pdf.svg"
                                        alt="Image Description">
                                {{translate('messages.pdf')}}
                            </a>
                        </div>
                    </div>
                    <!-- End Unfold -->
                </div>
                <!-- End Row -->
            </div>
            <!-- End Header -->

            <!-- Table -->
            <div class="table-responsive datatable-custom">
                <table id="datatable"
                       class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                       data-hs-datatables-options='{
                     "columnDefs": [{
                        "targets": [0],
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
                     "paging":false
                   }'>
                    <thead class="thead-light">
                        <tr>
                            {{-- <th >{{ translate('messages.SL') }}
                            </th> --}}
                            <th class="w-90px">{{ translate('messages.transaction') }}
                                                {{ translate('messages.id') }}</th>
                            <th class="w-130px">{{ translate('Transaction Date') }}</th>
                            <th class="w-130px">{{ translate('messages.restaurant_name') }}</th>
                            <th class="w-130px">{{ translate('messages.Pricing') }}</th>
                            <th class="w-130px">{{ translate('messages.Duration') }}</th>
                            <th class="w-130px">{{ translate('messages.Payment Status') }}</th>
                            <th class="text-center w-60px">{{ translate('messages.action') }}</th>
                        </tr>
                    </thead>

                    <tbody id="set-rows">

                        @include('admin-views.subscription.partials._subs_transcation' ,['transcations' =>$transcations])

                    </tbody>
                </table>
            </div>
            @if(count($transcations) === 0)
            <div class="empty--data">
                <img src="{{asset('/public/assets/admin/img/empty.png')}}" alt="public">
                <h5>
                    {{translate('no_data_found')}}
                </h5>
            </div>
            @endif
            <!-- End Table -->
            <div class="page-area px-4 pb-3">
                <div class="d-flex align-items-center justify-content-end">

                    <div>
                        {!! $transcations->appends(request()->all())->links() !!}
                    </div>
                </div>
            </div>
            <!-- End Footer -->

        </div>
        <!-- End Card -->

    </div>


@endsection

@push('script_2')
    <script>
        $('#search-form').on('submit', function() {
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('admin.subscription.transcation_search') }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#loading').show();
                },
                success: function(data) {
                    $('#set-rows').html(data.view);
                    $('#itemCount').html(data.total);
                    $('.page-area').hide();
                },
                complete: function() {
                    $('#loading').hide();
                },
            });
        });
    </script>
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
                var datatable = $.HSCore.components.HSDatatables.init($('#datatable'), {
                    dom: 'Bfrtip',
                    buttons: [
                        {
                            extend: 'copy',
                            className: 'd-none'
                        },
                        {
                            extend: 'excel',
                            className: 'd-none'
                        },
                        {
                            extend: 'csv',
                            className: 'd-none'
                        },
                        {
                            extend: 'pdf',
                            className: 'd-none'
                        },
                        {
                            extend: 'print',
                            className: 'd-none'
                        },
                    ],
                    select: {
                        style: 'multi',
                        selector: 'td:first-child input[type="checkbox"]',
                        classMap: {
                            checkAll: '#datatableCheckAll',
                            counter: '#datatableCounter',
                            counterInfo: '#datatableCounterInfo'
                        }
                    },
                    language: {
                        zeroRecords: '<div class="text-center p-4">' +
                            '<img class="mb-3 w-7rem" src="{{asset('public/assets/admin')}}/svg/illustrations/sorry.svg" alt="Image Description">' +
                            '<p class="mb-0">{{ translate('No data to show') }}</p>' +
                            '</div>'
                    }
                });

                $('#export-copy').click(function () {
                    datatable.button('.buttons-copy').trigger()
                    toastr.success('{{__("Copied Successfully")}}', {
                                        CloseButton: true,
                                        ProgressBar: true
                                    });
                });

                $('#export-excel').click(function () {
                    datatable.button('.buttons-excel').trigger()
                });

                $('#export-csv').click(function () {
                    datatable.button('.buttons-csv').trigger()
                });

                $('#export-pdf').click(function () {
                    datatable.button('.buttons-pdf').trigger()
                });

                $('#export-print').click(function () {
                    datatable.button('.buttons-print').trigger()
                });

                $('#datatableSearch').on('mouseup', function (e) {
                    var $input = $(this),
                        oldValue = $input.val();

                    if (oldValue == "") return;

                    setTimeout(function () {
                        var newValue = $input.val();

                        if (newValue == "") {
                            // Gotcha
                            datatable.search('').draw();
                        }
                    }, 1);
                });

                $('#toggleColumn_name').change(function (e) {
                    datatable.columns(1).visible(e.target.checked)
                })

                $('#toggleColumn_price').change(function (e) {
                    datatable.columns(2).visible(e.target.checked)
                })

                $('#toggleColumn_validity').change(function (e) {
                    datatable.columns(3).visible(e.target.checked)
                })

                $('#toggleColumn_total_sell').change(function (e) {
                    datatable.columns(4).visible(e.target.checked)
                })

                $('#toggleColumn_status').change(function (e) {
                    datatable.columns(5).visible(e.target.checked)
                })

                $('#toggleColumn_actions').change(function (e) {
                    datatable.columns(6).visible(e.target.checked)
                })

                // INITIALIZATION OF TAGIFY
                // =======================================================
                // $('.js-tagify').each(function () {
                //     var tagify = $.HSCore.components.HSTagify.init($(this));
                // });
            });
        </script>

@endpush









