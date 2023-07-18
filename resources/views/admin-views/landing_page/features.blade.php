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


        <form action="{{ route('admin.landing_page.settings', 'features-title-section') }}" method="POST">
            @csrf
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row g-3 lang_form" id="default-form">
                        <div class="col-sm-6">
                            <label class="form-label">{{translate('Title')}}
                                <span class="input-label-secondary text--title"  data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_title_within_20_characters') }}">
                                    <i class="tio-info-outined"></i>
                                </span>
                            </label>
                            <input type="text" maxlength="20"   name="feature_title[]" class="form-control" value="{{$feature_title?->getRawOriginal('value')??''}}" placeholder="{{translate('messages.title_here...')}}">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">{{translate('Subtitle')}}
                                <span class="input-label-secondary text--title"  data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_subtitle_within_70_characters') }}">
                                    <i class="tio-info-outined"></i>
                                </span>
                            </label>
                            <input type="text" maxlength="70"  name="feature_sub_title[]" class="form-control" value="{{$feature_sub_title?->getRawOriginal('value')??''}}" placeholder="{{translate('messages.sub_title_here...')}}">
                        </div>
                    </div>
                    <input type="hidden" name="lang[]" value="default">
                    @if ($language)
                            @foreach(json_decode($language) as $lang)
                            <?php
                            if($feature_title?->translations){
                                    $feature_title_translate = [];
                                    foreach($feature_title->translations as $t)
                                    {
                                        if($t->locale == $lang && $t->key=='feature_title'){
                                            $feature_title_translate[$lang]['value'] = $t->value;
                                        }
                                    }
                                }
                            if($feature_sub_title?->translations){
                                    $feature_sub_title_translate = [];
                                    foreach($feature_sub_title->translations as $t)
                                    {
                                        if($t->locale == $lang && $t->key=='feature_sub_title'){
                                            $feature_sub_title_translate[$lang]['value'] = $t->value;
                                        }
                                    }
                                }

                                ?>
                                <div class="row g-3 d-none lang_form" id="{{$lang}}-form">
                                    <div class="col-sm-6">
                                        <label class="form-label">{{translate('Title')}} ({{strtoupper($lang)}})
                                            <span class="input-label-secondary text--title"  data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_title_within_20_characters') }}">
                                                <i class="tio-info-outined"></i>
                                            </span>
                                        </label>
                                        <input type="text" maxlength="20" name="feature_title[]" class="form-control" value="{{ $feature_title_translate[$lang]['value']?? '' }}" placeholder="{{translate('messages.title_here...')}}">
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label">{{translate('Subtitle')}} ({{strtoupper($lang)}})
                                            <span class="input-label-secondary text--title"  data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_subtitle_within_70_characters') }}">
                                                <i class="tio-info-outined"></i>
                                            </span>
                                        </label>
                                        <input type="text" maxlength="70" name="feature_sub_title[]" class="form-control" value="{{ $feature_sub_title_translate[$lang]['value']?? '' }}" placeholder="{{translate('messages.sub_title_here...')}}">
                                    </div>
                                </div>
                                <input type="hidden" name="lang[]" value="{{$lang}}">
                            @endforeach

                        @endif
                    <div class="btn--container justify-content-end mt-3">
                        <button type="reset" class="btn btn--reset">{{translate('Reset')}}</button>
                        <button type="submit" onclick="" class="btn btn--primary mb-2">{{translate('Save')}}</button>
                    </div>
                </div>
            </div>
        </form>

        <form action="{{ route('admin.landing_page.feature_store') }}" method="post" enctype="multipart/form-data" >
            @csrf
            <h5 class="card-title mb-3 mt-3">
                <span class="card-header-icon mr-2">
                    <img src="{{asset('public/assets/admin/img/react_header.png')}}" alt="" class="mw-100">
                </span>

            <span>{{translate('messages.Feature_List')}}</span>
            </h5>
            <div class="card mb-3">
                <div class="card-body">
                    {{-- <div class="d-flex justify-content-end">
                        <div class="text--primary-2 py-1 d-flex flex-wrap align-items-center" type="button" data-toggle="modal" data-target="#section_view">
                            <strong class="mr-2">{{translate('Section View')}}</strong>
                            <div>
                                <i class="tio-intersect"></i>
                            </div>
                        </div>
                    </div> --}}
                    <div class="row g-3">
                        <div class="col-md-6 lang_form default-form">
                            <div class="row g-3">
                                <input type="hidden" name="lang[]" value="default">
                                <div class="col-md-12">
                                    <label class="form-label">{{translate('messages.Title')}}
                                        <span class="input-label-secondary text--title"  data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_title_within_20_characters') }}">
                                            <i class="tio-info-outined"></i>
                                        </span>
                                    </label>
                                    <input type="text" maxlength="20"  name="name[]" class="form-control" placeholder="{{translate('Ex:  John Doe')}}">
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label">{{translate('messages.Short_Description')}}

                                        <span class="input-label-secondary text--title"  data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_short_description_within_60_characters') }}">
                                            <i class="tio-info-outined"></i>
                                        </span>
                                    </label>
                                    <textarea name="description[]" maxlength="70"  placeholder="{{translate('Very Good Company')}}" class="form-control h92px"></textarea>
                                </div>
                            </div>
                        </div>

                        @if($language)
                        @forelse(json_decode($language) as $lang)
                        <div class="col-md-6 d-none lang_form" id="{{$lang}}-form1">
                            <div class="row g-3">
                                <input type="hidden" name="lang[]" value="{{$lang}}">
                                <div class="col-md-12">
                                    <label class="form-label">{{translate('messages.Title')}} ({{strtoupper($lang)}})

                                        <span class="input-label-secondary text--title"  data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_title_within_20_characters') }}">
                                            <i class="tio-info-outined"></i>
                                        </span>
                                    </label>
                                    <input type="text" name="name[]" maxlength="20" class="form-control" placeholder="{{translate('Ex:  John Doe')}}">
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label">{{translate('messages.Short_Description')}} ({{strtoupper($lang)}})

                                        <span class="input-label-secondary text--title"  data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_short_description_within_60_characters') }}">
                                            <i class="tio-info-outined"></i>
                                        </span>
                                    </label>
                                    <textarea name="description[]"  maxlength="70" placeholder="{{translate('Very Good Company')}}" class="form-control h92px"></textarea>
                                </div>
                            </div>
                        </div>
                        @empty
                        @endforelse
                        @endif


                        <div class="col-md-6">
                            <div class="d-flex gap-40px">
                                <div>
                                    <label class="form-label d-block mb-2">
                                        {{translate('messages.Feature_Image')}}  <span class="text--primary">(1:1)</span>
                                    </label>
                                    <label class="upload-img-3 m-0 d-block">
                                        <div class="img">
                                            <img src="{{asset("/public/assets/admin/img/aspect-1.png")}}"  class="vertical-img max-w-187px" alt="">
                                        </div>
                                        <input type="file" name="feature_image" hidden="">
                                    </label>
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="btn--container justify-content-end mt-3">
                        <button type="reset" class="btn btn--reset">{{translate('Reset')}}</button>
                        <button type="submit" onclick="" class="btn btn--primary mb-2">{{translate('Add')}}</button>
                    </div>
                </form>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive pt-0">
                        <table class="table table-borderless table-thead-bordered table-align-middle table-nowrap card-table m-0">
                            <thead class="thead-light">
                                <tr>
                                    <th class="border-top-0">{{translate('SL')}}</th>
                                    <th class="border-top-0">{{translate('Title')}}</th>
                                    <th class="border-top-0">{{translate('messages.Short_Description')}}</th>
                                    <th class="border-top-0">{{translate('Image')}}</th>
                                    <th class="border-top-0">{{translate('Status')}}</th>
                                    <th class="text-center border-top-0">{{translate('Action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($features as $key=>$feature)
                                <tr>
                                    <td>{{ $key+$features->firstItem() }}</td>
                                    <td>
                                        <div class="text--title">
                                        {{ $feature->title }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="word-break">
                                            {{ \Illuminate\Support\Str::limit($feature->description, 50, $end='...')    }}
                                        </div>
                                    </td>
                                    <td>
                                        <img src="{{asset('storage/app/public/feature_image')}}/{{$feature->image}}"
                                        onerror="this.src='{{asset('/public/assets/admin/img/upload-3.png')}}'" class="__size-105" alt="">
                                    </td>
                                    <td>
                                        <label class="toggle-switch toggle-switch-sm">
                                            <input type="checkbox" class="toggle-switch-input" onclick="toogleStatusModal(event,'feature_status_{{$feature->id}}','testimonial-on.png','testimonial-off.png',`{{translate('Want_to_enable_the_status_of_this ')}} <strong>{{translate('Feature')}}`,`{{translate('Want_to_disable_the_status_of_this')}} <strong>{{translate('feature')}}`,`<p>{{translate('If_enabled,_everyone_can_see_it_on_the_landing_page')}}</p>`,`<p>{{translate('If_disabled,_it_will_be_hidden_from_the_landing_page')}}</p>`)"
                                                id="feature_status_{{$feature->id}}" {{$feature->status?'checked':''}}>
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                        <form action="{{route('admin.landing_page.feature_status',[$feature->id,$feature->status?0:1])}}" method="get" id="feature_status_{{$feature->id}}_form">
                                        </form>
                                    </td>

                                    <td>
                                        <div class="btn--container justify-content-center">
                                            <a class="btn action-btn btn--primary btn-outline-primary" href="{{route('admin.landing_page.feature_edit',[$feature['id']])}}">
                                                <i class="tio-edit"></i>
                                            </a>
                                            <a class="btn action-btn btn--danger btn-outline-danger" href="javascript:"
                                            onclick="form_alert_feature('feature-{{$feature['id']}}','{{ translate('Want_to_delete_this_feature?') }}','{{ translate('If_yes,_it_will_be_removed_from_the_feature_list?') }}')" title="{{translate('messages.delete')}} {{translate('messages.feature')}}"><i class="tio-delete-outlined"></i>
                                            </a>
                                            <form action="{{route('admin.landing_page.feature_delete',[$feature['id']])}}" method="post" id="feature-{{$feature['id']}}">
                                                @csrf @method('delete')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty

                                @endforelse
                            </tbody>
                        </table>
                        @if(count($features) === 0)
                        <div class="empty--data">
                            <img src="{{asset('/public/assets/admin/img/empty.png')}}" alt="public">
                            <h5>
                                {{translate('no_data_found')}}
                            </h5>
                        </div>
                        @endif
                    </div>
                    <div class="page-area px-4 pb-3">
                        <div class="d-flex align-items-center justify-content-end">
                            <div>
                                {!! $features->appends(request()->all())->links() !!}
                            </div>
                        </div>
                    </div>
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
    <!-- How it Works -->


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

        function form_alert_feature(id, message ,data)  {
            Swal.fire({
                title: data,
                text: message,
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#FC6A57',
                cancelButtonText: '{{ translate('messages.No') }}',
                confirmButtonText: '{{ translate('messages.Yes') }}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $('#' + id).submit()
                }
            })
        }
    </script>

@endpush
