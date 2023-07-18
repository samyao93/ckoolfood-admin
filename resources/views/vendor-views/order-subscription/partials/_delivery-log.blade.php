@php
$logs = $subscription->logs()->with('delivery_man')->latest()->paginate(config('default_pagination'));
@endphp
<div class="card">
<div class="card-header">
    <h5 class="card-header-title">{{translate('messages.subscription_delivery_logs')}}<span class="badge badge-soft-dark ml-2">{{$logs->total()}}</span></h5>
</div>
<!-- Table -->
<div class="table-responsive datatable-custom">
    <table id="columnSearchDatatable"
           class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
           data-hs-datatables-options='{
             "order": [],
             "orderCellsTop": true,
             "paging":false
           }'>
        <thead class="thead-light">
        <tr>
            <th>{{translate('messages.#')}}</th>
            <th style="width: 33%" class="text-center">{{translate('messages.time')}}</th>
            <th style="width: 33%">{{translate('messages.status')}}</th>
            <th style="width: 34%">{{translate('messages.Delivery Man')}}</th>
        </tr>
        </thead>

        <tbody>
        @foreach($logs as $key=>$log)
            <tr>

                <td>{{$key+$logs->firstItem()}}</td>
                @if (isset($log->{$log->order_status}))

                <td class="table-column-pl-0 text-center">
                    {{  Carbon\Carbon::parse($log->{$log->order_status})->locale(app()->getLocale())->translatedFormat('d M Y ' . config('timeformat')) }}

                    {{-- {{date('Y-m-d '.config('timeformat'), strtotime($log->{$log->order_status}))}} --}}
                </td>
                @else
                <td class="table-column-pl-0 text-center">
                    {{  Carbon\Carbon::parse($log->updated_at)->locale(app()->getLocale())->translatedFormat('d M Y ' . config('timeformat')) }}

                    {{-- {{date('Y-m-d '.config('timeformat'), strtotime($log->updated_at))}}--}}
                </td> 
                @endif
                <td class="text-capitalize">
                    {{translate($log->order_status)}}
                </td>
                <td>
                    {{$log->delivery_man ? $log->delivery_man->f_name.' '.$log->delivery_man->l_name : translate('messages.Delivery Man Not Found')}}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <!-- Footer -->
    <div class="card-footer">
        <!-- Pagination -->
    {!! $logs->links() !!}
    <!-- End Pagination -->
    </div>
    <!-- End Footer -->
</div>
</div>
