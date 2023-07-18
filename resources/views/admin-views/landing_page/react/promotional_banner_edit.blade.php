@extends('layouts.admin.app')

@section('title',translate('messages.Admin Landing Page'))

@section('content')
<div class="content container-fluid">
    <div class="page-header pb-0">
        <div class="d-flex flex-wrap justify-content-between">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{ asset('/public/assets/admin/img/landing-page.png') }}" class="mw-26px" alt="public">
                </span>
                <span>
                    {{ translate('React Landing Page') }}
                </span>
            </h1>

        </div>
    </div>
    <div class="js-nav-scroller hs-nav-scroller-horizontal">
        @include('admin-views.landing_page.top_menu.react_landing_menu')
    </div>
    @php($language=\App\Models\BusinessSetting::where('key','language')->first())
    @php($language = $language->value ?? null)
    @php($default_lang = str_replace('_', '-', app()->getLocale()))
    <br>
    <ul class="nav nav-tabs mb-4 border-0">
        <li class="nav-item">
            <a class="nav-link lang_link active"
            href="#"
            id="default-link">{{translate('messages.default')}}</a>
        </li>
        @foreach (json_decode($language) as $lang)
            <li class="nav-item">
                <a class="nav-link lang_link"
                    href="#"
                    id="{{ $lang }}-link">{{ \App\CentralLogics\Helpers::get_language_name($lang) . '(' . strtoupper($lang) . ')' }}</a>
            </li>
        @endforeach
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade show active">
            <form action="{{ route('admin.react_landing_page.promotional_banner_update',[$react_promotional_banner->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <h5 class="card-title mb-3 mt-3">
                    <span class="card-header-icon mr-2">
                        {{-- <i class="tio-settings-outlined"></i> --}}
                    </span>
                     <span>{{translate('messages.promotional_banner_edit')}}</span>
                </h5>
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-end">

                        </div>
                        <div class="row g-3">

                            <div class="col-md-6 lang_form" id="default-form">
                                <input type="hidden" name="lang[]" value="default">

                                    <div class="form-group">
                                        <label class="form-label">{{translate('title')}} ({{ translate('messages.default') }})
                                        <span class="input-label-secondary text--title" data-toggle="tooltip"
                                        data-placement="right"
                                        data-original-title="{{ translate('Write_the_title_within_20_characters') }}">
                                            <i class="tio-info-outined"></i>
                                        </span>
                                        </label>
                                        <input type="text" maxlength="20" name="title[]"    value="{{ $react_promotional_banner?->getRawOriginal('title') }}" class="form-control" placeholder="{{translate('Ex:  John Doe')}}">
                                    </div>

                                    <div class="form-group mb-0">
                                        <label class="form-label">{{translate('messages.short_description')}} ({{ translate('messages.default') }})
                                        <span class="input-label-secondary text--title" data-toggle="tooltip"
                                        data-placement="right"
                                        data-original-title="{{ translate('Write_a_short_description_within_50_characters') }}">
                                            <i class="tio-info-outined"></i>
                                        </span>
                                        </label>
                                        <textarea name="description[]" maxlength="50"  class="form-control h-84px" placeholder="{{translate('Very Good Company')}}" cols="30" rows="10"> {{ $react_promotional_banner?->getRawOriginal('description') }} </textarea>
                                    </div>
                            </div>


                            @forelse(json_decode($language) as $lang)

                            <?php
                                if($react_promotional_banner?->translations){
                                    $translate = [];
                                    foreach($react_promotional_banner?->translations as $t)
                                    {
                                        if($t->locale == $lang && $t->key=="react_promotional_banner_title"){
                                            $translate[$lang]['react_promotional_banner_title'] = $t->value;
                                        }
                                        if($t->locale == $lang && $t->key=="react_promotional_banner_description"){
                                            $translate[$lang]['react_promotional_banner_description'] = $t->value;
                                        }
                                    }
                                }

                                ?>

                        <div class="col-md-6 d-none lang_form" id="{{$lang}}-form1">
                                <div class="form-group">
                                    <label class="form-label">{{translate('Title')}} ({{strtoupper($lang)}})
                                     <span class="input-label-secondary text--title" data-toggle="tooltip"
                                        data-placement="right"
                                        data-original-title="{{ translate('Write_the_title_within_20_characters') }}">
                                            <i class="tio-info-outined"></i>
                                        </span>
                                    </label>
                                    <input type="text" maxlength="20" name="title[]" value="{{ $translate[$lang]['react_promotional_banner_title']??'' }}" class="form-control" placeholder="{{translate('messages.name_here...')}}">
                                </div>
                                <div class="form-group mb-0">
                                    <label class="form-label">{{translate('Short_description')}} ({{strtoupper($lang)}})
                                    <span class="input-label-secondary text--title" data-toggle="tooltip"
                                        data-placement="right"
                                        data-original-title="{{ translate('Write_a_short_description_within_50_characters') }}">
                                            <i class="tio-info-outined"></i>
                                        </span>
                                    </label>
                                        <textarea name="description[]" maxlength="50"  class="form-control h-84px" placeholder="{{translate('Very Good Company')}}" cols="30" rows="10"> {{ $translate[$lang]['react_promotional_banner_description']??''}} </textarea>
                                    </div>
                            </div>
                            <input type="hidden" name="lang[]" value="{{$lang}}">
                            @empty
                            @endforelse
                            <div class="col-md-6">
                                <div class="d-flex gap-40px">
                                    <div>
                                        <label class="form-label d-block mb-2">
                                            {{translate('messages.Icon')}}   <span class="text--primary">{{translate('messages.(3:1)')}} *</span>
                                        </label>
                                        {{-- <div class="position-relative"> --}}

                                        <label class="upload-img-3 m-0 d-block">
                                            <div class="img">
                                                <img src="{{asset('storage/app/public/react_promotional_banner')}}/{{$react_promotional_banner?->image}}" onerror="this.src='{{asset('/public/assets/admin/img/upload-3.png')}}'" class="vertical-img max-w-187px" alt="">
                                            </div>
                                            <input type="file"  required  name="image" hidden="">
                                        </label>
                                        {{-- @if ($react_promotional_banner?->image)
                                        <span id="remove_image_1" class="remove_image_button"
                                            onclick="toogleStatusModal(event,'remove_image_1','mail-success','mail-warninh','{{translate('Important!')}}','{{translate('Warning!')}}',`<p>{{translate('Are_you_sure_you_want_to_remove_this_image')}}</p>`,`<p>{{translate('Are_you_sure_you_want_to_remove_this_image.')}}</p>`)"
                                            > <i class="tio-clear"></i></span>
                                        @endif --}}
                                    {{-- </div> --}}
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="btn--container justify-content-end mt-3">
                            <button type="reset" class="btn btn--reset">{{translate('Reset')}}</button>
                            <button type="submit" onclick="" class="btn btn--primary mb-2">{{translate('messages.Update')}}</button>
                        </div>
                    </div>
                </div>

            </form>




        </div>
    </div>
</div>
    {{-- <form  id="remove_image_1_form" action="{{ route('admin.remove_image') }}" method="post">
        @csrf
        <input type="hidden" name="id" value="{{  $react_promotional_banner?->id}}" >
        <input type="hidden" name="model_name" value="ReactPromotionalBanner" >
        <input type="hidden" name="image_path" value="react_promotional_banner" >
        <input type="hidden" name="field_name" value="image" >
    </form> --}}


@endsection
@push('script_2')
<script>
    $(".lang_link").click(function(e){
        e.preventDefault();
        $(".lang_link").removeClass('active');
        $(".lang_form").addClass('d-none');
        $(this).addClass('active');

        let form_id = this.id;
        let lang = form_id.substring(0, form_id.length - 5);

        console.log(lang);

        $("#"+lang+"-form").removeClass('d-none');
        $("#"+lang+"-form1").removeClass('d-none');
        if(lang == '{{$default_lang}}')
        {
            $(".from_part_2").removeClass('d-none');
        }
        if(lang == 'default')
        {
            $(".default-form").removeClass('d-none');
        }
        else
        {
            $(".from_part_2").addClass('d-none');
        }
    });
</script>
@endpush
