@extends('layouts.admin.app')
@section('title',translate('Employee Add'))
@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="content container-fluid">
    <!-- Page Heading -->

    <div class="page-header">
        <h1 class="page-header-title mb-2 text-capitalize">
            <div class="card-header-icon d-inline-flex mr-2 img">
                <img src="{{asset('/public/assets/admin/img/employee.png')}}" alt="public">
            </div>
            <span>
                {{ translate('Add New Employee') }}
            </span>
        </h1>
    </div>


    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <form action="{{route('admin.employee.add-new')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title">
                            <span class="card-header-icon">
                                <i class="tio-user"></i>
                            </span>
                            <span>
                                {{ translate('Genaral Information') }}
                            </span>
                        </h5>
                    </div>
                    <div class="card-body pb-0">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <div class="form-group mb-0">
                                            <label class="form-label " for="fname">{{translate('messages.first')}} {{translate('messages.name')}}</label>
                                            <input type="text" name="f_name" class="form-control h--45px" id="fname"
                                                    placeholder="{{ translate('Ex: John') }}" value="{{old('f_name')}}" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group mb-0">
                                            <label class="form-label " for="lname">{{translate('messages.last')}} {{translate('messages.name')}}</label>
                                            <input type="text" name="l_name" class="form-control h--45px" id="lname" value="{{old('l_name')}}"
                                                    placeholder="{{ translate('Ex: Doe') }}" value="{{old('name')}}">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group mb-0">
                                            <label class="form-label" for="title">{{translate('messages.zone')}}</label>
                                            <select name="zone_id" id="zone_id" class="form-control h--45px js-select2-custom">
                                                @if(!isset(auth('admin')->user()->zone_id))
                                                <option value="" {{!isset($e->zone_id)?'selected':''}}>{{translate('messages.all')}}</option>
                                                @endif
                                                @php($zones=\App\Models\Zone::all())
                                                @foreach($zones as $zone)
                                                    <option value="{{$zone['id']}}">{{$zone['name']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group mb-0">
                                            <label class="form-label " for="role_id">{{translate('messages.Role')}}</label>
                                            <select class="w-100 form-control h--45px js-select2-custom" name="role_id" id="role_id"  required>
                                                <option value="" selected disabled>{{translate('messages.select')}} {{translate('messages.Role')}}</option>
                                                @foreach($rls as $r)
                                                    <option value="{{$r->id}}">{{$r->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label " for="phone">{{translate('messages.phone')}}</label>
                                            <input type="tel" name="phone" value="{{old('phone')}}" class="form-control h--45px" id="phone"
                                                placeholder="{{ translate('Ex : +8801******') }}" required>
                                        </div>
                                    </div>
                                    {{-- <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="form-label " for="address">{{translate('messages.address')}}</label>
                                            <textarea name="" id="" class="form-control h--120px"></textarea>
                                        </div>
                                    </div> --}}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="h-100 d-flex flex-column justify-content-center">
                                    <label class="form-label d-block text-center mt-auto mb-3">
                                        {{ translate('Employee Image') }} <span class="text-danger">({{ translate('Ratio 1:1') }})</span>
                                    </label>
                                    <center class="mt-auto mb-auto">
                                        <img class="initial-24" id="viewer"
                                        src="{{asset('public/assets/admin/img/100x100/user.png')}}" alt="Employee thumbnail"/>
                                    </center>
                                    <div class="form-group mt-3">
                                        <div class="custom-file">
                                            <input type="file" name="image" id="customFileUpload" class="custom-file-input h--45px"
                                                accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" required>
                                            <label class="custom-file-label  h--45px" for="customFileUpload">{{translate('messages.choose')}} {{translate('messages.file')}}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title">
                            <span class="card-header-icon">
                                <i class="tio-user"></i>
                            </span>
                            <span>
                                {{translate('messages.account')}} {{translate('messages.info')}}
                            </span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label " for="email">{{translate('messages.email')}}</label>
                                <input type="email" name="email" value="{{old('email')}}" class="form-control h--45px" id="email"
                                    placeholder="{{ translate('Ex : ex@gmail.com') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label " for="password">{{translate('messages.password')}}
                                    <span class="input-label-secondary ps-1" title="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"><img src="{{ asset('/public/assets/admin/img/info-circle.svg') }}" alt="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"></span>
                                </label>
                                    <div class="input-group input-group-merge">
                                    <input type="text" name="password" class="form-control h--45px" id="password" value="{{old('password')}}"
                                    pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"
                                    placeholder="{{translate('messages.password_length_placeholder',['length'=>'8+'])}}" required>
                                    <div class="js-toggle-password-target-1 input-group-append">
                                        <a class="input-group-text" href="javascript:;">
                                            <i class="js-toggle-passowrd-show-icon-1 tio-visible-outlined"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label " for="password">{{translate('messages.confirm_password')}}</label>
                                    <div class="input-group input-group-merge">
                                    <input type="text" name="password" class="form-control h--45px" id="password" value="{{old('password')}}"
                                    pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"

                                    placeholder="{{translate('messages.password_length_placeholder',['length'=>'8+'])}}" required>
                                    <div class="js-toggle-password-target-1 input-group-append">
                                        <a class="input-group-text" href="javascript:;">
                                            <i class="js-toggle-passowrd-show-icon-1 tio-visible-outlined"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="btn--container justify-content-end">
                    <!-- Static Button -->
                    <button type="reset" id="reset_btn" class="btn btn--reset">{{translate('messages.reset')}}</button>
                    <!-- Static Button -->
                    <button type="submit" class="btn btn--primary">{{translate('messages.submit')}}</button>
                </div>
            </form>
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

        $("#customFileUpload").change(function () {
            readURL(this);
        });

        $(".js-example-theme-single").select2({
            theme: "classic"
        });

        $(".js-example-responsive").select2({
            width: 'resolve'
        });

    </script>

    <script>
        $('#reset_btn').click(function(){
            location.reload(true);
            $('#zone_id').val(null).trigger('change');
            $('#role_id').val(null).trigger('change');
            $('#viewer').attr('src','{{asset('public\assets\admin\img\100x100\user.png')}}');
        })
    </script>
@endpush
