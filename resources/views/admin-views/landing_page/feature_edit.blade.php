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
                    {{ translate('messages.Admin Landing Page') }}
                </span>
            </h1>

        </div>
    </div>
    <div class="js-nav-scroller hs-nav-scroller-horizontal">
        @include('admin-views.landing_page.top_menu.admin_landing_menu')
    </div>
    @php($language=\App\Models\BusinessSetting::where('key','language')->first())
    @php($language = $language->value ?? null)
    @php($default_lang = str_replace('_', '-', app()->getLocale()))
    <br>
    @if ($language)
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
    @endif
    <div class="tab-content">
        <div class="tab-pane fade show active">
            <form action="{{ route('admin.landing_page.feature_update',[$feature->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <h5 class="card-title mb-3 mt-3">
                    <span class="card-header-icon mr-2">
                        <img src="{{asset('public/assets/admin/img/react_header.png')}}" alt="" class="mw-100">
                    </span>

                <span>{{translate('messages.Feature_List')}}</span>
                </h5>
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-end">

                        </div>
                        <div class="row g-3">
                            <div class="col-md-6 lang_form" id="default-form">
                                <input type="hidden" name="lang[]" value="default">
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label class="form-label">{{translate('messages.Title')}}
                                         <span class="input-label-secondary text--title"  data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_title_within_20_characters') }}">
                                            <i class="tio-info-outined"></i>
                                        </span>
                                        </label>
                                        <input type="text" name="name[]" maxlength="20"     value="{{ $feature?->getRawOriginal('title') }}" class="form-control" placeholder="{{translate('Ex:  John Doe')}}">
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label">{{translate('messages.Short_Description')}}
                                          <span class="input-label-secondary text--title"  data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_short_description_within_60_characters') }}">
                                            <i class="tio-info-outined"></i>
                                        </span>
                                        </label>
                                        <textarea name="description[]" maxlength="60" placeholder="{{translate('Very Good Company')}}" class="form-control h92px">{{ $feature?->getRawOriginal('description') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            @if ($language)
                            @forelse(json_decode($language) as $lang)
                            <?php
                                if($feature?->translations){
                                    $translate = [];
                                    foreach($feature?->translations as $t)
                                    {
                                        if($t->locale == $lang && $t->key=="feature_name"){
                                            $translate[$lang]['feature_name'] = $t->value;
                                        }
                                        if($t->locale == $lang && $t->key=="feature_description"){
                                            $translate[$lang]['feature_description'] = $t->value;
                                        }
                                    }
                                }

                                ?>

                        <div class="col-md-6 d-none lang_form" id="{{$lang}}-form1">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">{{translate('messages.Title')}} ({{strtoupper($lang)}})
                                     <span class="input-label-secondary text--title"  data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_title_within_20_characters') }}">
                                            <i class="tio-info-outined"></i>
                                        </span>
                                    </label>
                                    <input type="text" name="name[]" maxlength="20"  value="{{ $translate[$lang]['feature_name']??'' }}" class="form-control" placeholder="{{translate('messages.name_here...')}}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">{{translate('messages.Short_Description')}} ({{strtoupper($lang)}})
                                      <span class="input-label-secondary text--title"  data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_short_description_within_60_characters') }}">
                                            <i class="tio-info-outined"></i>
                                        </span>
                                    </label>
                                        <textarea name="description[]" maxlength="60" placeholder="{{translate('Very Good Company')}}" class="form-control h92px">{{ $translate[$lang]['feature_description']??'' }}</textarea>
                                    </div>
                            </div>
                        </div>
                            <input type="hidden" name="lang[]" value="{{$lang}}">
                            @empty
                            @endforelse
                            @endif
                            <div class="col-md-6">
                                <div class="d-flex gap-40px">
                                    <div>
                                        <label class="form-label d-block mb-2">
                                            {{translate('messages.Feature_Image')}}  <span class="text--primary">(1:1)</span>
                                        </label>
                                        <div class="position-relative">

                                        <label class="upload-img-3 m-0 d-block">
                                            <div class="img">
                                                <img src="{{asset('storage/app/public/feature_image')}}/{{$feature->image}}" onerror="this.src='{{asset("/public/assets/admin/img/aspect-1.png")}}'" class="vertical-img max-w-187px" alt="">
                                            </div>
                                            <input type="file"   name="feature_image" hidden="">
                                        </label>
                                        @if (isset($feature->image ))
                                        <span id="remove_image" class="remove_image_button"
                                            onclick="toogleStatusModal(event,'remove_image','mail-success','mail-warninh','{{translate('Important!')}}','{{translate('Warning!')}}',`<p>{{translate('Are_you_sure_you_want_to_remove_this_image')}}</p>`,`<p>{{translate('Are_you_sure_you_want_to_remove_this_image.')}}</p>`)"
                                            > <i class="tio-clear"></i></span>
                                        @endif
                                    </div>
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



    <form  id="remove_image_form" action="{{ route('admin.remove_image') }}" method="post">
        @csrf
            <input type="hidden" name="id" value="{{  $feature?->id}}" >
            {{-- <input type="hidden" name="json" value="1" > --}}
            <input type="hidden" name="model_name" value="AdminFeature" >
            <input type="hidden" name="image_path" value="feature_image" >
            <input type="hidden" name="field_name" value="image" >
        </form>
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
