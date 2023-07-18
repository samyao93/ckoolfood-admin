@extends('layouts.vendor.app')

@section('title',translate('Delivery Man Preview'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title">{{$dm['f_name'].' '.$dm['l_name']}}</h1>
            <div class="js-nav-scroller hs-nav-scroller-horizontal">
                <!-- Nav -->
                <ul class="nav nav-tabs page-header-tabs">
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('vendor.delivery-man.preview', ['id'=>$dm->id, 'tab'=> 'info'])}}"  aria-disabled="true">{{translate('messages.info')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{route('vendor.delivery-man.preview', ['id'=>$dm->id, 'tab'=> 'transaction'])}}"  aria-disabled="true">{{translate('messages.transaction')}}</a>
                    </li>
                </ul>
                <!-- End Nav -->
            </div>
        </div>
        <!-- End Page Header -->

        <!-- Card -->
        <div class="card mb-3 mb-lg-5 mt-2">
            <div class="card-header border-0">
                <h5 class="qcont pr-3 m-0">
                    {{ translate('messages.order')}} {{ translate('messages.transactions')}}
                    {{-- <span class="badge badge-soft-secondary">
                        10
                    </span> --}}
                </h5>
                <span>
                    Delivery Man :
                    <span class="text-primary">{{$dm['f_name'].' '.$dm['l_name']}}</span>
                </span>
            </div>
            <!-- Body -->
            <div class="card-body px-0 pt-0">
                <div class="table-responsive">
                    <table id="datatable"
                        class="table table-borderless table-thead-bordered table-nowrap justify-content-between table-align-middle card-table">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center w-5p">{{ translate('messages.sl') }}</th>
                                <th class="text-center w-30p">{{translate('messages.order')}} {{translate('messages.id')}}</th>
                                <th class="text-center w-30p">
                                    {{translate('messages.deliveryman')}} {{translate('messages.earned')}}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        @php($digital_transaction = \App\Models\OrderTransaction::where('delivery_man_id', $dm->id)->paginate(25))
                        @foreach($digital_transaction as $k=>$dt)
                            <tr>
                                <td scope="row"class="text-center">{{$k+$digital_transaction->firstItem()}}</td>
                                <td class="text-center"><a href="{{route('admin.order.details',$dt->order_id)}}">{{$dt->order_id}}</a></td>
                                <td class="text-center">
                                    <strong>
                                        <!-- Static $ Sign -->
                                        $
                                        <!-- Static $ Sign -->
                                        {{$dt->original_delivery_charge}}
                                    </strong>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- End Body -->
            <div class="card-footer border-0 pt-0">
                {!!$digital_transaction->links()!!}
            </div>
        </div>
        <!-- End Card -->
    </div>
@endsection

@push('script_2')
<script>
    function request_alert(url, message) {
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
                location.href = url;
            }
        })
    }
</script>
@endpush
