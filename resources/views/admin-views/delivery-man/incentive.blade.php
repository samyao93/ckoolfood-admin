
@extends('layouts.admin.app')

@section('title',translate('messages.delivery_man_incentives'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">

        <div class="card mt-2">

            <!-- Header -->
            <div class="card-header py-2 border-0">
                <h4 class="card-title">
                    <span class="page-header-icon mr-2">
                        <i class="tio-dollar-outlined"></i>
                    </span>
                    <span>
                        {{ translate('messages.delivery_man_incentives')}} <span
                            class="badge badge-soft-dark ml-2" id="itemCount">{{$incentives->total()}}</span>
                    </span>
                </h4>
                <div class="search--button-wrapper justify-content-end">
                    <form action="javascript:" id="search-form" class="search-form" onsubmit="set_filter('{{url()->full()}}', document.getElementById('datatableSearch').value, 'search')">
                        <!-- Search -->
                        <div class="input-group input--group">
                            <input id="datatableSearch" name="search" type="search" class="form-control h--40px" value="{{ request('search') }}" placeholder="{{translate('ex_:_search_delivery_man_data')}}" aria-label="{{translate('messages.search_here')}}">
                            <button type="submit" class="btn btn--secondary h--40px"><i class="tio-search"></i></button>
                        </div>
                        <!-- End Search -->
                    </form>

                </div>
                <!-- End Row -->
            </div>
            <!-- End Header -->
            <div class="card-body p-0">
                <form id="store-incentive-request-form" action="{{route('admin.delivery-man.update-incentive')}}" method="post">
                    @csrf
                    <div class="table-responsive">
                    <table id="datatable"
                            class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                        <thead class="thead-light">
                        <tr>
                            @if($is_history = !Request::is('admin/delivery-man/incentive-history'))
                                <th class="border-0"></th>
                            @endif
                            <th class="border-0">{{translate('sl')}}</th>
                            <th class="border-0">{{ translate('messages.Delivery Man') }}</th>
                            <th class="border-0">{{ translate('messages.zone') }}</th>
                            <th class="border-0">{{ translate('messages.Total Earning') }}</th>
                            <th class="border-0">{{ translate('messages.incentive') }}</th>
                            <th class="border-0">{{ translate('messages.date') }}</th>
                            <th class="border-0">{{translate('messages.status')}}</th>
                            @if ($is_history)
                                <th class="text-center border-0">{{translate('messages.action')}}</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody id="set-rows">
                        @foreach($incentives as $k=>$incentive)
                            <tr>
                                @if ($is_history )
                                    <td scope="row">
                                        @if(now()->startOfDay()->gt(\Carbon\Carbon::parse($incentive->date)) && $incentive->status=='pending' && isset($incentive?->deliveryman))
                                            <input type="checkbox" class="incentive-transaction" name="incentive_id[]" value="{{$incentive->id}}">
                                        @endif
                                    </td>
                                @endif
                                <td scope="row">{{$k+$incentives->firstItem()}}</td>
                                <td>
                                    @if (isset($incentive?->deliveryman ) )
                                    <a href="{{route('admin.delivery-man.preview',$incentive->delivery_man_id)}}">{{ $incentive?->deliveryman?->f_name.' '.$incentive?->deliveryman?->l_name }}</a>
                                    @else
                                    {{translate('not_found') }}
                                    @endif
                                </td>

                                <td>{{$incentive->zone->name}}</td>
                                {{-- <td>{{str_pad((int)($incentive->working_hours/60), 2, '0', STR_PAD_LEFT)}}:{{str_pad((int)($incentive->working_hours % 60), 2, '0', STR_PAD_LEFT)}}</td> --}}
                                {{-- <td>{{\App\CentralLogics\Helpers::format_currency($incentive->zone->min_pay)}}</td> --}}

                                <td>{{\App\CentralLogics\Helpers::format_currency($incentive->today_earning)}}</td>
                                {{-- <td>{{\App\CentralLogics\Helpers::format_currency($incentive->today_earning)}}</td> --}}
                                <td>{{\App\CentralLogics\Helpers::format_currency($incentive->incentive)}}</td>
                                {{-- <td>
                                    @if((now()->startOfDay()->gt(\Carbon\Carbon::parse($incentive->date)) || $incentive->zone && now()->gt(\Carbon\Carbon::parse($incentive->zone->closing_time))) && $incentive->status=='pending')
                                        <input type="number" name="incentive[{{ $incentive->id }}]" value="{{ ($incentive->today_earning >= $incentive->min_pay_subsidy) ? 0 : ($incentive->min_pay_subsidy - $incentive->today_earning - $incentive->incentive) }}" step=".01" required data-toggle="tooltip" title="{{\App\CentralLogics\Helpers::format_currency($incentive->incentive)}}">
                                    @elseif (!$is_history)
                                        {{\App\CentralLogics\Helpers::format_currency($incentive->subsidy)}}
                                    @else
                                        {{\App\CentralLogics\Helpers::format_currency(($incentive->today_earning >= $incentive->min_pay_subsidy) ? 0 : ($incentive->min_pay_subsidy - $incentive->today_earning - $incentive->incentive))}}
                                    @endif
                                </td> --}}
                                <td>{{ $incentive->date }}</td>
                                <td>
                                    @if($incentive->status=='pending')
                                        <label class="badge badge-info">{{ translate("messages.{$incentive->status}") }}</label>
                                    @elseif($incentive->status=='approved')
                                        <label class="badge badge-success">{{ translate("messages.{$incentive->status}") }}</label>
                                    @else
                                        <label class="badge badge-danger">{{ translate("messages.{$incentive->status}") }}</label>
                                    @endif
                                </td>
                                @if ($is_history)
                                    <td>
                                        @if(now()->startOfDay()->gt(\Carbon\Carbon::parse($incentive->date)))
                                        <div class="btn--container justify-content-center">
                                            {{-- <a class="btn action-btn btn--success btn-outline-success" href="javascript:"
                                                onclick="form_alert('incentive-settled-{{$incentive->id}}','{{translate('messages.Want_to_settled_this_incentive')}}')" data-toggle="tooltip" data-placement="top" title="{{translate('messages.approve')}}"><i class="tio-checkmark-circle-outlined"></i>
                                            </a> --}}
                                            <a class="btn action-btn btn--danger btn-outline-danger" href="javascript:"
                                                onclick="@if($incentive->status == 'pending') reject_request('{{route('admin.delivery-man.incentive',['id'=>$incentive->id,'status'=>'denied'])}}','Want to reject this request?') @else on_reject_alert() @endif " title="{{translate('messages.reject')}}"><i class="tio-clear-circle-outlined"></i></a>

                                            {{-- <a class="btn action-btn btn--danger btn-outline-danger" href="javascript:"
                                                onclick="form_alert('incentive-reject-{{$incentive->id}}','{{translate('messages.Want_to_reject_this_incentive')}}')" data-toggle="tooltip" data-placement="top" title="{{translate('messages.deny')}}"><i class="tio-clear-circle-outlined"></i>
                                            </a> --}}
                                            {{-- <form action="{{route('admin.delivery-man.incentive',['id'=>$incentive->id,'status'=>'approved'])}}"
                                                    method="post" id="incentive-settled-{{$incentive->id}}">
                                                @csrf
                                            </form>
                                            <form action="{{route('admin.delivery-man.incentive',['id'=>$incentive->id,'status'=>'denied'])}}"
                                                    method="post" id="incentive-reject-{{$incentive->id}}">
                                                @csrf
                                            </form> --}}
                                        </div>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                        </table>
                    </div>
                    <div class="row p-3 justify-content-md-end ">
                        {{-- <div class="col-sm-6 col-md-4 col-lg-3">
                            <input type="text" name="debit_account" class="form-control" placeholder="{{translate('messages.debit_account')}}" data-toggle="tooltip" data-placement="top" title="{{translate('messages.debit_account')}}" required>
                        </div>
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <input type="text" name="payment_method" class="form-control" placeholder="{{translate('messages.payment_method')}}" data-toggle="tooltip" data-placement="top" title="{{translate('messages.payment_method')}}" required>
                        </div>
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <input type="text" name="purpose" class="form-control" placeholder="{{translate('messages.purpose')}}" data-toggle="tooltip" data-placement="top" title="{{translate('messages.purpose')}}" required>
                        </div> --}}
                        @if (count($incentives) > 0 && $is_history)
                        <button type="submit" id="submit-incentive-request" class="col-sm-6 col-md-4 col-lg-3 btn btn--primary" disabled>{{translate('messages.approve')}}</button>
                        @endif
                    </div>
                </form>
            </div>
            @if(count($incentives) !== 0)
            <hr>
            @endif
            <div class="page-area">
                {!! $incentives->links() !!}
            </div>
            @if(count($incentives) === 0)
            <div class="empty--data">
                <img src="{{asset('/public/assets/admin/svg/illustrations/sorry.svg')}}" alt="public">
                <h5>
                    {{translate('no_data_found')}}
                </h5>
            </div>
            @endif
        </div>
    </div>
@endsection

@push('script_2')
    <script>
        function on_reject_alert()
        {
            toastr.error("{{translate('messages.only_the_pending_request_can_be_rejected')}}", {
                CloseButton: true,
                ProgressBar: true
            });
        }
        $('.incentive-transaction').on('change', function(){
            if($(".incentive-transaction:checked").length>0){
                console.log($(this).length);
                $("#submit-incentive-request").removeAttr('disabled');
            }else{
                $("#submit-incentive-request").attr('disabled', 'disabled');
            }
        })
        function reject_request(action_url, message) {
            Swal.fire({
                title: '{{ translate('messages.Are you sure?') }}',
                text: message,
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#FC6A57',
                cancelButtonText: '{{ translate('messages.no') }}',
                confirmButtonText: '{{ translate('messages.Yes') }}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $('<form/>', { action: action_url, method: 'POST' }).append(
                        $('<input>', {type: 'hidden', name: '_token', value: '{{ csrf_token() }}'}),
                        $('<input>', {type: 'hidden', name: '_method', value: 'put'}),
                    ).appendTo('body').submit();
                }
            })
        }
    </script>
@endpush
