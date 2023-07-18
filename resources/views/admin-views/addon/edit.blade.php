@extends('layouts.admin.app')

@section('title',translate('update'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h2 class="page-header-title text-capitalize">
                        <div class="card-header-icon d-inline-flex mr-2 img">
                            <img src="{{asset('/public/assets/admin/img/addon.png')}}" alt="public">
                        </div>
                        <span>
                            {{translate('messages.addon')}} {{translate('messages.update')}}
                        </span>
                    </h2>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="card">
            <div class="card-body">
                <form action="{{route('admin.addon.update',[$addon['id']])}}" method="post">
                    @csrf
                    @php($language=\App\Models\BusinessSetting::where('key','language')->first())
                    @php($language = $language->value ?? null)
                    @php($default_lang = str_replace('_', '-', app()->getLocale()))

                    @if($language)
                        <ul class="nav nav-tabs mb-4">
                            <li class="nav-item">
                                <a class="nav-link lang_link active " href="#" id="default-link">{{ translate('Default')}}</a>
                            </li>
                            @foreach(json_decode($language) as $lang)
                                <li class="nav-item">
                                    <a class="nav-link lang_link " href="#" id="{{$lang}}-link">{{\App\CentralLogics\Helpers::get_language_name($lang).'('.strtoupper($lang).')'}}</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                    <div class="row">
                        <div class="col-sm-6 col-md-4">
                        @if ($language)
                        <div class="form-group lang_form" id="default-form">
                            <label class="input-label" for="exampleFormControlInput1">{{translate('messages.name')}}</label>
                            <input type="text" name="name[]" class="form-control" placeholder="{{translate('messages.new_addon')}}" value="{{ $addon?->getRawOriginal('name') }}"  maxlength="191">
                        </div>
                        <input type="hidden" name="lang[]" value="default">
                            @foreach(json_decode($language) as $lang)
                                <?php
                                    if(count($addon['translations'])){
                                        $translate = [];
                                        foreach($addon['translations'] as $t)
                                        {
                                            if($t->locale == $lang && $t->key=="name"){
                                                $translate[$lang]['name'] = $t->value;
                                            }
                                        }
                                    }
                                ?>
                                <div class="form-group d-none lang_form" id="{{$lang}}-form">
                                    <label class="input-label" for="exampleFormControlInput1">{{translate('messages.name')}} ({{strtoupper($lang)}})</label>
                                    <input type="text" name="name[]" class="form-control" placeholder="{{translate('messages.new_addon')}}" maxlength="191" value="{{$translate[$lang]['name'] ??''}}" oninvalid="document.getElementById('en-link').click()">
                                </div>
                                <input type="hidden" name="lang[]" value="{{$lang}}">
                            @endforeach
                        @else
                            <div class="form-group lang_form" id="default-form">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('messages.name')}}</label>
                                <input type="text" name="name[]" class="form-control" placeholder="{{translate('messages.new_addon')}}" value="{{ $addon['name'] }}"  maxlength="191">
                            </div>
                            <input type="hidden" name="lang[]" value="default">
                        @endif
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlSelect1">{{translate('messages.restaurant')}}<span
                                        class="input-label-secondary"></span></label>
                                <select name="restaurant_id" id="restaurant_id" class="form-control  js-data-example-ajax"  data-placeholder="{{translate('messages.select')}} {{translate('messages.restaurant')}}" required oninvalid="this.setCustomValidity('{{translate('messages.please_select_restaurant')}}')">
                                @if($addon->restaurant)
                                <option value="{{$addon->restaurant_id}}" selected="selected">{{$addon->restaurant->name}}</option>
                                @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('messages.price')}}</label>
                                <input type="number" min="0" max="999999999999.99" step="0.01" name="price" value="{{$addon['price']}}" class="form-control" placeholder="200" required>
                            </div>
                        </div>
                    </div>

                    <div class="btn--container justify-content-end">
                        <button type="reset" id="reset_btn" class="btn btn--reset">{{translate('messages.reset')}}</button>
                        <button type="submit" class="btn btn--primary">{{translate('messages.update')}}</button>
                    </div>
                </form>
            </div>
            <!-- End Table -->
        </div>
    </div>

@endsection

@push('script_2')
<script>
    $(".lang_link").click(function(e){
        e.preventDefault();
        $(".lang_link").removeClass('active fadeIn');
        $(".lang_form").addClass('d-none');
        $(this).addClass('active fadeIn');

        let form_id = this.id;
        let lang = form_id.substring(0, form_id.length - 5);
        $("#"+lang+"-form").removeClass('d-none');
        if(lang == '{{$default_lang}}')
        {
            $(".from_part_2").removeClass('d-none');
        }
        else
        {
            $(".from_part_2").addClass('d-none');
        }
    });
</script>
<script>
    $('.js-data-example-ajax').select2({
        ajax: {
            url: '{{url('/')}}/admin/restaurant/get-restaurants',
            data: function (params) {
                return {
                    q: params.term, // search term
                    page: params.page
                };
            },
            processResults: function (data) {
                return {
                results: data
                };
            },
            __port: function (params, success, failure) {
                var $request = $.ajax(params);

                $request.then(success);
                $request.fail(failure);

                return $request;
            }
        }
    });

    $('#reset_btn').click(function(){
            $('#restaurant_id').val("{{$addon->restaurant_id}}").trigger('change');
        })
</script>
@endpush
