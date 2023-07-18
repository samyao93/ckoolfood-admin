@extends('layouts.admin.app')

@section('title',translate('messages.Delivery Man Preview'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <i class="tio-user"></i>
                </span>
                <span>{{$dm['f_name'].' '.$dm['l_name']}}</span>
            </h1>
            <div class="js-nav-scroller hs-nav-scroller-horizontal">
                <!-- Nav -->
                <ul class="nav nav-tabs page-header-tabs">
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('admin.delivery-man.preview', ['id'=>$dm->id, 'tab'=> 'info'])}}"  aria-disabled="true">{{translate('messages.info')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('admin.delivery-man.preview', ['id'=>$dm->id, 'tab'=> 'transaction'])}}"  aria-disabled="true">{{translate('messages.transaction')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{route('admin.delivery-man.preview', ['id'=>$dm->id, 'tab'=> 'timelog'])}}"  aria-disabled="true">{{translate('messages.timelog')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('admin.delivery-man.preview', ['id'=>$dm->id, 'tab'=> 'conversation'])}}"  aria-disabled="true">{{translate('messages.conversations')}}</a>
                    </li>
                </ul>
                <!-- End Nav -->
            </div>
        </div>
        <!-- End Page Header -->
        <!-- End Page Header -->

        <!-- Card -->
        <div class="card mb-3 mb-lg-5 mt-2">
            <div class="card-header py-2">
`               <form class="search--button-wrapper" action="{{url()->current()}}">
                    <h5 class="card-title">{{ translate('messages.order')}} {{ translate('messages.transactions')}}</h5>
                    <div>
                        <input type="date" name="from" id="from" {{request('from')?'value='.request('from'):''}}
                                class="form-control" required>
                    </div>
                    <div>
                        <input type="date" name="to" id="to" {{request('to')?'value='.request('to'):''}}
                                class="form-control" required>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary btn-block">{{translate('messages.show')}}</button>
                    </div>
                </form>
            </div>
            <!-- Body -->
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="datatable"
                        class="table table-borderless table-thead-bordered table-nowrap justify-content-between table-align-middle card-table">
                        <thead class="thead-light">
                            <tr>
                                <th>{{translate('sl')}}</th>
                                <th>{{translate('messages.Shift')}}</th>
                                <th>{{translate('messages.date')}}</th>
                                <th>{{translate('messages.active_time')}} ({{translate('H:M')}})</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($timelogs as $key=>$timelog)
                            <tr>
                                <td scope="row">{{$key+$timelogs->firstItem()}}</td>
                                <td>{{ isset($timelog->shift) ? $timelog->shift->name : translate('no_shift_found')}}</td>
                                <td>{{$timelog->date}}</td>
                                <td>{{str_pad((int)($timelog->working_hour/60), 2, '0', STR_PAD_LEFT)}}:{{str_pad((int)($timelog->working_hour % 60), 2, '0', STR_PAD_LEFT)}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @if(count($timelogs) === 0)
                    <div class="empty--data">
                        <img src="{{asset('/public/assets/admin/img/empty.png')}}" alt="public">
                        <h5>
                            {{translate('no_data_found')}}
                        </h5>
                    </div>
                    @endif
                </div>
            </div>
            <!-- End Body -->
            <div class="card-footer">
                {!!$timelogs->links()!!}
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
