@foreach($categories as $key=>$category)
<tr>
    <td>{{$key+1}}</td>
    <td>
        <div class="pl-2">
            {{$category->id}}
        </div>
    </td>
    <td>
    <span class="d-block font-size-sm text-body">
        {{Str::limit($category['name'], 20,'...')}}
    </span>
    </td>
    <td>
        <form action="{{route('admin.category.priority',$category->id)}}">
        <select name="priority" id="priority" class=" form-control form--control-select {{$category->priority == 0 ? 'text--title':''}} {{$category->priority == 1 ? 'text--info':''}} {{$category->priority == 2 ? 'text--success':''}} " onchange="this.form.submit()">
            <option class="text--title" value="0" {{$category->priority == 0?'selected':''}}>{{translate('messages.normal')}}</option>
            <option class="text--info" value="1" {{$category->priority == 1?'selected':''}}>{{translate('messages.medium')}}</option>
            <option class="text--success" value="2" {{$category->priority == 2?'selected':''}}>{{translate('messages.high')}}</option>
        </select>
        </form>
    </td>
    <td>
        <label class="toggle-switch toggle-switch-sm ml-2" for="stocksCheckbox{{$category->id}}">
        <input type="checkbox" onclick="location.href='{{route('admin.category.status',[$category['id'],$category->status?0:1])}}'"class="toggle-switch-input" id="stocksCheckbox{{$category->id}}" {{$category->status?'checked':''}}>
            <span class="toggle-switch-label">
                <span class="toggle-switch-indicator"></span>
            </span>
        </label>
    </td>
    <td>
        <div class="btn--container">
            <a class="btn btn-sm btn--primary btn-outline-primary action-btn"
                href="{{route('admin.category.edit',[$category['id']])}}" title="{{translate('messages.edit')}} {{translate('messages.category')}}"><i class="tio-edit"></i>
            </a>
            <a class="btn btn-sm btn--danger btn-outline-danger action-btn" href="javascript:"
            onclick="form_alert('category-{{$category['id']}}','Want to delete this category')" title="{{translate('messages.delete')}} {{translate('messages.category')}}"><i class="tio-delete-outlined"></i>
            </a>
        </div>

        <form action="{{route('admin.category.delete',[$category['id']])}}" method="post" id="category-{{$category['id']}}">
            @csrf @method('delete')
        </form>
    </td>
</tr>
@endforeach
