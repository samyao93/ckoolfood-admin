@extends('layouts.vendor.app')
@section('title','Employee Edit')


@section('content')
<div class="content container-fluid">
    <!-- Page Header -->
     <div class="page-header">
        <h2 class="page-header-title text-capitalize">
            <div class="card-header-icon d-inline-flex mr-2 img">
                <img src="{{asset('/public/assets/admin/img/resturant-panel/page-title/employee-role.png')}}" alt="public">
            </div>
            <span>
                {{translate('messages.Employee')}} {{translate('messages.update')}}
            </span>
        </h2>
    </div>
    <!-- End Page Header -->

    <!-- Content Row -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">
                <span class="card-header-icon">
                    <i class="tio-user"></i>
                </span>
                <span>
                    {{ translate('General Information') }}
                </span>
            </h5>
        </div>
        <div class="card-body">
            <form action="{{route('vendor.employee.update',[$e['id']])}}" method="post" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="f_name">{{translate('messages.irst')}} {{translate('messages.name')}}</label>
                            <input type="text" name="f_name" value="{{$e['f_name']}}" class="form-control h--45px" id="f_name"
                                    placeholder="{{translate('messages.first_name')}}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="l_name">{{translate('messages.last')}} {{translate('messages.name')}}</label>
                            <input type="text" name="l_name" value="{{$e['l_name']}}" class="form-control h--45px" id="l_name"
                                    placeholder="{{translate('messages.last_name')}}">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="phone">{{translate('messages.phone')}}</label>
                            <input type="tel" value="{{$e['phone']}}" required name="phone" class="form-control h--45px" id="phone"
                                    placeholder="{{ translate('Ex : +88017********') }}">
                        </div>

                        <div class="form-group mb-md-0">
                            <label class="form-label" for="role_id">{{translate('messages.Role')}}</label>
                            <select class="form-control h--45px w-100" name="role_id">
                                    <option value="" selected disabled>{{translate('messages.select')}} {{translate('messages.Role')}}</option>
                                    @foreach($rls as $r)
                                        <option
                                            value="{{$r->id}}" {{$r['id']==$e['employee_role_id']?'selected':''}}>{{$r->name}}</option>
                                    @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="h-100 d-flex flex-column">
                            <div class="card h-100">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="form-label text-center mb-3">
                                        {{translate('messages.employee_image')}}
                                    </h5>
                                    <center class="my-auto">
                                        <img class="initial-78" id="viewer"
                                        onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                        src="{{asset('storage/app/public/vendor')}}/{{$e['image']}}" alt="Employee thumbnail"/>
                                    </center>
                                    <label class="form-label mt-3">{{ translate('Employee image size max 2 MB') }} <span class="text-danger">*</span></label>
                                    <div class="custom-file">
                                        <input type="file" name="image" id="customFileUpload" class="custom-file-input"
                                            accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                        <label class="custom-file-label" for="customFileUpload">{{translate('messages.choose')}} {{translate('messages.file')}}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="card mt-4">
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
                        <div class="row gy-2">
                            <div class="col-md-4">
                                <label class="form-label" for="email">{{translate('messages.email')}}</label>
                                <input type="email" value="{{$e['email']}}" name="email" class="form-control h--45px" id="email" placeholder="{{ translate('messages.Ex :') }} ex@gmail.com">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="password">{{translate('messages.password')}}<small> ( {{translate('messages.enter_if_you_want_to_change')}} )</small>
                                    <span class="input-label-secondary ps-1" title="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"><img src="{{ asset('/public/assets/admin/img/info-circle.svg') }}" alt="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"></span>
                                </label>
                                <input type="text" name="password"
                                pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"

                                class="form-control h--45px" id="password" placeholder="{{translate('messages.password_length_placeholder',['length'=>'8+'])}}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="confirm-password">
                                    {{translate('messages.confirm_password')}}
                                </label>
                                <input type="text" name="confirm-password"
                                pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"

                                class="form-control h--45px" id="confirm-password" placeholder="{{ translate('Ex : Confirm Password') }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="btn--container justify-content-end mt-3">
                    <button type="reset" id="reset_btn" class="btn btn--reset">{{translate('messages.reset')}}</button>
                    <button type="submit" class="btn btn--primary">{{translate('messages.update')}}</button>
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
                $('#viewer').attr('src','{{asset('storage/app/public/vendor')}}/{{$e['image']}}');
            })
        </script>
@endpush
