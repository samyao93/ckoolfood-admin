@foreach ($account_transaction as $key=>$at)
        <tr>
            <td scope="row">{{$key+1}}</td>
            <td>
                @if($at->restaurant)
                <a href="{{route('admin.restaurant.view',[$at->restaurant['id']])}}">{{ Str::limit($at->restaurant->name, 20, '...') }}</a>
                @elseif($at->deliveryman)
                <a href="{{route('admin.delivery-man.preview',[$at->deliveryman->id])}}">{{ $at->deliveryman->f_name }} {{ $at->deliveryman->l_name }}</a>
                @else
                    {{translate('messages.not_found')}}
                @endif
            </td>
            <td><label class="text-uppercase">{{$at['from_type']}}</label></td>
            <td>{{$at->created_at->format('Y-m-d '.config('timeformat'))}}</td>
            <td>{{$at['amount']}}</td>
            <td>{{$at['ref']}}</td>
            <td>
                <div class="btn--container justify-content-center">
                    <a href="{{route('admin.account-transaction.show',[$at['id']])}}"
                    class="btn btn-sm btn--warning btn-outline-warning action-btn"><i class="tio-invisible"></i>
                    </a>
                </div>
            </td>
        </tr>
@endforeach
