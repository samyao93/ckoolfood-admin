@foreach($addons as $key=>$addon)
    <tr>
        <td>{{$key+ 1}}</td>
        <td>
            <span class="d-block font-size-sm text-body text-center">
                {{Str::limit($addon['name'],20,'...')}}
            </span>
            </td>
            <td>
                <div class="text-right max-130px">
                    {{\App\CentralLogics\Helpers::format_currency($addon['price'])}}
                </div>
            </td>
            <td class="pl-3">{{Str::limit($addon->restaurant?$addon->restaurant->name:translate('messages.restaurant').' '.translate('messages.deleted'),25,'...')}}</td>
            <td>
                <label class="toggle-switch toggle-switch-sm" for="stausCheckbox{{$addon->id}}">
                <input type="checkbox" onclick="location.href='{{route('admin.addon.status',[$addon['id'],$addon->status?0:1])}}'"class="toggle-switch-input" id="stausCheckbox{{$addon->id}}" {{$addon->status?'checked':''}}>
                    <span class="toggle-switch-label">
                        <span class="toggle-switch-indicator"></span>
                    </span>
                </label>
            </td>
            <td>
                <div class="btn--container justify-content-center">
                    <a class="btn btn-sm btn--primary btn-outline-primary action-btn"
                    href="{{route('admin.addon.edit',[$addon['id']])}}" title="{{translate('messages.edit')}} {{translate('messages.addon')}}"><i class="tio-edit"></i></a>
                    <a class="btn btn-sm btn--danger btn-outline-danger action-btn"     href="javascript:"
                        onclick="form_alert('addon-{{$addon['id']}}','Want to delete this addon ?')" title="{{translate('messages.delete')}} {{translate('messages.addon')}}"><i class="tio-delete-outlined"></i></a>
                    <form action="{{route('admin.addon.delete',[$addon['id']])}}"
                                method="post" id="addon-{{$addon['id']}}">
                        @csrf @method('delete')
                    </form>
                </div>
            </td>
    </tr>
@endforeach
