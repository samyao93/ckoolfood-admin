
@extends('layouts.vendor.app')
@section('title',translate('messages.edit_restaurant'))
@push('css_or_js')
    <!-- Custom styles for this page -->
    <link href="{{asset('public/assets/admin')}}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
     <!-- Custom styles for this page -->
     <link href="{{asset('public/assets/admin/css/croppie.css')}}" rel="stylesheet">
     <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush
@section('content')
    <!-- Content Row -->
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h2 class="page-header-title text-capitalize">
                        <div class="card-header-icon d-inline-flex mr-2 img">
                            <img src="{{asset('/public/assets/admin/img/resturant-panel/page-title/resturant.png')}}" alt="public">
                        </div>
                        <span>
                            {{translate('Edit Restaurant Information')}}
                        </span>
                    </h2>
                </div>
            </div>
        </div>
        @php($language=\App\Models\BusinessSetting::where('key','language')->first())
        @php($language = $language->value ?? null)
        @php($default_lang = str_replace('_', '-', app()->getLocale()))
        <!-- End Page Header -->
        <form action="{{route('vendor.shop.update')}}" method="post"
        enctype="multipart/form-data">
        @csrf
            <div class="row g-2">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body p-xl-4">
                            @if($language)
                                <ul class="nav nav-tabs mb-4">
                                    <li class="nav-item">
                                        <a class="nav-link lang_link active"
                                        href="#"
                                        id="default-link">{{ translate('Default') }}</a>
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
                            <div class="row gy-3 gx-2">
                                <div class="col-md-6">

                                        <div class="form-group lang_form" id="default-form">
                                                <label class="input-label" for="exampleFormControlInput1">{{ translate('messages.restaurant') }}
                                                    {{ translate('messages.name') }} ({{translate('messages.default')}})</label>
                                            <input type="text" name="name[]" class="form-control" placeholder="{{ translate('messages.restaurant') }} {{ translate('messages.name') }}" maxlength="191" value="{{$shop?->getRawOriginal('name')}}" oninvalid="document.getElementById('en-link').click()">
                                        </div>
                                        @if ($language)
                                            <input type="hidden" name="lang[]" value="default">
                                            @foreach(json_decode($language) as $lang)
                                                <?php
                                                    if(count($shop['translations'])){
                                                        $translate = [];
                                                        foreach($shop['translations'] as $t)
                                                        {
                                                            if($t->locale == $lang && $t->key=="name"){
                                                                $translate[$lang]['name'] = $t->value;
                                                            }

                                                        }
                                                    }
                                                ?>
                                                <div class="form-group d-none lang_form" id="{{$lang}}-form">
                                                    <label class="input-label" for="exampleFormControlInput1">{{ translate('messages.restaurant') }}
                                                {{ translate('messages.name') }} ({{strtoupper($lang)}})</label>
                                                    <input type="text" name="name[]" class="form-control" placeholder="{{ translate('messages.restaurant') }} {{ translate('messages.name') }}" maxlength="191" value="{{$translate[$lang]['name']??''}}" oninvalid="document.getElementById('en-link').click()">
                                                </div>
                                                <input type="hidden" name="lang[]" value="{{$lang}}">
                                            @endforeach
                                        @endif



                                    <div class="form-group mb-0 pt-lg-1">
                                        <label for="contact" class="form-label">{{translate('messages.contact')}} {{translate('messages.number')}}<span class="text-danger">*</span></label>
                                        <input type="tel" name="contact" value="{{$shop->phone}}" placeholder="{{ translate('Ex : +880 123456789') }}" class="form-control h--45px" id="contact"
                                                required>
                                    </div>
                                </div>
                                <div class="col-md-6">


                                    <div class="form-group mb-0  lang_form default-form"  >
                                        <label for="address" class="form-label">{{ translate('messages.restaurant') }} {{translate('messages.address')}} ({{translate('messages.default')}})<span class="text-danger">*</span></label>
                                        <textarea type="text" rows="4" name="address[]" value="" placeholder="{{ translate('Ex : House-45, Road-08, Sector-12, Mirupara, Test City') }}" class="form-control min-height-149px" id="address">{{$shop->address}}</textarea>
                                    </div>





                                    @if ($language)
                                    @foreach(json_decode($language) as $lang)
                                        <?php
                                            if(count($shop['translations'])){
                                                $translate = [];
                                                foreach($shop['translations'] as $t)
                                                {
                                                    if($t->locale == $lang && $t->key=="address"){
                                                        $translate[$lang]['address'] = $t->value;
                                                    }

                                                }
                                            }
                                        ?>
                                        <div class="form-group mb-0  d-none lang_form" id="{{$lang}}-form1">
                                                <label class="input-label" for="exampleFormControlInput1">{{ translate('messages.restaurant') }}
                                            {{ translate('messages.address') }} ({{strtoupper($lang)}})</label>
                                            <textarea type="text" rows="4" name="address[]" value="" placeholder="{{ translate('Ex : House-45, Road-08, Sector-12, Mirupara, Test City') }}" class="form-control min-height-149px" id="address" >{{  $translate[$lang]['address'] ?? ''}}</textarea>
                                        </div>
                                    @endforeach
                                @endif






                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="card-title font-regular">
                                {{translate('Upload Restaurant Logo')}} <span class="text-danger">({{translate('messages.Ratio 200x200')}})</span>
                            </h5>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <center class="my-auto py-4 py-xl-5">
                                <img class="initial-91" id="viewer"
                                onerror="this.src='{{asset('public/assets/admin/img/image-place-holder.png')}}'"
                                src="{{asset('storage/app/public/restaurant/'.$shop->logo)}}" alt="Product thumbnail"/>
                            </center>
                            <div class="custom-file">
                                <input type="file" name="image" id="customFileUpload" class="custom-file-input"
                                    accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                <label class="custom-file-label" for="customFileUpload">{{translate('messages.choose')}} {{translate('messages.file')}}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="card-title font-regular">
                                {{translate('messages.upload')}} {{translate('messages.cover')}} {{translate('messages.photo')}} <span class="text-danger">({{translate('messages.ratio')}} : 1100x320)</span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <center class="my-auto py-4 py-xl-5">
                                <img class="initial-92" id="coverImageViewer"
                                onerror="this.src='{{asset('public/assets/admin/img/restaurant_cover.jpg')}}'"
                                src="{{asset('storage/app/public/restaurant/cover/'.$shop->cover_photo)}}" alt="Product thumbnail"/>
                            </center>
                            <div class="custom-file">
                                <input type="file" name="photo" id="coverImageUpload" class="custom-file-input"
                                    accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                <label class="custom-file-label" for="customFileUpload">{{translate('messages.choose')}} {{translate('messages.file')}}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="btn--container justify-content-end mt-2">
                        <button type="submit" class="btn btn--primary" id="btn_update">{{translate('messages.update')}}</button>
                        <a class="btn btn--danger text-capitalize" href="{{route('vendor.shop.view')}}">{{translate('messages.cancel')}}</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('script_2')

   <script>
        function readURL(input, viewer) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#'+viewer).attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#coverImageUpload").change(function () {
            readURL(this, 'coverImageViewer');
        });
        $("#customFileUpload").change(function () {
            readURL(this, 'viewer');
        });
   </script>
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
