@foreach($foods as $key=>$food)

<tr>
    <td class="text-center">{{$key+1}}</td>
    <td class="py-2">
        <a class="media align-items-center" href="{{route('admin.food.view',[$food['id']])}}">
            <img class="avatar avatar-lg mr-3" src="{{asset('storage/app/public/product')}}/{{$food['image']}}"
                    onerror="this.src='{{asset('public/assets/admin/img/100x100/food-default-image.png')}}'" alt="{{$food->name}} image">
            <div class="media-body">
                <h5 class="text-hover-primary mb-0">{{Str::limit($food['name'],20,'...')}}</h5>
            </div>
        </a>
    </td>
    <td>
    <div>
        {{Str::limit($food->category,20,'...')}}
    </div>
    </td>
    <td>
        <div class="table--food-price text-right">
            @php($price = \App\CentralLogics\Helpers::format_currency($food['price']))
            {{$price}}
        </div>
    </td>
    <td>
        <label class="toggle-switch toggle-switch-sm" for="stocksCheckbox{{$food->id}}">
            <input type="checkbox" onclick="location.href='{{route('admin.food.status',[$food['id'],$food->status?0:1])}}'"class="toggle-switch-input" id="stocksCheckbox{{$food->id}}" {{$food->status?'checked':''}}>
            <span class="toggle-switch-label">
                <span class="toggle-switch-indicator"></span>
            </span>
        </label>
    </td>
    <td>
        <div class="btn--container justify-content-center">
            <a class="btn btn-sm btn--primary btn-outline-primary action-btn"
                href="{{route('admin.food.edit',[$food['id']])}}" title="{{translate('messages.edit')}} {{translate('messages.food')}}"><i class="tio-edit"></i>
            </a>
            <a class="btn btn-sm btn--danger btn-outline-danger action-btn" href="javascript:"
                onclick="form_alert('food-{{$food['id']}}','Want to delete this item ?')" title="{{translate('messages.delete')}} {{translate('messages.food')}}"><i class="tio-delete-outlined"></i>
            </a>
            <form action="{{route('admin.food.delete',[$food['id']])}}"
                    method="post" id="food-{{$food['id']}}">
                @csrf @method('delete')
            </form>
        </div>
    </td>
</tr>
@endforeach
