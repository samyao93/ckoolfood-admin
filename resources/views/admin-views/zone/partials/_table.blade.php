@php($non_mod = 0)

@foreach($zones as $key=>$zone)
@php($non_mod = ( ($zone?->minimum_shipping_charge && $zone?->per_km_shipping_charge ) && $non_mod == 0) ? $non_mod:$non_mod+1 )
<tr>
    <td>{{$key+ $zones?->firstItem()}}</td>
    <td class="text-center">
        <span class="move-left">
            {{$zone->id}}
        </span>
    </td>
    <td class="pl-5">
        <span class="d-block font-size-sm text-body">
            {{$zone['name']}}
        </span>
    </td>
    <td class="text-center">
        <span class="move-left">
            {{$zone->restaurants_count}}
        </span>
    </td>
    <td class="text-center">
        <span class="move-left">
            {{$zone->deliverymen_count}}
        </span>
    </td>
    <td>
        <label class="toggle-switch toggle-switch-sm" data-toggle="modal" data-target="#status-warning-modal">
            <input type="checkbox" class="toggle-switch-input" id="stocksCheckbox{{$zone->id}}" {{$zone->status?'checked':''}}>
            <span class="toggle-switch-label">
                <span class="toggle-switch-indicator"></span>
            </span>
        </label>
        <form action="{{route('admin.zone.status',[$zone['id'],$zone->status?0:1])}}" method="get" id="status-{{$zone['id']}}">
        </form>
    </td>
    <td>
        <div class="btn--container justify-content-center">
            <a class="btn btn-sm btn--primary btn-outline-primary action-btn"
                href="{{route('admin.zone.edit',[$zone['id']])}}" title="{{translate('messages.edit')}} {{translate('messages.zone')}}"><i class="tio-edit"></i>
            </a>
            <!-- <div class="popover-wrapper active"> add active class to show -->
            <div class="popover-wrapper {{ $non_mod == 1 ? 'active':'' }}">
                <a class="btn active action-btn btn--warning btn-outline-warning" href="{{route('admin.zone.settings',['id'=>$zone['id']])}}" title="{{translate('messages.zone_settings')}}">
                    <i class="tio-settings"></i>
                </a>
                <div class="popover __popover  {{ $non_mod == 1  ? '':'d-none' }}">
                    <div class="arrow"></div>
                    <h3 class="popover-header">{{ translate('Important') }}</h3>
                    <div class="popover-body">
                        {{ translate('Must_set_the_delivery_charges_for_this_zone._Other_wise_this_zone_will_not_work_properly')}}
                    </div>
                </div>
            </div>
        </div>
    </td>
</tr>
@endforeach

