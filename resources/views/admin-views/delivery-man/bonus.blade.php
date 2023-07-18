@extends('layouts.admin.app')

@section('title',translate('messages.bonus'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title mr-3">
                <span class="page-header-icon">
                    <img src="{{asset('/public/assets/admin/img/money.png')}}" class="w--26" alt="">
                </span>
                <span>
                     {{translate("messages.bonus")}}
                </span>
            </h1>
        </div>
        <!-- Page Header -->
        <div class="card gx-2 gx-lg-3">
            <div class="card-body">
                <form action="{{route('admin.delivery-man.bonus')}}" method="post" enctype="multipart/form-data" id="add_fund">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6 col-12">
                            <div class="form-group">
                                <label class="input-label" for="customer">{{translate('messages.Delivery Man')}}</label>
                                <select id='customer' name="delivery_man_id" data-placeholder="{{translate('messages.select_delivery_man')}}" class="js-data-example-ajax form-control" required>

                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 col-12">
                            <div class="form-group">
                                <label class="input-label" for="amount">{{translate("messages.amount")}}</label>

                                <input type="number" class="form-control" name="amount" id="amount" step=".01" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label class="input-label" for="referance">{{translate('messages.reference')}} <small>({{translate('messages.optional')}})</small></label>

                                <input type="text" class="form-control" name="referance" id="referance">
                            </div>
                        </div>
                    </div>
                    <div class="btn--container justify-content-end">
                        <button type="reset" id="reset" class="btn btn--reset">{{translate('messages.reset')}}</button>
                        <button type="submit" id="submit" class="btn btn--primary">{{translate('messages.submit')}}</button>
                    </div>
                </form>
            </div>
            <!-- End Table -->
        </div>

        <!-- Card -->
        <div class="card mt-3">
            <!-- Header -->
            <div class="card-header border-0">
                <h4 class="card-title">
                    <span class="card-header-icon">
                        {{-- <i class="tio-dollar-outlined"></i> --}}
                    </span>
                    <span>{{__('messages.transactions')}}</span>
                    <span class="badge badge-soft-dark ml-2" id="itemCount">{{$data->total()}}</span>
                </h4>
                <form>
                    <!-- Search -->
                    <div class="input--group input-group input-group-merge input-group-flush">
                        <input id="datatableSearch_" type="search" name="search"  value="{{ request()->search ?? null }}"  class="form-control"
                            placeholder="{{ translate('Search by name or transaction id ...') }}" aria-label="Search">
                        <button type="submit" class="btn btn--secondary">
                            <i class="tio-search"></i>
                        </button>
                    </div>
                    <!-- End Search -->
                </form>
            </div>
            <!-- End Header -->

            <!-- Body -->
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="datatable"
                        class="table table-thead-bordered table-align-middle card-table table-nowrap">
                        <thead class="thead-light">
                            <tr>
                                <th class="border-0">{{translate('messages.sl')}}</th>
                                <th class="border-0">{{translate('messages.transaction_id')}}</th>
                                <th class="border-0">{{translate('messages.Delivery Man')}}</th>
                                <th class="border-0">{{translate('messages.bonus')}}</th>
                                <th class="border-0">{{translate('messages.reference')}}</th>
                                <th class="border-0">{{translate('messages.created_at')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($data as $k=>$wt)
                            <tr scope="row">
                                <td >{{$k+$data->firstItem()}}</td>
                                <td>{{$wt->transaction_id}}</td>
                                <td>
                                    @if ($wt->delivery_man)
                                    <a href="{{route('admin.delivery-man.preview',[$wt['delivery_man_id']])}}">{{Str::limit($wt->delivery_man->f_name.' '.$wt->delivery_man->l_name ,20,'...')}}</a>
                                    @else
                                    {{ translate(('messages.not_found')) }}
                                    @endif
                                </td>
                                <td>{{\App\CentralLogics\Helpers::format_currency($wt->credit)}}</td>
                                <td>{{$wt->reference}}</td>
                                <td>
                                    <span class="d-block">{{date('d-m-Y',strtotime($wt['created_at']))}}</span>
                                    <span class="d-block">{{date(config('timeformat'),strtotime($wt['created_at']))}}</span>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- End Body -->
            @if(count($data) !== 0)
            <hr>
            @endif
            <div class="page-area">
                {!! $data->withQueryString()->links() !!}
            </div>
            @if(count($data) === 0)
            <div class="empty--data">
                <img src="{{asset('/public/assets/admin/svg/illustrations/sorry.svg')}}" alt="public">
                <h5>
                    {{translate('no_data_found')}}
                </h5>
            </div>
            @endif
        </div>
        <!-- End Card -->
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

        $('#add_fund').on('submit', function (e) {

            e.preventDefault();
            var formData = new FormData(this);

            Swal.fire({
                title: '{{translate('messages.are_you_sure')}}',
                text: '{{translate('messages.you_want_to_add_bonus')}}'+$('#amount').val()+' {{\App\CentralLogics\Helpers::currency_code().' '.translate('messages.to')}} '+$('#customer option:selected').text(),
                type: 'info',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: 'primary',
                cancelButtonText: '{{translate('messages.no')}}',
                confirmButtonText: '{{translate('messages.add')}}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.post({
                        url: '{{route('admin.delivery-man.bonus')}}',
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        beforeSend: function() {
                            $('#loading').show();
                        },
                        success: function (data) {
                            $('#loading').hide();
                            if (data.errors) {
                                for (var i = 0; i < data.errors.length; i++) {
                                    toastr.error(data.errors[i].message, {
                                        CloseButton: true,
                                        ProgressBar: true
                                    });
                                }
                            } else {
                                toastr.success('{{translate("messages.bonus_added_successfulley")}}', {
                                    CloseButton: true,
                                    ProgressBar: true
                                });
                                setTimeout(function () {
                                    window.location.reload();
                                }, 2000);

                            }
                        },
                    });
                }
            })
        })

        $('.js-data-example-ajax').select2({
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
    </script>
@endpush
