@foreach ($orders as $key=>$order)
    <tr class="status-{{$order['order_status']}} class-all">
        <td class="text-center pl-4">
            {{$key+1}}
        </td>
        <td class="table-column-pl-0">
            <a class="text--title" href="{{route('admin.order.details',['id'=>$order['id']])}}">{{$order['id']}}</a>
        </td>
        <td>
            <div class="d-inline-block text-right text-uppercase">
                <span class="d-block">{{date('d-m-Y',strtotime($order['created_at']))}}</span>
                <span class="d-block">{{date(config('timeformat'),strtotime($order['created_at']))}}</span>
            </div>
        </td>
        <td>
            <div class="text-center d-inline-block customer-info-table-data">
                @if($order->customer)
                    <a class="text-capitalize" href="{{route('admin.customer.view',[$order['user_id']])}}">
                        <span class="d-block">{{$order->customer['f_name'].' '.$order->customer['l_name']}}</span>
                        <small class="d-block">{{$order->customer['phone']}}</small>
                    </a>
                @else
                    <label class="badge badge-danger">{{translate('messages.invalid')}} {{translate('messages.customer')}} {{translate('messages.data')}}</label>
                @endif
            </div>
        </td>
        <td>
            <div class="d-inline-block text-right total-amount-table-data">
                <div class="paid--amount-status">
                    {{\App\CentralLogics\Helpers::format_currency($order['order_amount'])}}
                </div>
                @if($order->payment_status=='paid')
                    <strong class="text--success order--status">
                        {{translate('messages.paid')}}
                    </strong>
                @else
                    <strong class="text--danger order--status">
                        {{translate('messages.unpaid')}}
                    </strong>
                @endif
            </div>
        </td>
        <td class="text-capitalize">
            @if($order['order_status']=='pending')
                <span class="badge badge-soft-info badge--pending">
                    {{translate('messages.pending')}}
                </span>
            @elseif($order['order_status']=='confirmed')
                <span class="badge badge-soft-info ">
                    {{translate('messages.confirmed')}}
                </span>
            @elseif($order['order_status']=='processing')
                <span class="badge badge-soft-warning">
                    {{translate('messages.processing')}}
                </span>
            @elseif($order['order_status']=='out_for_delivery')
                <span class="badge badge-soft-warning badge--on-the-way">
                    {{translate('messages.out_for_delivery')}}
                </span>
            @elseif($order['order_status']=='delivered')
                <span class="badge badge-soft-success ">
                    {{translate('messages.delivered')}}
                </span>
            @elseif($order['order_status']=='accepted')
                <span class="badge badge-soft-success badge--accepted">
                    {{translate('messages.accepted')}}
                </span>
            @else
                <span class="badge badge-soft-danger badge--cancel">
                    {{translate(str_replace('_',' ',$order['order_status']))}}
                </span>
            @endif
        </td>
        <td>
            <a class="btn btn-sm btn--warning btn-outline-warning action-btn"
            href="{{route('admin.order.details',['id'=>$order['id']])}}">
                <i class="tio-invisible"></i>
            </a>
        </td>
    </tr>
@endforeach
