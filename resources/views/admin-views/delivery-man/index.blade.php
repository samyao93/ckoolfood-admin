@extends('layouts.admin.app')

@section('title', translate('messages.add_delivery_man'))

@push('css_or_js')
    <link rel="stylesheet" href="{{asset('/public/assets/admin/css/intlTelInput.css')}}" />
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title mb-2 text-capitalize">
                <div class="card-header-icon d-inline-flex mr-2 img">
                    <img src="{{asset('/public/assets/admin/img/delivery-man.png')}}" alt="public">
                </div>
                <span>
                    {{ translate('messages.add_new_deliveryman') }}
                </span>
            </h1>
        </div>
        <!-- End Page Header -->
        <form action="{{ route('admin.delivery-man.store') }}" method="post" enctype="multipart/form-data">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <span class="card-title-icon"><i class="tio-user"></i></span>
                        <span>
                            {{ translate('messages.general_info') }}
                        </span>
                    </h5>
                </div>
                @csrf
                <div class="card-body pb-2">
                    <div class="row g-3">
                        <div class="col-lg-8">
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <div class="form-group m-0">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ translate('messages.first') }}
                                            {{ translate('messages.name') }}</label>
                                        <input type="text" name="f_name" class="form-control h--45px"
                                            placeholder="{{ translate('Ex: Jhone') }}" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group m-0">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ translate('messages.last') }}
                                            {{ translate('messages.name') }}</label>
                                        <input type="text" name="l_name" class="form-control h--45px"
                                            placeholder="{{ translate('Ex: Joe') }}" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group m-0">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ translate('messages.email') }}</label>
                                        <input type="email" name="email" class="form-control h--45px"
                                            placeholder="{{ translate('Ex : ex@example.com') }}" required>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group m-0">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ translate('messages.zone') }}</label>
                                        <select name="zone_id" class="form-control js-select2-custom h--45px" required
                                            data-placeholder="{{ translate('messages.select') }} {{ translate('messages.zone') }}">
                                            <option value="" readonly="true" hidden="true">{{ translate('Ex: XYZ Zone') }}</option>
                                            @foreach (\App\Models\Zone::where('status',1)->get(['id','name']) as $zone)
                                                @if (isset(auth('admin')->user()->zone_id))
                                                    @if (auth('admin')->user()->zone_id == $zone->id)
                                                        <option value="{{ $zone->id }}" selected>{{ $zone->name }}
                                                        </option>
                                                    @endif
                                                @else
                                                    <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group m-0">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ translate('messages.Vehicle') }}</label>
                                        <select name="vehicle_id" class="form-control js-select2-custom h--45px" required
                                            data-placeholder="{{ translate('messages.select') }} {{ translate('messages.vehicle') }}">
                                            <option value="" readonly="true" hidden="true">{{ translate('messages.select') }} {{ translate('messages.vehicle') }}</option>
                                            @foreach (\App\Models\Vehicle::where('status',1)->get(['id','type']) as $v)
                                                        <option value="{{ $v->id }}" >{{ $v->type }}
                                                        </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group m-0">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ translate('messages.deliveryman') }}
                                            {{ translate('messages.type') }}</label>
                                        <select name="earning" class="form-control h--45px">
                                            <option value="" readonly="true" hidden="true">{{ translate('messages.delivery_man_type') }}</option>
                                            <option value="1">{{ translate('messages.freelancer') }}</option>
                                            <option value="0">{{ translate('messages.salary_based') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group m-0">
                                <label class="d-block mb-lg-5 text-center">{{ translate('messages.delivery_man_image') }} <small class="text-danger">* ( {{ translate('messages.ratio') }} {{ translate('100x100') }} )</small></label>
                                <center>
                                    <img class="initial-24" id="viewer"
                                        src="{{ asset('public/assets/admin/img/100x100/user.png') }}"
                                        alt="delivery-man image" />
                                </center>
                                <label class="d-block mb-lg-3 "></label>
                                <div class="custom-file">
                                    <input type="file" name="image" id="customFileEg1" class="custom-file-input h--45px"
                                        accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" required>
                                    <label class="custom-file-label" for="customFileEg1">{{ translate('messages.choose') }}
                                        {{ translate('messages.file') }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="row g-3">
                                <div class="col-sm-6 col-lg-12">
                                    <div class="form-group m-0">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ translate('messages.identity') }}
                                            {{ translate('messages.type') }}</label>
                                        <select name="identity_type" class="form-control h--45px">
                                            <option value="passport">{{ translate('messages.passport') }}</option>
                                            <option value="driving_license">{{ translate('messages.driving') }}
                                                {{ translate('messages.license') }}</option>
                                            <option value="nid">{{ translate('messages.nid') }}</option>
                                            <option value="restaurant_id">{{ translate('messages.restaurant') }}
                                                {{ translate('messages.id') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-12">
                                    <div class="form-group m-0">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ translate('messages.identity') }}
                                            {{ translate('messages.number') }}</label>
                                        <input type="text" name="identity_number" class="form-control h--45px"
                                            placeholder="{{ translate('Ex : DH-23434-LS') }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="form-group m-0">
                                <label class="input-label"
                                    for="exampleFormControlInput1">{{ translate('messages.identity') }}
                                    {{ translate('messages.image') }}</label>
                                <div>
                                    <div class="row" id="coba"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title">
                        <span class="card-header-icon"><i class="tio-user"></i></span>
                        <span>{{ translate('messages.account_info') }}</span>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="form-group m-0">
                                <label class="input-label" for="phone">{{ translate('messages.phone') }}</label>
                                <div class="input-group">
                                    <input type="tel" name="phone" id="phone" placeholder="{{ translate('Ex : 017********') }}"
                                        class="form-control h--45px" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group m-0">
                                <label class="input-label"
                                    for="exampleFormControlInput1">{{ translate('messages.password') }}
                                    <span class="input-label-secondary ps-1" title="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"><img src="{{ asset('/public/assets/admin/img/info-circle.svg') }}" alt="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"></span>
                                </label>
                                <input type="text" name="password"
                                pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"
                                class="form-control h--45px" placeholder="{{ translate('Ex: 8+ Character') }}"
                                    required>
                            </div>
                        </div>
                        <!-- This is Static -->
                        <div class="col-md-4">
                            <div class="form-group m-0">
                                <label class="input-label"
                                for="exampleFormControlInput1">{{ translate('messages.confirm_password') }}</label>
                                <input type="text" name="password" class="form-control h--45px"
                                pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"
                                placeholder="{{ translate('Ex: 8+ Character') }}"
                                required>
                            </div>
                        </div>
                        <!-- This is Static -->
                    </div>
                </div>
            </div>
            <div class="btn--container mt-4 justify-content-end">
                <button type="reset" id="reset_btn" class="btn btn--reset">{{ translate('messages.reset') }}</button>
                <button type="submit" class="btn btn--primary submitBtn">{{ translate('messages.submit') }}</button>
            </div>
        </form>
    </div>

@endsection

@push('script_2')
    <script src="{{asset('public/assets/admin/js/intlTelInput.js')}}"></script>
    <script src="{{asset('public/assets/admin/js/intlTelInput-jquery.min.js')}}"></script>

    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function() {
            readURL(this);
        });
        <?php
            $country = \App\Models\BusinessSetting::where('key', 'country')->first();
        ?>
        var phone = $("#phone").intlTelInput({
            utilsScript: "{{asset('public/assets/admin/js/intlTellInput-util.min.js')}}",
            autoHideDialCode: true,
            autoPlaceholder: "ON",
            dropdownContainer: document.body,
            formatOnDisplay: true,
            hiddenInput: "phone",
            initialCountry: "{{ $country ? $country->value : auto }}",
            placeholderNumberType: "MOBILE",
            separateDialCode: true
        });
        // $("#phone").on('change', function(){
        //     $(this).val(phone.getNumber());
        // })
    </script>

    <script src="{{ asset('public/assets/admin/js/spartan-multi-image-picker.js') }}"></script>
    <script type="text/javascript">
        $(function() {
            $("#coba").spartanMultiImagePicker({
                fieldName: 'identity_image[]',
                maxCount: 5,
                rowHeight: '140px',
                groupClassName: 'col-6',
                maxFileSize: '',
                placeholderImage: {
                    image: '{{ asset('public/assets/admin/img/100x100/user2.png') }}',
                    width: '100%'
                },
                dropFileLabel: "Drop Here",
                onAddRow: function(index, file) {

                },
                onRenderedPreview: function(index) {

                },
                onRemoveRow: function(index) {

                },
                onExtensionErr: function(index, file) {
                    toastr.error('{{ translate('messages.please_only_input_png_or_jpg_type_file') }}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                },
                onSizeErr: function(index, file) {
                    toastr.error('{{ translate('messages.file_size_too_big') }}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });
        });
    </script>
    <script>
        $('#reset_btn').click(function(){
            $('#viewer').attr('src','{{asset('public/assets/admin/img/900x400/img1.jpg')}}');
            $('#coba').attr('src','{{asset('public/assets/admin/img/900x400/img1.jpg')}}');
        })
    </script>
@endpush
