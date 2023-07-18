@forelse ($shifts as $key => $shift)
<tr>
    <td>{{$key+$shifts->firstItem()}}</td>

    <td>
    <span class="d-block font-size-sm text-body">
        {{$shift['name']}}
    </span>
    </td>
    <td>
        {{ Carbon\Carbon::parse($shift->start_time)->locale(app()->getLocale())->translatedFormat(config('timeformat'))}}
    </td>
    <td>
        {{ Carbon\Carbon::parse($shift->end_time)->locale(app()->getLocale())->translatedFormat(config('timeformat'))}}
    <td>
        <label class="toggle-switch toggle-switch-sm" for="stocksCheckbox{{$shift->id}}" >
            <input class="toggle-switch-input" type="checkbox"  onclick="status_change_alert('{{route('admin.shift.status',[$shift['id'],$shift->status?0:1])}}','{{ translate('Want to change status for this shift ?') }}', event)"
            id="stocksCheckbox{{$shift->id}}" {{$shift->status?'checked':''}}>
            <span class="toggle-switch-label">
                <span class="toggle-switch-indicator"></span>
            </span>
        </label>

        {{-- <label class="toggle-switch toggle-switch-sm" for="stocksCheckbox{{$dm->id}}">
            <input type="checkbox" onclick="status_change_alert('{{route('admin.restaurant.status',[$dm->id,$dm->status?0:1])}}', '{{translate('messages.you_want_to_change_this_restaurant_status')}}', event)" class="toggle-switch-input" id="stocksCheckbox{{$dm->id}}" {{$dm->status?'checked':''}}>
            <span class="toggle-switch-label">
                <span class="toggle-switch-indicator"></span>
            </span>
        </label> --}}

        <form action="{{route('admin.shift.status',[$shift['id'],$shift->status?0:1])}}" method="get" id="stocksCheckbox-{{$shift['id']}}">
        </form>
    </td>
    <td >
        <div class="btn--container justify-content-center">
            <button onclick="edit_shift('{{$shift['id']}}')"
            data-toggle="modal"   data-target="#add_update_shift_{{$shift->id}}" class="btn btn-sm btn--primary btn-outline-primary action-btn">
                <i class="tio-edit"></i>
            </button>
            <a class="btn btn-sm btn--danger btn-outline-danger action-btn" href="javascript:"
            onclick="form_alert('shift-{{$shift['id']}}','{{ translate('Want to delete this shift data. All of data related to this shift will be gone !!!') }}')" title="{{translate('messages.delete')}} {{translate('messages.shift')}}">
            <i class="tio-delete-outlined"></i>
            </a>
            <form action="{{route('admin.shift.delete',[$shift['id']])}}" method="post" id="shift-{{$shift['id']}}">
                @csrf @method('delete')
            </form>
        </div>
    </td>
</tr>





    <!-- Modal -->
    <div class="modal fade" id="add_update_shift_{{$shift->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{translate('messages.shift_update')}}  </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>

            </div>
            <div class="modal-body">
                <form  action="javascript:" id="system-form-update"   method="post">
                    @csrf
                    @method('post')


                    {{-- @method('put') --}}
                    <input type="hidden" name="id" value="{{$shift->id}}" id="id" />

                    @php($shift=  \App\Models\Shift::withoutGlobalScope('translate')->with('translations')->find($shift->id))
                    @php($language=\App\Models\BusinessSetting::where('key','language')->first())
                @php($language = $language->value ?? null)
                @php($default_lang = str_replace('_', '-', app()->getLocale()))
                <ul class="nav nav-tabs nav--tabs mb-3 border-0">
                    <li class="nav-item">
                        <a class="nav-link lang_link add_active active"
                        href="#" onclick="show_form_def('default','{{$shift->id}}')"
                        id="default-link">{{ translate('Default') }}</a>
                    </li>
                    @if($language)
                    @foreach (json_decode($language) as $lang)
                        <li class="nav-item">
                            <a class="nav-link lang_link"
                                href="#" onclick="show_form_lang('{{$lang}}','{{$shift->id}}')"
                                id="{{ $lang }}-link">{{ \App\CentralLogics\Helpers::get_language_name($lang) . '(' . strtoupper($lang) . ')' }}</a>
                        </li>
                    @endforeach
                    @endif
                </ul>


                    <div class="form-group add_active_2  lang_form" id="default-form_{{$shift->id}}">
                        <label class="input-label" for="exampleFormControlInput1">{{translate('messages.name')}}  ({{translate('messages.default')}})</label>
                        <input class="form-control" name='name[]' value="{{$shift->getRawOriginal('name')}}"  type="text">
                        <input type="hidden" name="lang1[]" value="default">
                    </div>
                    @if($language)
                    @forelse(json_decode($language) as $lang)
                    <?php
                        if($shift?->translations){
                            $translate = [];
                            foreach($shift?->translations as $t)
                            {
                                if($t->locale == $lang && $t->key=="name"){
                                    $translate[$lang]['name'] = $t->value;
                                }
                            }
                        }

                        ?>
                        <div class="form-group d-none lang_form" id="{{$lang}}-form_{{$shift->id}}">
                            <label class="input-label" for="exampleFormControlInput1">{{translate('messages.name')}} ({{strtoupper($lang)}}) </label>
                            <input class="form-control" name='name[]' value="{{ $translate[$lang]['name'] ?? null }}"  type="text">
                            <input type="hidden" name="lang1[]" value="{{$lang}}">
                        </div>
                        @empty
                        @endforelse
                        @endif



                    <br>
                    <div class="form-group">
                        <label for="start_time" class="mb-2">{{ translate('messages.Start_Time') }}</label>
                        <input type="time"  required   name="start_time" value="{{ $shift->start_time }}" class="form-control">
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="end_time" class="mb-2">{{ translate('End_Time') }}</label>
                        <input type="time" required   name="end_time" value="{{ $shift->end_time }}" class="form-control" >
                    </div>
                    <br>

                </div>
                <div class="modal-footer">
                    <button id="reset_btn" type="reset" data-dismiss="modal" class="btn btn-secondary" >{{ translate('Close') }} </button>
                    <button class="btn btn-primary" type="submit">{{ translate('Submit') }}</button>
                </form>
            </div>
        </div>
        </div>
    </div>








@empty

@endforelse
