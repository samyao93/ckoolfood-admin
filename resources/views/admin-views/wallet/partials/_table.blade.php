@foreach($withdraw_req as $k=>$wr)
<tr>
    <td scope="row">{{$k+1}}</td>
    <td>{{$wr['amount']}}</td>
    {{-- <td>{{$wr->__action_note}}</td> --}}
    <td>
        @if($wr->vendor && isset($wr->vendor->restaurants[0]))
        <a class="deco-none"
           href="{{route('admin.restaurant.view',[$wr->vendor['id']])}}">{{ Str::limit($wr->vendor?$wr->vendor->restaurants[0]->name:translate('messages.Restaurant deleted!'), 20, '...') }}</a>
        @else
        {{translate('messages.Restaurant deleted!') }}
        @endif
    </td>
    <td>{{date('Y-m-d '.config('timeformat'),strtotime($wr->created_at))}}</td>
    <td>
        <div>
            @if($wr->approved==0)
                <label class="badge badge-soft-primary">{{ translate('Pending') }}</label>
            @elseif($wr->approved==1)
                <label class="badge badge-soft-success">{{ translate('Approved') }}</label>
            @else
                <label class="badge badge-soft-danger">{{ translate('Denied') }}</label>
            @endif
        </div>
    </td>
    <td>
        <div class="btn--container justify-content-center">
            @if($wr->vendor)
            <a href="{{route('admin.restaurant.withdraw_view',[$wr['id'],$wr->vendor['id']])}}"
            class="btn btn-sm btn--primary btn-outline-primary action-btn"><i class="tio-invisible"></i>
            </a>
            @else
            {{translate('messages.restaurant').' '.translate('messages.deleted') }}
            @endif
            {{--<a class="btn btn-sm btn--warning btn-outline-warning action-btn" href="javascript:"
            onclick="form_alert('withdraw-{{$wr['id']}}','Want to delete this  ?')">{{translate('messages.Delete')}}</a>
            <form action="{{route('vendor.withdraw.close',[$wr['id']])}}"
                method="post" id="withdraw-{{$wr['id']}}">
                @csrf @method('delete')
            </form>--}}
        </div>
    </td>
</tr>
@endforeach
