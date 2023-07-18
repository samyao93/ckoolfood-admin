@foreach($orders as $key=>$order)
<tr>
    <td>{{$key+$orders->firstItem()}}</td>
    <td class="table-column-pl-0 text-center">
        <a href="{{route('admin.order.details',['id'=>$order['id']])}}">{{$order['id']}}</a>
    </td>
    <td>
        <div class="text-center">
            {{\App\CentralLogics\Helpers::format_currency($order['order_amount'])}}
        </div>
    </td>
    <td>
        <div class="btn--container justify-content-center">
        <a class="btn btn-sm btn--warning btn-outline-warning action-btn"
                    href="{{route('admin.order.details',['id'=>$order['id']])}}" title="{{translate('messages.view')}}"><i
                            class="tio-visible-outlined"></i></a>
        <a class="btn btn-sm btn--primary btn-outline-primary action-btn" target="_blank"
                    href="{{route('admin.order.generate-invoice',[$order['id']])}}" title="{{translate('messages.invoice')}}"><i
                            class="tio-print"></i> </a>
        </div>
    </td>
</tr>
@endforeach
