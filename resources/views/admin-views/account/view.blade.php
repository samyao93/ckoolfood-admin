@extends('layouts.admin.app')
@section('title',translate('Accoutn transaction information'))
@push('css_or_js')

@endpush

@section('content')
<div class="content container-fluid">
    <!-- Page Heading -->
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
        <h2 class="page-header-title text-capitalize m-0">
            {{translate('messages.account_transaction')}} {{translate('messages.information')}}
        </h2>
    </div>
    <div class="row g-2">

        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title">
                        <span class="card-header-icon">
                            <i class="tio-user"></i>
                        </span>
                        <span>{{$account_transaction->restaurant?translate('messages.restaurant'):translate('messages.deliveryman')}} {{translate('messages.info')}}</span>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="col-md-8 mt-2">
                        @if ($account_transaction->restaurant || $account_transaction->deliveryman)
                            <h4>{{translate('messages.name')}}: {{$account_transaction->restaurant ? $account_transaction->restaurant->name : $account_transaction->deliveryman->f_name.' '.$account_transaction->deliveryman->l_name}}</h4>
                            <h6>{{translate('messages.phone')}}  : {{$account_transaction->restaurant ? $account_transaction->restaurant->phone : $account_transaction->deliveryman->phone}}</h6>
                            <h6>{{translate('messages.collected_cash')}} : {{\App\CentralLogics\Helpers::format_currency($account_transaction->restaurant ? $account_transaction->restaurant->vendor->wallet->collected_cash : $account_transaction->deliveryman->wallet->collected_cash)}}</h6>
                        @else
                            <h4 class="text-center">{{$account_transaction->from_type == 'restaurant' ? translate('messages.Restaurant deleted!') : translate('messages.deliveryman_deleted!')}}</h4>
                        @endif

                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            {{-- {{ $wr }} --}}

            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title">
                        <span class="card-header-icon">
                            <i class="tio-user"></i>
                        </span>
                        <span>{{translate('messages.transaction')}} {{translate('messages.information')}}</span>
                    </h5>
                </div>
                <div class="card-body">
                    <h6>{{translate('messages.amount')}} : {{\App\CentralLogics\Helpers::format_currency($account_transaction->amount)}}</h6>
                    <h6 class="text-capitalize">{{translate('messages.time')}} : {{$account_transaction->created_at->format('Y-m-d '.config('timeformat'))}}</h6>
                    <h6>{{translate('messages.method')}} : {{$account_transaction->method}}</h6>
                    <h6>{{translate('messages.reference')}} : {{$account_transaction->ref}}</h6>
                </div>
            </div>



        </div>



    </div>

</div>

@endsection

@push('script')

@endpush
