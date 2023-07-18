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

            <ul class="nav nav-tabs page-header-tabs">
                <li class="nav-item">
                    <a class="nav-link active"
                        href="{{ route('admin.business-settings.landing-page-settings', 'platform-order') }}"
                        aria-disabled="true">{{ translate('messages.order_platform') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link"
                        href="{{ route('admin.business-settings.landing-page-settings', 'platform-restaurant') }}"
                        aria-disabled="true">{{ translate('messages.restaurant_platform') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link"
                        href="{{ route('admin.business-settings.landing-page-settings', 'platform-delivery') }}"
                        aria-disabled="true">{{ translate('messages.delivery_platform') }}</a>
                </li>
            </ul>
        </div>
        <!-- End Nav Scroller -->
    </div>
        <!-- End Page Header -->
    <!-- Page Heading -->

    <div class="card my-2">
        <br>
        <h2 class="text-center">{{ translate('messages.order_platform') }}</h2>
        <div class="card-body">
            <form action="{{route('admin.business-settings.landing-page-settings', 'platform-main')}}" method="POST" enctype="multipart/form-data">
                @php($order_platform = \App\Models\BusinessSetting::where(['key'=>'order_platform'])->first())
                @php($order_platform = isset($order_platform->value)?json_decode($order_platform->value, true):null)
                @csrf
                <div class="row gy-3">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="input-label" for="title">{{translate('messages.Menu')}}</label>
                            <input required type="text" id="title" value="{{ isset($order_platform['title'])?$order_platform['title']:'' }}"  name="title" class="form-control h--45px" placeholder="{{ translate('Ex:Our Mobile App' ) }}">
                        </div>
                        {{-- <div class="form-group">
                            <label class="input-label" for="sub_title">{{translate('messages.sub_title')}}</label>
                            <input required type="text" id="sub_title" value="{{  isset($order_platform['sub_title'])?$order_platform['sub_title']:'' }}"  name="sub_title" class="form-control h--45px" placeholder="{{ translate('Ex: Manage Your Data') }}">
                        </div> --}}
                        <br>
                        <div class="form-group mb-0">
                            <label class="toggle-switch toggle-switch-sm d-flex justify-content-between input-label mb-1" for="url_status">
                                <span class="form-check-label">{{translate('messages.button_url')}} </span>
                                <input type="checkbox" class="toggle-switch-input" value="1" name="url_status" id="url_status"  {{(isset($order_platform['url_status']) && $order_platform['url_status'])?'checked':''}} >
                                <span class="toggle-switch-label">
                                    <span class="toggle-switch-indicator"></span>
                                </span>
                            </label>
                            <input type="text" id="order_url" name="url" placeholder="https://google.com" class="form-control h--45px" value="{{isset($order_platform['url'])?$order_platform['url']:''}}">
                        </div>
                        {{-- <div class="form-group mb-0">
                            <label class="input-label" for="detail">{{translate('messages.details')}}</label>
                            <textarea type="text" id="detail" required name="detail" class="form-control" placeholder="{{ translate('Ex: Details') }}">{{ isset($order_platform['detail'])?$order_platform['detail']:'' }}</textarea>
                        </div> --}}
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group h-100 d-flex flex-column mb-0">
                            <label class="input-label text-center d-block mt-auto mb-lg-0" >{{translate('messages.image')}}<small class="text-danger">* ( {{translate('messages.size')}}: 200 X 200 px )</small></label>
                            <center id="image-viewer-section" class="pt-2 mt-auto mb-auto">
                                <img class="initial-5" id="viewer3"
                                src="{{ asset('storage/app/public/landing') }}/{{ $order_platform['image'] ?? null }}"
                                onerror="this.src='{{asset('public/assets/admin/img/400x400/img2.jpg')}}'"
                                 alt=""/>
                            </center>
                            <div class="custom-file mt-2">
                                <input type="file" name="image" id="customFileEg3" class="custom-file-input"
                                        accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" >
                                <label class="custom-file-label" for="customFileEg3">{{translate('messages.choose')}} {{translate('messages.file')}}</label>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="form-group mt-3">
                    <div class="btn--container justify-content-end">
                        <button type="reset" id="reset_btn" class="btn btn--reset">{{translate('messages.reset')}}</button>
                        <button type="submit" name='button' value="order_platform" class="btn btn--primary">{{translate('messages.submit')}}</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
    <div class="card my-2">
        <br>
        <h2 class="text-center">{{ translate('messages.order_platform_features') }}</h2>
        <div class="card-body">
            <form action="{{route('admin.business-settings.landing-page-settings', 'platform-data')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row gy-3">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="input-label" for="title">{{translate('messages.title')}}</label>
                            <input required type="text" id="title" value="{{ old('title')}}"  name="title" class="form-control h--45px" placeholder="{{ translate('Ex: Manage Your Data') }}">
                        </div>
                        <br>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group mb-0">
                            <label class="input-label" for="detail">{{translate('messages.details')}}</label>
                            <textarea type="text" id="detail" required name="detail" class="form-control h--45px" placeholder="{{ translate('Ex: Details') }}">{{ old('detail') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="form-group mt-3">
                    <div class="btn--container justify-content-end">
                        <button type="reset" id="reset_btn" class="btn btn--reset">{{translate('messages.reset')}}</button>
                        <button type="submit" name='button' value="platform_order_data" class="btn btn--primary">{{translate('messages.submit')}}</button>
                    </div>
                </div>
            </form>

            @php($order_platform = \App\Models\BusinessSetting::where(['key'=>'platform_order_data'])->first())
            @php($order_platform = isset($order_platform->value)?json_decode($order_platform->value, true):[])
            <table class="table table-borderless table-thead-bordered table-align-middle card-table">
                <thead class="thead-light">
                    <tr>
                        <th scope="col" class="w-10p">{{ translate('messages.sl') }}</th>
                        <th scope="col" class="w-15p">{{translate('messages.Title')}}</th>
                        <th scope="col" class="w-40p">{{translate('messages.detail')}}</th>
                        <th scope="col" class="w-10p" class="text-center">{{translate('messages.action')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @if($order_platform)
                    @foreach ($order_platform as $key=>$sp)
                        <tr>
                            <td scope="row">{{$key + 1}}</td>
                            <td>{{$sp['title']}}</td>
                            <td class="mw-150px">
                                <p class="text-justify w-100">{{$sp['detail']}}</p>
                            </td>
                            <td>
                                <div class="btn--container justify-content-center">
                                    <a class="btn btn--danger btn-outline-danger action-btn" href="javascript:"
                                        onclick="form_alert('sp-{{$key}}','{{translate('messages.Want_to_delete_this_item')}}')" title="{{translate('messages.delete')}}"><i class="tio-delete-outlined"></i>
                                    </a>
                                </div>
                                <form action="{{route('admin.business-settings.landing-page-settings-delete',['tab'=>'platform_order_data', 'key'=>$key])}}"
                                        method="post" id="sp-{{$key}}">
                                    @csrf @method('delete')
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
            @if(count($order_platform) === 0)
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

        $('#reset_btn').click(function(){
            $('#viewer').attr('src','{{asset('public/assets/admin/img/100x100/user.png')}}');
        })
    </script>
        <script>
            function readURL(input, viewer) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#' + viewer).attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }

            $("#customFileEg1").change(function() {
                readURL(this, 'viewer');
                $('#image-viewer-section').show(1000);
            });
            $("#customFileEg2").change(function() {
                readURL(this, 'viewer2');
                $('#image-viewer-section3').show(1000);
            });
            $("#customFileEg3").change(function() {
                readURL(this, 'viewer3');
                $('#image-viewer-section3').show(1000);
            });


        </script>
@endpush
