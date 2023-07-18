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

    <div class="tab-content">
        <div class="tab-pane fade show active">
            <form action="{{ route('admin.landing_page.testimonial_update',[$review->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <h5 class="card-title mb-3 mt-3">
                    <span class="card-header-icon mr-2"><i class="tio-settings-outlined"></i></span> <span>{{translate('Testimonial List Section')}}</span>
                </h5>
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-end">

                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">{{translate('Reviewer Name')}}</label>
                                        <input type="text" name="name" maxlength="191" required  value="{{ $review->name }}" class="form-control" placeholder="{{translate('Ex:  John Doe')}}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">{{translate('Designation')}}</label>
                                        <input type="text"  maxlength="191" required name="designation" value="{{ $review->designation }}" class="form-control" placeholder="{{translate('Ex:  CTO')}}">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">{{translate('messages.review')}}
                                            <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{translate('Write_the_review_within_300_characters')}}">
                                                <i class="tio-info-outined"></i>
                                            </span></label>
                                        <textarea name="review"  maxlength="300" required  placeholder="{{translate('Very Good Company')}}" class="form-control h92px">{{ $review->review }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex gap-40px">
                                    <div>
                                        <label class="form-label d-block mb-2">
                                            {{translate('Reviewer Image *')}}  <span class="text--primary">(1:1)</span>
                                        </label>
                                        <div class="position-relative">
                                        <label class="upload-img-3 m-0 d-block">
                                            <div class="img">
                                                <img src="{{asset('storage/app/public/reviewer_image')}}/{{$review->reviewer_image}}"onerror="this.src='{{asset("/public/assets/admin/img/aspect-1.png")}}'" class="vertical-img max-w-187px" alt="">
                                            </div>
                                            <input type="file"   name="reviewer_image" hidden="">
                                        </label>
                                        @if ($review->reviewer_image)
                                        <span id="remove_image_1" class="remove_image_button"
                                            onclick="toogleStatusModal(event,'remove_image_1','mail-success','mail-warninh','{{translate('Important!')}}','{{translate('Warning!')}}',`<p>{{translate('Are_you_sure_you_want_to_remove_this_image')}}</p>`,`<p>{{translate('Are_you_sure_you_want_to_remove_this_image.')}}</p>`)"
                                            > <i class="tio-clear"></i></span>
                                        @endif
                                    </div>
                                    </div>
                                    {{-- <div class="d-flex flex-column">
                                        <label class="form-label d-block mb-2">
                                            {{translate('Company Logo *')}}  <span class="text--primary">(3:1)</span>
                                        </label>
                                        <label class="upload-img-3 m-0 d-block my-auto">
                                            <div class="img">
                                                <img src="{{asset('storage/app/public/reviewer_image')}}/{{$review->company_image}}" onerror="this.src='{{asset("/public/assets/admin/img/aspect-3-1.png")}}'" class="vertical-img max-w-187px" alt="">
                                            </div>
                                            <input type="file"  name="company_image" hidden="">
                                        </label>
                                    </div> --}}
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

<form  id="remove_image_1_form" action="{{ route('admin.remove_image') }}" method="post">
    @csrf
    <input type="hidden" name="id" value="{{  $review?->id}}" >
    <input type="hidden" name="model_name" value="AdminTestimonial" >
    <input type="hidden" name="image_path" value="reviewer_image" >
    <input type="hidden" name="field_name" value="reviewer_image" >
</form>
@endsection
@push('script_2')

@endpush
