@foreach($packages as $key=>$package)
<tr class="">

    <td class="">
        {{$key+$packages->firstItem()}}
    </td>
    <td class="">
        <a href="{{route('admin.subscription.package_details',[$package['id']])}}" class="text--title text-hover">
            {{ Str::limit($package->package_name, 20, '...')   }}
        </a>
    </td>
    <td>
        <div>
            {{ \App\CentralLogics\Helpers::format_currency($package->price) }}
        </div>
    </td>
    <td>
        <div>
            {{$package->validity}}
        </div>
    </td>
    <td>
        <div class="pl-4 ml-2">
            {{$package->transactions_count}}
        </div>
    </td>
    <td>
        <div class="d-flex justify-content-center">
            <label class="toggle-switch toggle-switch-sm" for="stocksCheckbox{{$package->id}}">
                <input type="checkbox" onclick="status_change_alert('{{route('admin.subscription.package_status',[$package->id,$package->status?0:1])}}',
                '{{$package->status?translate('Do You Want To Disable This Package'):translate('Do you want to Active This Package')}}', event)"
                class="toggle-switch-input" id="stocksCheckbox{{$package->id}}" {{$package->status?'checked':''}}>
                <span class="toggle-switch-label">
                    <span class="toggle-switch-indicator"></span>
                </span>
            </label>
        </div>
    </td>


    <td>
        <div class="btn--container justify-content-center">
            <a class="btn btn--primary btn-outline-primary action-btn" href="{{ route('admin.subscription.package_edit',$package->id) }}" title="{{translate('messages.edit')}} {{translate('messages.Package')}}"><i class="tio-edit"></i>
            </a>
            <a class="btn btn-sm btn--warning btn-outline-warning action-btn"
                href="{{route('admin.subscription.package_details',[$package['id']])}}" title="{{translate('messages.view')}} {{translate('messages.Package')}}"><i class="tio-visible-outlined"></i>
            </a>
        </div>
    </td>



</tr>
@endforeach
