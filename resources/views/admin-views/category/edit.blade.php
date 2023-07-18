@extends('layouts.admin.app')

@section('title',translate('messages.Update category'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h2 class="page-header-title text-capitalize">
                        <div class="card-header-icon d-inline-flex mr-2 img">
                            <img src="{{asset('public/assets/admin/img/sub-category.png')}}" alt="">
                        </div>
                        <span>
                            {{$category->position?translate('messages.sub').' ':''}}{{translate('messages.category')}} {{translate('messages.update')}}
                        </span>
                    </h2>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="card">
            <div class="card-body">
                <form action="{{route('admin.category.update',[$category['id']])}}" method="post" enctype="multipart/form-data">
                    @csrf
                    @php($language=\App\Models\BusinessSetting::where('key','language')->first())
                    @php($language = $language->value ?? null)
                    @php($default_lang = str_replace('_', '-', app()->getLocale()))
                    @if($language)
                        <ul class="nav nav-tabs mb-4">
                            <li class="nav-item">
                                <a class="nav-link lang_link active" href="#" id="default-link">{{translate('Default')}}</a>
                            </li>
                            @foreach(json_decode($language) as $lang)
                                <li class="nav-item">
                                    <a class="nav-link lang_link" href="#" id="{{$lang}}-link">{{\App\CentralLogics\Helpers::get_language_name($lang).'('.strtoupper($lang).')'}}</a>
                                </li>
                            @endforeach
                        </ul>
                        <div class="form-group lang_form" id="default-form">
                            <label class="input-label" for="exampleFormControlInput1">{{translate('messages.name')}}</label>
                            <input type="text" name="name[]" class="form-control" placeholder="{{ translate('Ex: Category Name') }}" value="{{$category?->getRawOriginal('name')}}"  maxlength="191">
                            <input type="hidden" name="lang[]" value="default">
                        </div>
                        @foreach(json_decode($language) as $lang)
                            <?php
                                if(count($category['translations'])){
                                    $translate = [];
                                    foreach($category['translations'] as $t)
                                    {
                                        if($t->locale == $lang && $t->key=="name"){
                                            $translate[$lang]['name'] = $t->value;
                                        }
                                    }
                                }
                            ?>
                            <div class="form-group d-none lang_form" id="{{$lang}}-form">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('messages.name')}} ({{strtoupper($lang)}})</label>
                                <input id="name" type="text" name="name[]" class="form-control" placeholder="{{translate('messages.new_category')}}" maxlength="191" value="{{$lang==$default_lang?$category['name']:($translate[$lang]['name']??'')}}"  oninvalid="document.getElementById('en-link').click()">
                            </div>
                            <input type="hidden" name="lang[]" value="{{$lang}}">
                        @endforeach
                    @else
                    <div class="form-group lang_form" id="default-form">
                        <label class="input-label" for="exampleFormControlInput1">{{translate('messages.name')}}</label>
                        <input type="text" name="name[]" class="form-control" placeholder="{{ translate('Ex: Category Name') }}" value="{{$category['name']}}"  maxlength="191">
                        <input type="hidden" name="lang[]" value="default">
                    </div>
                    @endif
                    @if ($category->position != 1)
                    <div class="row">
                        <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                                <center>
                                    <img class="initial-18" id="viewer"
                                        src="{{asset('storage/app/public/category')}}/{{$category['image']}}" alt=""/>
                                </center>
                            </div>
                            <div class="form-group mt-2">
                                <label>{{translate('messages.image')}}</label><small class="text-danger">* ( {{translate('messages.ratio')}} 1:1)</small>
                                <div class="custom-file">
                                    <input type="file" name="image" id="customFileEg1" class="custom-file-input"
                                           accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                    <label class="custom-file-label" for="customFileEg1">{{translate('messages.choose')}} {{translate('messages.file')}}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="btn--container justify-content-end">
                        <button id="reset_btn" type="button" class="btn btn--reset">{{translate('messages.reset')}}</button>
                        <button type="submit" class="btn btn--primary">{{translate('messages.update')}}</button>
                    </div>
                </form>
            </div>
            <!-- End Table -->
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
            if(lang == '{{$default_lang}}')
            {
                $(".from_part_2").removeClass('d-none');
            }
            else
            {
                $(".from_part_2").addClass('d-none');
            }
        });
    </script>
    <script>
        $('#reset_btn').click(function(){
            $('#name').val("{{$lang==$default_lang?$category['name']:($translate[$lang]['name']??'')}}");
            $('#viewer').attr('src', "{{asset('storage/app/public/category')}}/{{$category['image']}}");
            $('#customFileEg1').val(null);
        })
    </script>
@endpush
