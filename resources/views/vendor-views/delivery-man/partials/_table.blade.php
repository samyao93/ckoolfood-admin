@foreach($delivery_men as $key=>$dm)
<tr>
    <td>{{$key+1}}</td>
    <td>
        <a class="media align-items-center" href="{{route('vendor.delivery-man.preview',[$dm['id']])}}">
            <img class="avatar avatar-lg mr-3" onerror="this.src='{{asset('public/assets/admin/img/160x160/img1.jpg')}}'"
                    src="{{asset('storage/app/public/delivery-man')}}/{{$dm['image']}}" alt="{{$dm['f_name']}} {{$dm['l_name']}}">
            <div class="media-body">
                <h5 class="text-hover-primary mb-0">{{$dm['f_name'].' '.$dm['l_name']}}</h5>
                <span class="rating">
                    <i class="tio-star"></i> {{count($dm->rating)>0?number_format($dm->rating[0]->average, 1, '.', ' '):0}}
                </span>
            </div>
        </a>
    </td>
    <td>
        <div>
            <!-- Status -->
            Currenty Assigned Orders : {{$dm->current_orders}}
            <!-- Status -->
        </div>
        @if($dm->application_status == 'approved')
            @if($dm->active)
            <div>
                Active Status : <strong class="text-primary text-capitalize">{{translate('messages.online')}}</strong>
            </div>
            @else
            <div>
                Active Status : <strong class="text-secondary text-capitalize">{{translate('messages.offline')}}</strong>
            </div>
            @endif
        @elseif ($dm->application_status == 'denied')
            <div>
                Active Status : <strong class="text-danger text-capitalize">{{translate('messages.denied')}}</strong>
            </div>
        @else
            <div>
                Active Status : <strong class="text-info text-capitalize">{{translate('messages.not_approved')}}</strong>
            </div>
        @endif
    </td>
    <td>
        <a class="deco-none" href="tel:{{$dm['phone']}}">{{$dm['phone']}}</a>
    </td>
    <td>
        <div class="text-right max-90px">
            {{ $dm->orders ? count($dm->orders):0 }}
        </div>
    </td>
    <td>
        <div class="btn--container justify-content-center">
            <a class="btn btn--primary btn-outline-primary action-btn" href="{{route('vendor.delivery-man.edit',[$dm['id']])}}" title="{{translate('messages.edit')}}"><i class="tio-edit"></i>
            </a>
            <a class="btn btn--danger btn-outline-danger action-btn" href="javascript:" onclick="form_alert('delivery-man-{{$dm['id']}}','Want to remove this deliveryman ?')" title="{{translate('messages.delete')}}"><i class="tio-delete-outlined"></i>
            </a>
            <form action="{{route('vendor.delivery-man.delete',[$dm['id']])}}" method="post" id="delivery-man-{{$dm['id']}}">
                @csrf @method('delete')
            </form>
        </div>
    </td>
</tr>
@endforeach
