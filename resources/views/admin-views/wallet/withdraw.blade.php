@extends('layouts.admin.app')

@section('title',translate('Withdraw Request'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">

    <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
        <h2 class="page-header-title text-capitalize m-0">
            {{ translate('Restaurant Withdraw Transaction') }}
        </h2>
    </div>
        <!-- Page Heading -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header py-2 border-0">
                        <div class="search--button-wrapper">
                            <h5 class="card-title">{{ translate('Withdraw Request Table')}} <span id="itemCount"
                                    class="badge badge-soft-dark ml-2">{{$withdraw_req->total()}}</span></h5>

                            <form action="javascript:" id="search-form" class="my-2 ml-auto mr-sm-2 mr-xl-4 ml-sm-auto flex-grow-1 flex-grow-sm-0">
                                <!-- Search -->
                                <div class="input--group input-group input-group-merge input-group-flush">
                                    <input id="datatableSearch_" type="search" name="search" class="form-control" placeholder="{{ translate('Ex : Search by Restaurant name of Phone number') }}" aria-label="Search" required="">
                                    <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>
                                </div>
                                <!-- End Search -->
                            </form>

                            <div class="mr-sm-3 max--sm-100">
                                <select name="withdraw_status_filter" onchange="status_filter(this.value)"
                                    class="custom-select">
                                <option
                                    value="all" {{session()->has('withdraw_status_filter') && session('withdraw_status_filter') == 'all'?'selected':''}}>
                                    {{translate('messages.all')}}
                                </option>
                                <option
                                    value="approved" {{session()->has('withdraw_status_filter') && session('withdraw_status_filter') == 'approved'?'selected':''}}>
                                    {{translate('messages.approved')}}
                                </option>
                                <option
                                    value="denied" {{session()->has('withdraw_status_filter') && session('withdraw_status_filter') == 'denied'?'selected':''}}>
                                    {{translate('messages.denied')}}
                                </option>
                                <option
                                    value="pending" {{session()->has('withdraw_status_filter') && session('withdraw_status_filter') == 'pending'?'selected':''}}>
                                    {{ translate('Pending') }}
                                </option>

                            </select>
                            </div>

                            <!-- Export Button Static -->
                            <div class="hs-unfold ml-3 max--sm-100">
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
                                    {{-- <form action="{{route('admin.vendor.withdraw-list-export')}}" method="post">
                                        @csrf
                                        <input type="hidden" name="type" value="excel">
                                        <button type="submit">
                                            <img class="avatar avatar-xss avatar-4by3 mr-2"
                                            src="{{asset('public/assets/admin')}}/svg/components/excel.svg"
                                            alt="Image Description">
                                            {{translate('messages.excel')}}
                                        </button>
                                    </form> --}}
                                    <a id="export-excel" class="dropdown-item" href="{{route('admin.restaurant.withdraw-list-export', ['type'=>'excel'])}}">
                                        <img class="avatar avatar-xss avatar-4by3 mr-2"
                                                src="{{asset('public/assets/admin')}}/svg/components/excel.svg"
                                                alt="Image Description">
                                        {{translate('messages.excel')}}
                                    </a>

                                    {{-- <form action="{{route('admin.vendor.withdraw-list-export')}}" method="post">
                                        @csrf
                                        <input type="hidden" name="type" value="csv">
                                        <button type="submit">
                                            <img class="avatar avatar-xss avatar-4by3 mr-2"
                                            src="{{asset('public/assets/admin')}}/svg/components/placeholder-csv-format.svg"
                                            alt="Image Description">
                                            .{{translate('messages.csv')}}
                                        </button>
                                    </form> --}}
                                    <a id="export-csv" class="dropdown-item" href="{{route('admin.restaurant.withdraw-list-export', ['type'=>'csv'])}}">
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
                            <!-- Export Button Static -->
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table id="datatable"
                                   class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                                <thead class="thead-light">
                                <tr>
                                    <th>{{ translate('messages.sl') }}</th>
                                    <th>{{translate('messages.amount')}}</th>
                                    {{-- <th>{{translate('messages.note')}}</th> --}}
                                    <th>{{ translate('messages.restaurant') }}</th>
                                    <th>{{translate('messages.request_time')}}</th>
                                    <th>{{translate('messages.status')}}</th>
                                    <th class="text-center">{{translate('messages.action')}}</th>
                                </tr>
                                </thead>
                                <tbody id="set-rows">
                                @foreach($withdraw_req as $k=>$wr)
                                    <tr>
                                        <td scope="row">{{$k+$withdraw_req->firstItem()}}</td>
                                        <td>{{$wr['amount']}}</td>
                                        {{-- <td>{{$wr->__action_note}}</td> --}}
                                        <td>
                                            @if($wr->vendor && isset($wr->vendor->restaurants[0]))
                                            <a class="deco-none"
                                               href="{{route('admin.restaurant.view',[$wr->vendor['id']])}}">{{ Str::limit($wr->vendor?$wr->vendor->restaurants[0]->name:translate('messages.Restaurant deleted!'), 20, '...') }}</a>
                                            @else
                                            {{translate('messages.Restaurant deleted!') }}
                                            @endif
                                        </td>
                                        <td>
                                            {{  Carbon\Carbon::parse($wr->created_at)->locale(app()->getLocale())->translatedFormat('d M Y '.config('timeformat')) }}

                                            {{-- {{date('Y-m-d '.config('timeformat'),strtotime($wr->created_at))}}</td> --}}
                                        <td>
                                            <div>
                                                @if($wr->approved==0)
                                                    <label class="badge badge-soft-primary">{{ translate('Pending') }}</label>
                                                @elseif($wr->approved==1)
                                                    <label class="badge badge-soft-success">{{ translate('Approved') }}</label>
                                                @else
                                                    <label class="badge badge-soft-danger">{{ translate('Denied') }}</label>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn--container justify-content-center">
                                                @if($wr->vendor)
                                                <a href="{{route('admin.restaurant.withdraw_view',[$wr['id'],$wr->vendor['id']])}}"
                                                class="btn btn-sm btn--primary btn-outline-primary action-btn"><i class="tio-invisible"></i>
                                                </a>
                                                @else
                                                {{translate('messages.restaurant').' '.translate('messages.deleted') }}
                                                @endif
                                                {{--<a class="btn btn-sm btn--warning btn-outline-warning action-btn" href="javascript:"
                                                onclick="form_alert('withdraw-{{$wr['id']}}','Want to delete this  ?')">{{translate('messages.Delete')}}</a>
                                                <form action="{{route('vendor.withdraw.close',[$wr['id']])}}"
                                                    method="post" id="withdraw-{{$wr['id']}}">
                                                    @csrf @method('delete')
                                                </form>--}}
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            @if(count($withdraw_req) === 0)
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
                        {{$withdraw_req->links()}}
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('script_2')
    <script>
            $('#search-form').on('submit', function () {
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('admin.restaurant.withdraw_list_search')}}',
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

        function status_filter(type) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('admin.restaurant.status-filter')}}',
                data: {
                    withdraw_status_filter: type
                },
                beforeSend: function () {
                    $('#loading').show()
                },
                success: function (data) {
                    console.log(data)
                    location.reload();
                },
                complete: function () {
                    $('#loading').hide()
                }
            });
        }
    </script>
@endpush

