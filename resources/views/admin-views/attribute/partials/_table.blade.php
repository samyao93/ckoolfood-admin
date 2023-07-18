@foreach($attributes as $key=>$attribute)
    <tr>
        <td>
            <span class="d-inline-block pr-3">
                {{$key+1}}
            </span>
        </td>
        <td>
        <span class="d-block font-size-sm text-body">
            {{Str::limit($attribute['name'],20,'...')}}
        </span>
        </td>
        <td>
            <div class="btn--container justify-content-center">
                <a class="btn btn-sm btn--primary btn-outline-primary action-btn" href="{{route('admin.attribute.edit',[$attribute['id']])}}" title="{{translate('messages.edit')}}"><i class="tio-edit"></i>
                </a>
                <a class="btn btn-sm btn--danger btn-outline-danger action-btn" href="javascript:" onclick="form_alert('attribute-{{$attribute['id']}}','Want to delete this attribute ?')" title="{{translate('messages.delete')}}"><i class="tio-delete-outlined"></i>
                </a>
                <form action="{{route('admin.attribute.delete',[$attribute['id']])}}"
                        method="post" id="attribute-{{$attribute['id']}}">
                    @csrf @method('delete')
                </form>
            </div>
        </td>
    </tr>
@endforeach
