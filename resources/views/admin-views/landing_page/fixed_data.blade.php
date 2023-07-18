@extends('layouts.admin.app')

@section('title', translate('messages.landing_page_settings'))

@push('css_or_js')
    <!-- Custom styles for this page -->
    <link href="{{ asset('public/assets/admin/css/croppie.css') }}" rel="stylesheet">
@endpush

@section('content')

<div class="content container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex flex-wrap justify-content-between align-items-start">
            <!-- Page Header -->
            <h1 class="page-header-title text-capitalize">
                <div class="card-header-icon d-inline-flex mr-2 img">
                    <img src="{{ asset('/public/assets/admin/img/landing-page.png') }}" class="mw-26px" alt="public">
                </div>
                <span>
                    {{ translate('Admin Landing Page') }}
                </span>
            </h1>
            <div class="text--primary-2 py-1 d-flex flex-wrap align-items-center" type="button" data-toggle="modal" data-target="#how-it-works">
                <strong class="mr-2">{{translate('See_how_it_works')}}</strong>
                <div>
                    <i class="tio-info-outined"></i>
                </div>
            </div>
        </div>
        <!-- End Nav Scroller -->
        <div class="js-nav-scroller hs-nav-scroller-horizontal">
            @include('admin-views.landing_page.top_menu.admin_landing_menu')
        </div>
    </div>
        <!-- Choose -->

        @php($default_lang = str_replace('_', '-', app()->getLocale()))
        @if($language)
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
            <div class="d-flex justify-content-between __gap-12px mb-3">
                <h5 class="card-title d-flex align-items-center">
                    <span class="card-header-icon mr-2">
                        <img src="{{asset('public/assets/admin/img/fixed_data2.png')}}" alt="" class="mw-100">
                    </span>
                    {{translate('Newsletter')}}
                </h5>
                {{-- <div class="text--primary-2 py-1 d-flex flex-wrap align-items-center" type="button" data-toggle="modal" data-target="#section_view">
                    <strong class="mr-2">{{translate('Section View')}}</strong>
                    <div>
                        <i class="tio-intersect"></i>
                    </div>
                </div> --}}
            </div>
            <div class="card">
                <form action="{{ route('admin.landing_page.settings', 'fixed-data-newsletter') }}" method="post">
                    @csrf
                <div class="card-body">
                    <div class="row g-3 lang_form default-form" id="default-form">
                        <input type="hidden" name="lang[]" value="default">
                        <div class="col-sm-6">
                            <label class="form-label">{{translate('Title')}} ({{ translate('messages.default') }})
                              <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_title_within_30_characters') }}">
                            <i class="tio-info-outined"></i>
                        </span>
                            </label>
                            <input type="text" maxlength="30"  name="title[]" value="{{ $news_letter_title?->getRawOriginal('value') ?? null}}" class="form-control" placeholder="{{translate('Enter Title')}}">
                            <input type="hidden" name="key" value="news_letter_title" >
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">{{translate('Subtitle')}} ({{ translate('messages.default') }})
                              <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_subtitle_within_70_characters') }}">
                            <i class="tio-info-outined"></i>
                        </span>
                            </label>
                            <input type="text" maxlength="70"  name="sub_title[]" value="{{ $news_letter_sub_title?->getRawOriginal('value') ?? null}}"  class="form-control" placeholder="{{translate('Enter_Sub_Title')}}">
                            <input type="hidden" name="key_2" value="news_letter_sub_title" >
                        </div>
                    </div>

                    @forelse(json_decode($language) as $lang)
                    <?php
                    if($news_letter_title?->translations){
                            $news_letter_title_translate = [];
                            foreach($news_letter_title->translations as $t)
                            {
                                if($t->locale == $lang && $t->key=='news_letter_title'){
                                    $news_letter_title_translate[$lang]['value'] = $t->value;
                                }
                            }
                        }
                    if($news_letter_sub_title?->translations){
                            $news_letter_sub_title_translate = [];
                            foreach($news_letter_sub_title->translations as $t)
                            {
                                if($t->locale == $lang && $t->key=='news_letter_sub_title'){
                                    $news_letter_sub_title_translate[$lang]['value'] = $t->value;
                                }
                            }
                        }
                        ?>

                    <div class="row g-3 d-none lang_form" id="{{$lang}}-form">
                        <input type="hidden" name="lang[]" value="{{$lang}}">
                        <div class="col-sm-6">
                            <label class="form-label">{{translate('Title')}} ({{strtoupper($lang)}})
                             <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_title_within_30_characters') }}">
                            <i class="tio-info-outined"></i>
                        </span>
                            </label>
                            <input type="text" name="title[]"   maxlength="30" value="{{ $news_letter_title_translate[$lang]['value'] ?? '' }}" class="form-control" placeholder="{{translate('Enter Title')}}">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">{{translate('Subtitle')}} ({{strtoupper($lang)}})
                             <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_subtitle_within_70_characters') }}">
                            <i class="tio-info-outined"></i>
                        </span>
                            </label>
                            <input type="text" name="sub_title[]" maxlength="70" value="{{ $news_letter_sub_title_translate[$lang]['value'] ?? '' }}" class="form-control" placeholder="{{translate('Enter Title')}}">
                        </div>
                    </div>
                    @empty
                    @endforelse

                    <div class="btn--container justify-content-end mt-3">
                        <button type="reset" class="btn btn--reset">{{translate('Reset')}}</button>
                        <button type="submit" onclick="" class="btn btn--primary mb-2">{{translate('Save')}}</button>
                    </div>
                </form>
                </div>
            </div>
            <br>
            <br>
            <div class="d-flex justify-content-between __gap-12px mb-3">
                <h5 class="card-title d-flex align-items-center">
                    <span class="card-header-icon mr-2">
                        <img src="{{asset('public/assets/admin/img/fixed_data1.png')}}" alt="" class="mw-100">
                    </span>
                    {{translate('Footer_Short_Description')}}
                </h5>

            </div>





            <div class="card">
                <form action="{{ route('admin.landing_page.settings', 'fixed-data-footer') }}" method="post">
                    @csrf
                <div class="card-body">
                    <div class="row g-3 lang_form default-form" >
                        <input type="hidden" name="lang[]" value="default">
                        {{-- <div class="col-sm-12">
                            <div class="form-group">
                                <label class="form-label">{{translate('messages.copyright_text')}} ({{ translate('messages.default') }}) </label>
                                <input type="hidden" name="key_copyright" value="copyright_text" >
                                <input type="text"  name="copyright_text[]" value="{{ $copyright_text?->getRawOriginal('value') ?? null}}" class="form-control" placeholder="{{translate('messages.copyright')}}">
                            </div>
                        </div> --}}
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="form-label">{{translate('messages.Footer_description')}} ({{ translate('messages.default') }})
                                    <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_footer_description_within_300_characters') }}">
                                        <i class="tio-info-outined"></i>
                                    </span>

                                </label>
                                <input type="hidden" name="footer_key" value="footer_data" >
                                <textarea rows="5" maxlength="300"   class="form-control" name="footer_data[]" placeholder="{{translate('messages.Short Description')}}">{{ $footer_data?->getRawOriginal('value') ?? null}}</textarea>
                            </div>
                        </div>
                    </div>

                    @forelse(json_decode($language) as $lang)
                        <input type="hidden" name="lang[]" value="{{$lang}}">
                        <?php
                            if($footer_data?->translations){
                                    $footer_data_translate = [];
                                    foreach($footer_data->translations as $t)
                                    {
                                        if($t->locale == $lang && $t->key=='footer_data'){
                                            $footer_data_translate[$lang]['value'] = $t->value;
                                        }
                                    }
                                }
                            // if($copyright_text?->translations){
                            //         $copyright_text_translate = [];
                            //         foreach($copyright_text->translations as $t)
                            //         {
                            //             if($t->locale == $lang && $t->key=='copyright_text'){
                            //                 $copyright_text_translate[$lang]['value'] = $t->value;
                            //             }
                            //         }
                            //     }
                            ?>
                        <div class="row g-3  d-none lang_form" id="{{$lang}}-form1">
                            {{-- <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="form-label">{{translate('messages.copyright_text')}} ({{strtoupper($lang)}}) </label>
                                    <input type="text" name="copyright_text[]" value="{{ $copyright_text_translate[$lang]['value'] ?? '' }}" class="form-control" placeholder="{{translate('messages.copyright')}}">
                                </div>
                            </div> --}}
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="form-label">{{translate('messages.Footer_description')}} ({{strtoupper($lang)}})
                                        <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_footer_description_within_300_characters') }}">
                                            <i class="tio-info-outined"></i>
                                        </span> </label>
                                    <textarea rows="5" class="form-control" maxlength="300" name="footer_data[]" placeholder="{{translate('messages.Short Description')}}">{{ $footer_data_translate[$lang]['value'] ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>
                        @empty
                    @endforelse
                    <div class="btn--container justify-content-end mt-3">
                        <button type="reset" class="btn btn--reset">{{translate('Reset')}}</button>
                        <button type="submit" onclick="" class="btn btn--primary mb-2">{{translate('Save')}}</button>
                    </div>
                </div>
            </form>
            </div>
        </div>
    <!-- Earn Money -->
</div>
</div>

<!--  Section View -->
<div class="modal fade" id="section_view">
    <div class="modal-dialog modal-lg warning-modal">
        <div class="modal-content">
            <div class="modal-body">
                <div class="mb-3">
                    <h3 class="modal-title mb-3">{{translate(' Special Criteria')}}</h3>
                </div>
                <img src="{{asset('/public/assets/admin/img/zone-instruction.png')}}" alt="admin/img" class="w-100">
            </div>
        </div>
    </div>
</div>

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
