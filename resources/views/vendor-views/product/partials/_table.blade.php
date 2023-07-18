@foreach($foods as $key=>$food)
    <tr>
        <td>{{$key+1}}</td>
        <td>
            <a class="media align-items-center" href="{{route('vendor.food.view',[$food['id']])}}">
                <img class="avatar avatar-lg mr-3" src="{{asset('storage/app/public/product')}}/{{$food['image']}}"
                        onerror="this.src='{{asset('/public/assets/admin/img/100x100/food-default-image.png')}}'" alt="{{$food->name}} image">
                <div class="media-body">
                    <h5 class="text-hover-primary mb-0">{{Str::limit($food['name'],20,'...')}}</h5>
                </div>
            </a>
        </td>
        <td>
        {{Str::limit($food->category,20,'...')}}
        </td>
        <td>
            <div class="text-right mx-auto mw-36px">
            <!-- Static Symbol -->
            {{ \App\CentralLogics\Helpers::currency_symbol() }}
            <!-- Static Symbol -->
                {{($food['price'])}}
            </div>
        </td>
        <td>
            <div class="d-flex">
                <div class="mx-auto">
                    <label class="toggle-switch toggle-switch-sm mr-2"  data-toggle="tooltip" data-placement="top" title="{{ translate('messages.Recommend_to_customers') }}" for="stocksCheckbox{{$food->id}}">
                        <input type="checkbox" onclick="location.href='{{route('vendor.food.recommended',[$food['id'],$food->recommended?0:1])}}'"class="toggle-switch-input" id="stocksCheckbox{{$food->id}}" {{$food->recommended?'checked':''}}>
                        <span class="toggle-switch-label">
                            <span class="toggle-switch-indicator"></span>
                        </span>
                    </label>
                </div>
            </div>
        </td>
        <td>
            <div class="d-flex">
                <div class="mx-auto">
                    <label class="toggle-switch toggle-switch-sm mr-2" data-toggle="tooltip" data-placement="top" title="{{ translate('messages.Change_food_visibility_to_customers') }}" for="statusCheckbox{{$food->id}}">
                        <input type="checkbox" onclick="location.href='{{route('vendor.food.status',[$food['id'],$food->status?0:1])}}'"class="toggle-switch-input" id="statusCheckbox{{$food->id}}" {{$food->status?'checked':''}}>
                        <span class="toggle-switch-label">
                            <span class="toggle-switch-indicator"></span>
                        </span>
                    </label>
                </div>
            </div>
        </td>
        <td>
            <div class="btn--container justify-content-center">
                <a class="btn action-btn btn--primary btn-outline-primary"
                    href="{{route('vendor.food.edit',[$food['id']])}}" title="{{translate('messages.edit')}} {{translate('messages.food')}}"><i class="tio-edit"></i>
                </a>
                <a class="btn action-btn btn--danger btn-outline-danger" href="javascript:"
                    onclick="form_alert('food-{{$food['id']}}','{{ translate('messages.Want to delete this item ?') }}')" title="{{translate('messages.delete')}} {{translate('messages.food')}}"><i class="tio-delete-outlined"></i>
                </a>
                <form action="{{route('vendor.food.delete',[$food['id']])}}"
                        method="post" id="food-{{$food['id']}}">
                    @csrf @method('delete')
                </form>
            </div>
        </td>
    </tr>
@endforeach
