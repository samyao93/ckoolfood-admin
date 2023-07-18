@extends('layouts.admin.app')

@section('title',translate('messages.landing_page_settings'))

@push('css_or_js')
    <!-- Custom styles for this page -->
    <link href="{{asset('public/assets/admin/css/croppie.css')}}" rel="stylesheet">
@endpush

@section('content')
<div class="content container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <!-- Page Header -->
        <h1 class="page-header-title text-capitalize">
            <div class="card-header-icon d-inline-flex mr-2 img">
                <img src="{{asset('/public/assets/admin/img/landing-page.png')}}" class="mw-26px" alt="public">
            </div>
            <span>
                {{ translate('messages.landing_page_settings') }}
            </span>
        </h1>
        <!-- End Page Header -->
        <!-- Nav Scroller -->
         <div class="js-nav-scroller hs-nav-scroller-horizontal">
                <!-- Nav -->
                @include('admin-views.business-settings.landing-page-settings.top-menu-links.top-menu-links')
                <!-- End Nav -->
            </div>
        <!-- End Nav Scroller -->
    </div>
        <!-- End Page Header -->
    <!-- Page Heading -->

    <div class="card my-2">
        <div class="card-body">
            <form action="{{route('admin.business-settings.landing-page-settings', 'testimonial')}}" method="POST" enctype="multipart/form-data">
                @php($testimonial = \App\Models\BusinessSetting::where(['key'=>'testimonial'])->first())
                @php($testimonial = isset($testimonial->value)?json_decode($testimonial->value, true):null)

                @csrf
                <div class="row gy-3">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="input-label" for="reviewer_name">{{translate('messages.reviewer')}}</label>
                            <input type="text" id="reviewer_name"  name="reviewer_name" class="form-control h--45px" placeholder="{{ translate('Ex: Jhone Dhoe') }}">
                        </div>
                        <div class="form-group">
                            <label class="input-label" for="reviewer_designation">{{translate('messages.designation')}}</label>
                            <input type="text" id="reviewer_designation"  name="reviewer_designation" class="form-control h--45px" placeholder="{{ translate('Ex: Frontend Web Developer') }}">
                        </div>
                        <div class="form-group mb-0">
                            <label class="input-label" for="review">{{translate('messages.review')}}</label>
                            <textarea type="text" id="review"  name="review" class="form-control" placeholder="{{ translate('Ex: description') }}"></textarea>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group h-100 d-flex flex-column mb-0">
                            <label class="input-label text-center d-block mt-auto mb-lg-0" >{{translate('messages.image')}}<small class="text-danger">* ( {{translate('messages.size')}}: 200 X 200 px )</small></label>

                            <center id="image-viewer-section" class="pt-2 mt-auto mb-auto">
                                <img class="initial-5" id="viewer"
                                        src="{{asset('public/assets/admin/img/100x100/user.png')}}" alt=""/>
                            </center>

                            <div class="custom-file mt-2">
                                <input type="file" name="image" id="customFileEg1" class="custom-file-input"
                                        accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" required>
                                <label class="custom-file-label" for="customFileEg1">{{translate('messages.choose')}} {{translate('messages.file')}}</label>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="form-group mt-3">
                    <div class="btn--container justify-content-end">
                        <button type="reset" id="reset_btn" class="btn btn--reset">{{translate('messages.reset')}}</button>
                        <button type="submit" class="btn btn--primary">{{translate('messages.submit')}}</button>
                    </div>
                </div>
            </form>
            <table class="table table-borderless table-thead-bordered table-align-middle card-table">
                <thead class="thead-light">
                    <tr>
                        <th scope="col" class="w-10p">{{ translate('messages.sl') }}</th>
                        <th scope="col" class="w-25p">{{translate('messages.reviewer')}}</th>
                        <th scope="col" class="w-15p">{{translate('messages.designation')}}</th>
                        <th scope="col" class="w-40p">{{translate('messages.review')}}</th>
                        <th scope="col" class="w-10p" class="text-center">{{translate('messages.action')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @if($testimonial)
                    @foreach ($testimonial as $key=>$sp)
                        <tr>
                            <td scope="row">{{$key + 1}}</td>
                            <td>
                                <div class="media align-items-center">
                                    <img class="avatar avatar-lg mr-3 img-circle bg-f4f4f4" src="{{asset('public/assets/landing/image')}}/{{$sp['img']}}"
                                            onerror="this.src='{{asset('public/assets/admin/img/160x160/img2.jpg')}}'" alt="{{$sp['name']}}">
                                    <div class="media-body">
                                        <h5 class="text-hover-primary mb-0">{{$sp['name']}}</h5>
                                    </div>
                                </div>
                            </td>
                            <td>{{$sp['position']}}</td>
                            <td class="mw-150px">
                                <p class="text-justify w-100">{{$sp['detail']}}</p>
                            </td>
                            <td>
                                <div class="btn--container justify-content-center">
                                    {{-- <a class="btn btn--primary btn-outline-primary action-btn" href="javascript:void(0)" data-toggle="tooltip" data-placement="right" data-original-title="Edit Now"><i class="tio-edit"></i>
                                    </a> --}}
                                    <a class="btn btn--danger btn-outline-danger action-btn" href="javascript:"
                                        onclick="form_alert('sp-{{$key}}','{{translate('messages.Want_to_delete_this_item')}}')" title="{{translate('messages.delete')}}"><i class="tio-delete-outlined"></i>
                                    </a>
                                </div>
                                <form action="{{route('admin.business-settings.landing-page-settings-delete',['tab'=>'testimonial', 'key'=>$key])}}"
                                        method="post" id="sp-{{$key}}">
                                    @csrf @method('delete')
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    @endif
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
    </div>
</div>
@endsection

@push('script_2')
    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function () {
            readURL(this);
            $('#image-viewer-section').show(1000);
        });

        $(document).on('ready', function () {

        });

        $('#reset_btn').click(function(){
            $('#viewer').attr('src','{{asset('public/assets/admin/img/100x100/user.png')}}');
        })
    </script>
@endpush
