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


        <form action="{{ route('admin.landing_page.settings', 'testimonial-title') }}" method="POST">
            @csrf
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row g-3 lang_form" id="default-form">
                        <div class="col-sm-12">
                            <label class="form-label">{{translate('Title')}} ({{translate('default')  }})
                             <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{translate('Write_the_title_within_60_characters')}}">
                                                                <i class="tio-info-outined"></i>
                                                            </span>
                                                        </label>
                            <input type="text" name="testimonial_title[]" maxlength="60"  class="form-control" value="{{$testimonial_title?->getRawOriginal('value') ?? ''}}" placeholder="{{translate('messages.title_here...')}}">
                        </div>
                    </div>
                    <input type="hidden" name="lang[]" value="default">
                    @if ($language)
                            @foreach(json_decode($language) as $lang)
                            <?php
                            if($testimonial_title?->translations){
                                    $testimonial_title_translate = [];
                                    foreach($testimonial_title->translations as $t)
                                    {
                                        if($t->locale == $lang && $t->key=='testimonial_title'){
                                            $testimonial_title_translate[$lang]['value'] = $t->value;
                                        }
                                    }
                                }
                                ?>
                                <div class="row g-3 d-none lang_form" id="{{$lang}}-form">
                                    <div class="col-sm-12">
                                        <label class="form-label">{{translate('Title')}} ({{strtoupper($lang)}})
                                         <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{translate('Write_the_title_within_60_characters')}}">
                                                                <i class="tio-info-outined"></i>
                                                            </span>
                                                        </label>
                                        <input type="text" name="testimonial_title[]" maxlength="60"  class="form-control" value="{{ $testimonial_title_translate[$lang]['value']?? '' }}" placeholder="{{translate('messages.title_here...')}}">
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

        <form action="{{ route('admin.landing_page.testimonial_store',) }}" method="post" enctype="multipart/form-data" >
            @csrf
            <h5 class="card-title mb-3 mt-3">
                <span class="card-header-icon mr-2"><i class="tio-settings-outlined"></i></span> <span>{{translate('Testimonial List Section')}}</span>
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
                        <div class="col-md-6">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">{{translate('Reviewer Name')}}</label>
                                    <input type="text" maxlength="191" required name="name" class="form-control" placeholder="{{translate('Ex:  John Doe')}}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{translate('Designation')}}</label>
                                    <input type="text" maxlength="191" required name="designation" class="form-control" placeholder="{{translate('Ex:  CTO')}}">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">{{translate('messages.review')}}
                                        <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{translate('Write_the_review_within_300_characters')}}">
                                            <i class="tio-info-outined"></i>
                                        </span>
                                    </label>
                                    <textarea name="review" maxlength="300" required placeholder="{{translate('Very Good Company')}}" class="form-control h92px"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex gap-40px">
                                <div>
                                    <label class="form-label d-block mb-2">
                                        {{translate('Reviewer Image *')}}  <span class="text--primary">(1:1)</span>
                                    </label>
                                    <label class="upload-img-3 m-0 d-block">
                                        <div class="img">
                                            <img src="{{asset("/public/assets/admin/img/aspect-1.png")}}"  class="vertical-img max-w-187px" alt="">
                                        </div>
                                        <input type="file"   name="reviewer_image" hidden="">
                                    </label>
                                </div>
                                {{-- <div class="d-flex flex-column">
                                    <label class="form-label d-block mb-2">
                                        {{translate('Company Logo *')}}  <span class="text--primary">(3:1)</span>
                                    </label>
                                    <label class="upload-img-3 m-0 d-block my-auto">
                                        <div class="img">
                                            <img src="{{asset("/public/assets/admin/img/aspect-3-1.png")}}"  class="vertical-img max-w-187px" alt="">
                                        </div>
                                        <input type="file" required  name="company_image" hidden="">
                                    </label>
                                </div> --}}
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
                                    <th class="border-top-0">{{translate('Reviewer_Name')}}</th>
                                    <th class="border-top-0">{{translate('Designation')}}</th>
                                    <th class="border-top-0">{{translate('Reviews')}}</th>
                                    <th class="border-top-0">{{translate('Reviewer_Image')}}</th>
                                    {{-- <th class="text-center border-top-0">{{translate('Company_Image')}}</th> --}}
                                    <th class="border-top-0">{{translate('Status')}}</th>
                                    <th class="text-center border-top-0">{{translate('Action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($testimonial as $key=>$review)
                                <tr>
                                    <td>{{ $key+$testimonial->firstItem() }}</td>
                                    <td>
                                        <div class="text--title">
                                        {{ $review->name }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text--title">
                                        {{ $review->designation }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="word-break">
                                            {{ \Illuminate\Support\Str::limit($review->review, 50, $end='...')    }}
                                        </div>
                                    </td>
                                    <td>
                                        <img src="{{asset('storage/app/public/reviewer_image')}}/{{$review->reviewer_image}}"
                                        onerror="this.src='{{asset('/public/assets/admin/img/upload-3.png')}}'" class="__size-105" alt="">
                                    </td>
                                    {{-- <td>
                                        <img src="{{asset('storage/app/public/reviewer_image')}}/{{$review->company_image}}"
                                        onerror="this.src='{{asset('/public/assets/admin/img/upload-3.png')}}'" class="__size-105" alt="">
                                    </td> --}}
                                    <td>
                                        <label class="toggle-switch toggle-switch-sm">
                                            <input type="checkbox" class="toggle-switch-input" onclick="toogleStatusModal(event,'testimonial_status_{{$review->id}}','testimonial-on.png','testimonial-off.png',`{{translate('By Turning ON ')}} <strong>{{translate('This testimonial')}}`,`{{translate('By Turning OFF ')}} <strong>{{translate('This testimonial')}}`,`<p>{{translate('This section will be enabled. You can see this section on your landing page.')}}</p>`,`<p>{{translate('This section  will be disabled. You can enable it in the settings')}}</p>`)"
                                                id="testimonial_status_{{$review->id}}" {{$review->status?'checked':''}}>
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                        <form action="{{route('admin.landing_page.testimonial_status',[$review->id,$review->status?0:1])}}" method="get" id="testimonial_status_{{$review->id}}_form">
                                        </form>
                                    </td>

                                    <td>
                                        <div class="btn--container justify-content-center">
                                            <a class="btn action-btn btn--primary btn-outline-primary" href="{{route('admin.landing_page.testimonial_edit',[$review['id']])}}">
                                                <i class="tio-edit"></i>
                                            </a>
                                            <a class="btn action-btn btn--danger btn-outline-danger" href="javascript:"
                                            onclick="form_alert('review-{{$review['id']}}','{{ translate('Want to delete this review ?') }}')" title="{{translate('messages.delete')}} {{translate('messages.review')}}"><i class="tio-delete-outlined"></i>
                                            </a>
                                            <form action="{{route('admin.landing_page.testimonial_delete',[$review['id']])}}" method="post" id="review-{{$review['id']}}">
                                                @csrf @method('delete')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty

                                @endforelse
                            </tbody>
                        </table>
                        @if(count($testimonial) === 0)
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
                                {!! $testimonial->appends(request()->all())->links() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

    </div>
    <!-- Testimonial Modal -->
    <div class="modal fade" id="testimonials-status-modal">
        <div class="modal-dialog status-warning-modal">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true" class="tio-clear"></span>
                    </button>
                </div>
                <div class="modal-body pb-5 pt-0">
                    <div class="max-349 mx-auto mb-20">
                        <div>
                            <div class="text-center">
                                <img src="{{asset('/public/assets/admin/img/modal/testimonial-off.png')}}" alt="" class="mb-20">
                                <h5 class="modal-title">{{translate('By Turning OFF ')}} <strong>{{translate('This Testimonial')}}</strong></h5>
                            </div>
                            <div class="text-center">
                                <p>
                                    {{translate('This testimonial will be disable. You can see this testimonial in review section.')}}
                                </p>
                            </div>
                        </div>
                        <!-- <div>
                            <div class="text-center">
                                <img src="{{asset('/public/assets/admin/img/modal/testimonial-on.png')}}" alt="" class="mb-20">
                                <h5 class="modal-title">{{translate('By Turning ON ')}} <strong>{{translate('This Testimonial')}}</strong></h5>
                            </div>
                            <div class="text-center">
                                <p>
                                    {{translate('This testimonial will be enabled. You can see this testimonial in review section.')}}
                                </p>
                            </div>
                        </div> -->
                        <div class="btn--container justify-content-center">
                            <button type="submit" class="btn btn--primary min-w-120" data-dismiss="modal">{{translate('Ok')}}</button>
                            <button id="reset_btn" type="reset" class="btn btn--cancel min-w-120" data-dismiss="modal">
                                {{translate("Cancel")}}
                            </button>
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
    </script>
@endpush
