@foreach($orders as $key=>$order)
    <tr class="status-{{$order['order_status']}} class-all">
        <td class="">
            {{$key+1}}
        </td>
        <td class="table-column-pl-0">
            <a href="{{route('vendor.order.details',['id'=>$order['id']])}}" class="text-hover">{{$order['id']}}</a>
        </td>
        <td>
            <span class="d-block">
                {{ Carbon\Carbon::parse($order['created_at'])->locale(app()->getLocale())->translatedFormat('d M Y') }}
            </span>
            <span class="d-block text-uppercase">
                {{ Carbon\Carbon::parse($order['created_at'])->locale(app()->getLocale())->translatedFormat(config('timeformat')) }}
            </span>
        </td>
        <td>
            @if($order->customer)
                <a class="text-body text-capitalize"
                   href="{{route('vendor.order.details',['id'=>$order['id']])}}">
                   <span class="d-block font-semibold">
                        {{$order->customer['f_name'].' '.$order->customer['l_name']}}
                   </span>
                   <span class="d-block">
                        {{$order->customer['phone']}}
                   </span>
                </a>
            @else
                <label
                    class="badge badge-danger">{{translate('messages.invalid')}} {{translate('messages.customer')}} {{translate('messages.data')}}</label>
            @endif
        </td>
        <td>


            <div class="text-right mw-85px">
                <div>
                    {{\App\CentralLogics\Helpers::format_currency($order['order_amount'])}}
                </div>
                @if($order->payment_status=='paid')
                <strong class="text-success">
                    {{translate('messages.paid')}}
                </strong>
                @else
                    <strong class="text-danger">
                        {{translate('messages.unpaid')}}
                    </strong>
                @endif
            </div>

        </td>
        @if (isset($order->subscription) && $order->subscription->status != 'canceled' )
            @php
                $order->order_status = $order->subscription_log ? $order->subscription_log->order_status : $order->order_status;
            @endphp
        @endif
        <td class="text-capitalize text-center">
            @if($order['order_status']=='pending')
                <span class="badge badge-soft-info mb-1">
                    {{translate('messages.pending')}}
                </span>
            @elseif($order['order_status']=='confirmed')
                <span class="badge badge-soft-info mb-1">
                  {{translate('messages.confirmed')}}
                </span>
            @elseif($order['order_status']=='processing')
                <span class="badge badge-soft-warning mb-1">
                  {{translate('messages.processing')}}
                </span>
            @elseif($order['order_status']=='picked_up')
                <span class="badge badge-soft-warning mb-1">
                  {{translate('messages.out_for_delivery')}}
                </span>
            @elseif($order['order_status']=='delivered')
                <span class="badge badge-soft-success mb-1">
                  {{translate('messages.delivered')}}
                </span>
            @else
                <span class="badge badge-soft-danger mb-1">
                    {{translate(str_replace('_',' ',$order['order_status']))}}
                </span>
            @endif

            <div class="text-capitalze opacity-7">
                @if($order['order_type']=='take_away')
                    <span>
                        {{translate('messages.take_away')}}
                    </span>
                    @else
                    <span>
                        {{translate('messages.delivery')}}
                    </span>
                @endif
            </div>
        </td>
        <td>
            <div class="btn--container justify-content-center">
                <a class="btn action-btn btn--warning btn-outline-warning" href="{{route('vendor.order.details',['id'=>$order['id']])}}"><i class="tio-visible-outlined"></i></a>
                <a class="btn action-btn btn--primary btn-outline-primary" target="_blank" href="{{route('vendor.order.generate-invoice',[$order['id']])}}"><i class="tio-print"></i></a>
            </div>
        </td>
    </tr>
@endforeach
