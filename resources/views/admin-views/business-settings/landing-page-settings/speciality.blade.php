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
            <form action="{{route('admin.business-settings.landing-page-settings', 'speciality')}}" method="POST" enctype="multipart/form-data">
                @php($speciality = \App\Models\BusinessSetting::where(['key'=>'speciality'])->first())
                @php($speciality = isset($speciality->value)?json_decode($speciality->value, true):null)

                @csrf

                <div class="form-group">
                    <label class="input-label" for="speciality_title">{{translate('messages.speciality_title')}}</label>
                    <input type="text" id="speciality_title"  name="speciality_title" class="form-control h--45px" >
                </div>
                <div class="form-group">
                    <label class="input-label" >{{translate('messages.speciality_img')}}<small class="text-danger">* ( {{translate('messages.size')}}: 140 X 140 px )</small></label>
                    <div class="custom-file">
                        <input type="file" name="image" id="customFileEg1" class="custom-file-input"
                                accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" required>
                        <label class="custom-file-label" for="customFileEg1">{{translate('messages.choose')}} {{translate('messages.file')}}</label>
                    </div>

                    <center id="image-viewer-section" class="pt-2 initial-hidden">
                        <img class="initial-95" id="viewer"
                                src="{{asset('public/assets/admin/img/400x400/img2.jpg')}}" alt=""/>
                    </center>
                </div>

                <div class="form-group">
                    <div class="btn--container justify-content-end">
                        <button type="reset" class="btn btn--reset">{{translate('messages.reset')}}</button>
                        <button type="submit" class="btn btn--primary">{{translate('messages.submit')}}</button>
                    </div>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">{{ translate('messages.sl') }}</th>
                            <th scope="col">{{translate('messages.image')}}</th>
                            <th scope="col">{{translate('messages.speciality_title')}}</th>
                            <th scope="col" class="text-center">{{translate('messages.action')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($speciality)
                        @foreach ($speciality as $key=>$sp)
                            <tr>
                                <th scope="row">{{$key + 1}}</th>
                                <td>
                                    <div class="media align-items-center">
                                        <img class="avatar avatar-lg mr-3" src="{{asset('public/assets/landing/image')}}/{{$sp['img']}}"
                                                onerror="this.src='{{asset('public/assets/admin/img/160x160/img2.jpg')}}'" alt="{{$sp['title']}}">
                                    </div>
                                </td>
                                <td>{{$sp['title']}}</td>
                                <td>
                                    <div class="btn--container justify-content-center">
                                        {{-- <a class="btn btn--primary btn-outline-primary action-btn" href="javascript:void(0)" data-toggle="tooltip" data-placement="right" data-original-title="Edit Now"><i class="tio-edit"></i>
                                        </a> --}}
                                        <a class="btn btn--danger btn-outline-danger action-btn" href="javascript:"
                                            onclick="form_alert('sp-{{$key}}','{{translate('messages.Want_to_delete_this_item')}}')" data-toggle="tooltip" data-placement="right" data-original-title="{{translate('messages.delete')}}"><i class="tio-delete-outlined"></i>
                                        </a>
                                        <form action="{{route('admin.business-settings.landing-page-settings-delete',['tab'=>'speciality', 'key'=>$key])}}"
                                                method="post" id="sp-{{$key}}">
                                            @csrf @method('delete')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
                @if(count($speciality) === 0)
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
    </script>
@endpush
