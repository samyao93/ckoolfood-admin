@extends('layouts.admin.app')

@section('title',translate('messages.account_transaction'))

@push('css_or_js')

@endpush

@section('content')
<div class="content container-fluid">

    <!-- Page Heading -->
    <div class="page-header">
        <h1 class="page-header-title mb-2 text-capitalize">
            <div class="card-header-icon d-inline-flex mr-2 img">
                <img src="{{asset('/public/assets/admin/img/collect-cash.png')}}" class="w-20px" alt="public">
            </div>
            <span>
                {{ translate('Cash Collection Transaction') }}
            </span>
        </h1>
    </div>
    <div class="card mb-3">
        <div class="card-body">
            <form action="{{route('admin.account-transaction.store')}}" method='post' id="add_transaction">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                        <label class="input-label" for="type">{{translate('messages.type')}}<span class="input-label-secondary"></span></label>
                            <select name="type" id="type" class="form-control h--48px">
                                <option value="deliveryman">{{translate('messages.deliveryman')}}</option>
                                <option value="restaurant">{{translate('messages.restaurant')}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="input-label" for="restaurant">{{translate('messages.restaurant')}}<span class="input-label-secondary"></span></label>
                            <select id="restaurant" name="restaurant_id" data-placeholder="{{translate('messages.select')}} {{translate('messages.restaurant')}}" onchange="getAccountData('{{url('/')}}/admin/restaurant/get-account-data/',this.value,'restaurant')" class="form-control h--48px" title="Select Restaurant" disabled>

                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="input-label" for="deliveryman">{{translate('messages.deliveryman')}}<span class="input-label-secondary"></span></label>
                            <select id="deliveryman" name="deliveryman_id" data-placeholder="{{translate('messages.select')}} {{translate('messages.deliveryman')}}" onchange="getAccountData('{{url('/')}}/admin/delivery-man/get-account-data/',this.value,'deliveryman')" class="form-control h--48px" title="Select deliveryman">

                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="input-label" for="method">{{translate('messages.method')}}<span class="input-label-secondary"></span></label>
                            <input class="form-control h--48px" type="text" name="method" id="method" required maxlength="191" placeholder="{{ translate('Ex : Cash') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="input-label" for="ref">{{translate('messages.reference')}}<span class="input-label-secondary"></span></label>
                            <input  class="form-control h--48px" type="text" name="ref" id="ref" maxlength="191" placeholder="{{ translate('Ex : Collect Cash') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="input-label" for="amount">{{translate('messages.amount')}}<span class="input-label-secondary" id="account_info"></span></label>
                            <input class="form-control h--48px" type="number" min=".01" step="0.01" name="amount" id="amount" max="999999999999.99" placeholder="{{ translate('Ex : 100') }}">
                        </div>
                    </div>
                </div>
                <div class="btn--container justify-content-end">
                    <button type="reset" id="reset_btn" class="btn btn--reset">{{translate('messages.reset')}}</button>
                    <button type="submit" class="btn btn--primary">{{translate('messages.collect')}} {{translate('messages.cash')}}</button>
                </div>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-header py-2 border-0">
            <div class="search--button-wrapper">
                <h3 class="card-title">
                    <span>{{ translate('messages.transaction')}} {{ translate('messages.table')}}</span>
                    <span class="badge badge-soft-secondary" id="itemCount" >{{$account_transaction->total()}}</span>
                </h3>
                <!-- Static Search Form -->
                <form action="javascript:" id="search-form" class="my-2 ml-auto mr-sm-2 mr-xl-4 ml-sm-auto flex-grow-1 flex-grow-sm-0">
                        <div class="input--group input-group input-group-merge input-group-flush">
                        <input id="datatableSearch_" type="search" name="search" class="form-control" placeholder="{{ translate('Search by Reference') }}" aria-label="Search" required="">
                        <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>
                    </div>
                    <!-- End Search -->
                </form>
                <!-- Static Search Form -->
                <!-- Static Export Button -->
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
                        {{--<span class="dropdown-header">{{translate('messages.options')}}</span>
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
                        <div class="dropdown-divider"></div>--}}
                        <span class="dropdown-header">{{translate('messages.download')}} {{translate('messages.options')}}</span>
                        {{-- <form action="{{route('admin.export-account-transaction')}}"  method="post">
                            @csrf
                            <input type="hidden" name="type" value="excel">
                            <button type="submit">
                                <img class="avatar avatar-xss avatar-4by3 mr-2"
                                src="{{asset('public/assets/admin')}}/svg/components/excel.svg"
                                alt="Image Description">
                                {{translate('messages.excel')}}
                            </button>
                        </form> --}}
                        <a id="export-excel" class="dropdown-item" href="{{route('admin.export-account-transaction', ['type'=>'excel'])}}">
                            <img class="avatar avatar-xss avatar-4by3 mr-2"
                                    src="{{asset('public/assets/admin')}}/svg/components/excel.svg"
                                    alt="Image Description">
                            {{translate('messages.excel')}}
                        </a>

                        {{-- <form action="{{route('admin.export-account-transaction')}}" method="post">
                            @csrf
                            <input type="hidden" name="type" value="csv">
                            <button type="submit">
                                <img class="avatar avatar-xss avatar-4by3 mr-2"
                                src="{{asset('public/assets/admin')}}/svg/components/placeholder-csv-format.svg"
                                alt="Image Description">
                                .{{translate('messages.csv')}}
                            </button>
                        </form> --}}
                        <a id="export-csv" class="dropdown-item" href="{{route('admin.export-account-transaction', ['type'=>'csv'])}}">
                            <img class="avatar avatar-xss avatar-4by3 mr-2"
                                    src="{{asset('public/assets/admin')}}/svg/components/placeholder-csv-format.svg"
                                    alt="Image Description">
                            .{{translate('messages.csv')}}
                        </a>
                        {{--<a id="export-pdf" class="dropdown-item" href="javascript:;">
                            <img class="avatar avatar-xss avatar-4by3 mr-2"
                                    src="{{asset('public/assets/admin')}}/svg/components/pdf.svg"
                                    alt="Image Description">
                            {{translate('messages.pdf')}}
                        </a>--}}
                    </div>
                </div>
                <!-- Static Export Button -->
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="datatable"
                    class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                    <thead class="thead-light">
                        <tr>
                            <th>{{ translate('messages.sl') }}</th>
                            <th>{{ translate('messages.received_from') }}</th>
                            <th>{{ translate('messages.type') }}</th>
                            <th>{{translate('messages.received_at')}}</th>
                            <th>{{translate('messages.amount')}}</th>
                            <th>{{translate('messages.reference')}}</th>
                            <th class="text-center w-120px">{{translate('messages.action')}}</th>
                        </tr>
                    </thead>
                    <tbody id="set-rows">
                    @foreach($account_transaction as $k=>$at)
                        <tr>
                            <td scope="row">{{$k+$account_transaction->firstItem()}}</td>
                            <td>
                                @if($at->restaurant)
                                <a href="{{route('admin.restaurant.view',[$at->restaurant['id']])}}">{{ Str::limit($at->restaurant->name, 20, '...') }}</a>
                                @elseif($at->deliveryman)
                                <a href="{{route('admin.delivery-man.preview',[$at->deliveryman->id])}}">{{ $at->deliveryman->f_name }} {{ $at->deliveryman->l_name }}</a>
                                @else
                                    {{translate('messages.not_found')}}
                                @endif
                            </td>
                            <td><label class="text-uppercase">{{ translate($at['from_type'])}}</label></td>
                            <td>

                                {{  Carbon\Carbon::parse($at->created_at)->locale(app()->getLocale())->translatedFormat('d M Y '.config('timeformat')) }}
                            </td>
                            <td>{{$at['amount']}}</td>
                            <td>{{$at['ref']}}</td>
                            <td>
                                <div class="btn--container justify-content-center">
                                    <a href="{{route('admin.account-transaction.show',[$at['id']])}}"
                                    class="btn btn-sm btn--warning btn-outline-warning action-btn"><i class="tio-invisible"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @if(count($account_transaction) === 0)
                <div class="empty--data">
                    <img src="{{asset('/public/assets/admin/img/empty.png')}}" alt="public">
                    <h5>
                        {{translate('no_data_found')}}
                    </h5>
                </div>
                @endif
            </div>
        </div>
        <div class="card-footer border-0 pt-0">
            <div class="page-area px-4 pb-3">
                <div class="d-flex align-items-center justify-content-end">
                                        {{-- <div>
                        1-15 of 380
                    </div> --}}
                    <div>
                        {{$account_transaction->links()}}
                    </div>
                </div>
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

        $('#type').on('change', function() {
            if($('#type').val() == 'restaurant')
            {
                $('#restaurant').removeAttr("disabled");
                $('#deliveryman').val("").trigger( "change" );
                $('#deliveryman').attr("disabled","true");
            }
            else if($('#type').val() == 'deliveryman')
            {
                $('#deliveryman').removeAttr("disabled");
                $('#restaurant').val("").trigger( "change" );
                $('#restaurant').attr("disabled","true");
            }
        });
    });
    $('#restaurant').select2({
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

    $('#deliveryman').select2({
        ajax: {
            url: '{{url('/')}}/admin/delivery-man/get-deliverymen',
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

    function getAccountData(route, data_id, type)
    {
        $.get({
                url: route+data_id,
                dataType: 'json',
                success: function (data) {
                    $('#account_info').html('({{translate('messages.cash_in_hand')}}: '+data.cash_in_hand+' {{translate('messages.earning_balance')}}: '+data.earning_balance+')');
                },
            });
    }
</script>
<script>
    $('#add_transaction').on('submit', function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.post({
            url: '{{route('admin.account-transaction.store')}}',
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
                    toastr.success('{{translate('messages.transaction_saved')}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                    setTimeout(function () {
                        location.href = '{{route('admin.account-transaction.index')}}';
                    }, 2000);
                }
            }
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
            url: '{{route('admin.search-account-transaction')}}',
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

    $('#reset_btn').click(function(){
            $('#restaurant').val(null).trigger('change');
            $('#deliveryman').val(null).trigger('change');
        })
</script>
@endpush
