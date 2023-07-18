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
                        {{ translate('React Landing Page') }}
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
                @include('admin-views.landing_page.top_menu.react_landing_menu')
            </div>
        </div>

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



        <form action="{{ route('admin.react_landing_page.settings', 'react-header') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <h5 class="card-title d-felx align-items-center mr-2 mb-2">
                <span class="card-header-icon mr-2">
                    <img src="{{asset('public/assets/admin/img/react_header.png')}}" alt="" class="mw-100">
                </span>
                <span>{{translate('messages.Header_Section')}}</span>
            </h5>
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-end">
                        {{-- <div class="text--primary-2 py-1 d-flex flex-wrap align-items-center" type="button" data-toggle="modal" data-target="#section_view">
                            <strong class="mr-2">{{translate('Section View')}}</strong>
                            <div>
                                <i class="tio-intersect"></i>
                            </div>
                        </div> --}}
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6 lang_form default-form">
                            <div class="row g-3">
                                <input type="hidden" name="lang[]" value="default">
                                <div class="col-md-12">
                                    <label class="form-label">{{translate('Title')}} ({{ translate('messages.default') }})

                                      <span class="input-label-secondary text--title" data-toggle="tooltip"
                                        data-placement="right"
                                        data-original-title="{{ translate('Write_the_title_within_20_characters') }}">
                                            <i class="tio-info-outined"></i>
                                        </span>
                                    </span>
                                    </label>
                                    <input maxlength="20" type="text" name="react_header_title[]"  class="form-control" placeholder="{{translate('Ex:  John Doe')}}"
                                    value="{{ $react_header_title?->getRawOriginal('value') ?? '' }}">
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label">{{translate('messages.Subtitle')}} ({{ translate('messages.default') }})

                                      <span class="input-label-secondary text--title" data-toggle="tooltip"
                                        data-placement="right"
                                        data-original-title="{{ translate('Write_the_subtitle_within_50_characters') }}">
                                            <i class="tio-info-outined"></i>
                                        </span>
                                    </span>
                                    </label>
                                    <input maxlength="50" type="text" name="react_header_sub_title[]"  placeholder="{{translate('Very Good Company')}}"  class="form-control"
                                    value="{{ $react_header_sub_title?->getRawOriginal('value') ?? '' }}">
                                </div>
                            </div>
                        </div>

                        @if($language)
                        @forelse(json_decode($language) as $lang)
                        <div class="col-md-6 d-none lang_form" id="{{$lang}}-form1">

                            <?php
                            if($react_header_title?->translations){
                                    $react_header_title_translate = [];
                                    foreach($react_header_title->translations as $t)
                                    {
                                        if($t->locale == $lang && $t->key=='react_header_title'){
                                            $react_header_title_translate[$lang]['value'] = $t->value;
                                        }
                                    }
                                }
                            if($react_header_sub_title?->translations){
                                    $react_header_sub_title_translate = [];
                                    foreach($react_header_sub_title->translations as $t)
                                    {
                                        if($t->locale == $lang && $t->key=='react_header_sub_title'){
                                            $react_header_sub_title_translate[$lang]['value'] = $t->value;
                                        }
                                    }
                                }

                                ?>
                            <div class="row g-3">
                                <input type="hidden" name="lang[]" value="{{$lang}}">
                                <div class="col-md-12">
                                    <label class="form-label">{{translate('Title')}} ({{strtoupper($lang)}})

                                      <span class="input-label-secondary text--title" data-toggle="tooltip"
                                        data-placement="right"
                                        data-original-title="{{ translate('Write_the_title_within_20_characters') }}">
                                            <i class="tio-info-outined"></i>
                                        </span>
                                    </span>
                                    </label>
                                    <input maxlength="20" type="text" name="react_header_title[]" class="form-control" placeholder="{{translate('Ex:  John Doe')}}" value="{{ $react_header_title_translate[$lang]['value'] ?? '' }}">
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label">{{translate('messages.Subtitle')}} ({{strtoupper($lang)}})

                                      <span class="input-label-secondary text--title" data-toggle="tooltip"
                                        data-placement="right"
                                        data-original-title="{{ translate('Write_the_subtitle_within_50_characters') }}">
                                            <i class="tio-info-outined"></i>
                                        </span>
                                    </span>
                                    </label>

                                    <input type="text" maxlength="50"  name="react_header_sub_title[]" placeholder="{{translate('Very Good Company')}}"  class="form-control"
                                    value="{{ $react_header_sub_title_translate[$lang]['value'] ?? '' }}">

                                </div>
                            </div>
                        </div>
                        @empty
                        @endforelse
                        @endif


                        <div class="col-sm-6">
                            <div class="ml-xl-5 pl-xxl-4">
                                <label class="form-label d-block mb-2">
                                    {{translate('messages.Section_Background_Image')}}  <span class="text--primary">{{translate('(4:1)')}}</span>
                                </label>
                                <div class="d-inline-block position-relative">

                                <label class="upload-img-3 m-0 d-block">
                                    <div class="img">
                                        <img src="{{asset('storage/app/public/react_header')}}/{{$react_header_image?->value ?? ''}}" onerror='this.src="{{asset('/public/assets/admin/img/upload-3.png')}}"' class="vertical-img max-w-187px" alt="">
                                    </div>
                                        <input type="file" name="react_header_image" hidden>
                                </label>
                                @if ($react_header_image?->value)
                                <span id="remove_image_1" class="remove_image_button"
                                    onclick="toogleStatusModal(event,'remove_image_1','mail-success','mail-warninh','{{translate('Important!')}}','{{translate('Warning!')}}',`<p>{{translate('Are_you_sure_you_want_to_remove_this_image')}}</p>`,`<p>{{translate('Are_you_sure_you_want_to_remove_this_image.')}}</p>`)"
                                    > <i class="tio-clear"></i></span>
                                @endif
                            </div>
                            </div>
                        </div>


                    </div>
                    <div class="btn--container justify-content-end mt-3">
                        <button type="reset" class="btn btn--reset">{{translate('Reset')}}</button>
                        <button type="submit" onclick="" class="btn btn--primary mb-2">{{translate('Save')}}</button>
                    </div>
                </form>
                </div>

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




    <form  id="remove_image_1_form" action="{{ route('admin.remove_image') }}" method="post">
        @csrf
        <input type="hidden" name="id" value="{{  $react_header_image?->id}}" >
        <input type="hidden" name="model_name" value="DataSetting" >
        <input type="hidden" name="image_path" value="react_header" >
        <input type="hidden" name="field_name" value="value" >
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
