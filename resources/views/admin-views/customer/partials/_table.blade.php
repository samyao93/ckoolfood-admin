@foreach($customers as $key=>$customer)
<tr class="">
    <td class="">
        {{$key+1}}
    </td>
    <td class="table-column-pl-0">
        <a href="{{route('admin.customer.view',[$customer['id']])}}" class="text--title text-hover">
            {{$customer['f_name']." ".$customer['l_name']}}
        </a>
    </td>
    <td>
        <div>
            {{$customer['email']}}
        </div>
        <div>
            {{$customer['phone']}}
        </div>
    </td>
    <td>
        <div class="pl-4 ml-2">
            {{$customer->order_count}}
        </div>
    </td>
    <td>
        <div class="d-flex justify-content-center">
            <label class="toggle-switch toggle-switch-sm" for="stocksCheckbox{{$customer->id}}">
                <input type="checkbox" onclick="status_change_alert('{{route('admin.customer.status',[$customer->id,$customer->status?0:1])}}', '{{$customer->status?translate('messages.you_want_to_block_this_customer'):translate('messages.you_want_to_unblock_this_customer')}}', event)" class="toggle-switch-input" id="stocksCheckbox{{$customer->id}}" {{$customer->status?'checked':''}}>
                <span class="toggle-switch-label">
                    <span class="toggle-switch-indicator"></span>
                </span>
            </label>
        </div>
    </td>
    <td>
        <div class="btn--container">
            <a class="btn btn-sm btn--warning btn-outline-warning action-btn"
                href="{{route('admin.customer.view',[$customer['id']])}}" title="{{translate('messages.view')}} {{translate('messages.customer')}}"><i class="tio-visible-outlined"></i>
            </a>
        </div>
    </td>
</tr>
@endforeach
