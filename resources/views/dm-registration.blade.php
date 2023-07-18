{{-- @extends('layouts.landing.app') --}}
@extends('layouts.landing.app')
@section('title', translate('messages.deliveryman_registration'))
@push('css_or_js')
    {{-- <link rel="stylesheet" href="{{ asset('public/assets/admin') }}/css/toastr.css"> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/css/intlTelInput.css"
        integrity="sha512-gxWow8Mo6q6pLa1XH/CcH8JyiSDEtiwJV78E+D+QP0EVasFs8wKXq16G8CLD4CJ2SnonHr4Lm/yY2fSI2+cbmw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="{{ asset('public/assets/landing') }}/css/style.css" />
@endpush

@section('content')
     <!-- Page Header Gap -->
     <div class="h-148px"></div>
     <!-- Page Header Gap -->

    <section class="m-0">
        <div class="container">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col-sm mb-2 mb-sm-0">
                        <h1 class="page-header-title text-center"><i class="tio-add-circle-outlined"></i>
                            {{ translate('messages.deliveryman_application') }}</h1>
                    </div>
                </div>
            </div>
            <!-- End Page Header -->
            <div class="row">
                <div class="card shadow-sm col-12">
                    <form class="card-body" action="{{ route('deliveryman.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <small class="nav-subtitle">{{ translate('messages.deliveryman') }}
                            {{ translate('messages.info') }}</small>
                        <br>
                        <div class="row mt-3">
                            <div class="col-md-6 col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.first') }}
                                        {{ translate('messages.name') }}</label>
                                    <input type="text" name="f_name" class="form-control"
                                        placeholder="{{ translate('messages.first') }} {{ translate('messages.name') }}" required
                                        value="{{ old('f_name') }}">
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.last') }}
                                        {{ translate('messages.name') }}</label>
                                    <input type="text" name="l_name" class="form-control"
                                        placeholder="{{ translate('messages.last') }} {{ translate('messages.name') }}"
                                        value="{{ old('l_name') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6 col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.email') }}</label>
                                    <input type="email" name="email" class="form-control"
                                        placeholder="{{ translate('messages.Ex :') }} ex@example.com" value="{{ old('email') }}" required>
                                </div>
                            </div>
                            <div class="col-sm-6 col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.deliveryman') }}
                                        {{ translate('messages.type') }}</label>
                                    <select name="earning" class="form-control">
                                        <option value="1">{{ translate('messages.freelancer') }}</option>
                                        <option value="0">{{ translate('messages.salary_based') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-6 col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.zone') }}</label>
                                    <select name="zone_id" class="form-control js-select2-custom" required
                                        data-placeholder="{{ translate('messages.select') }} {{ translate('messages.zone') }}">
                                        <option value="" readonly="true" hidden="true">{{ translate('messages.select') }}
                                            {{ translate('messages.zone') }}</option>
                                        @foreach (\App\Models\Zone::active()->get(['id','name']) as $zone)
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
                            <div class="col-sm-6 col-12">
                                <div class="form-group">
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
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.identity') }}
                                        {{ translate('messages.type') }}</label>
                                    <select name="identity_type" class="form-control">
                                        <option value="passport">{{ translate('messages.passport') }}</option>
                                        <option value="driving_license">{{ translate('messages.driving') }}
                                            {{ translate('messages.license') }}</option>
                                        <option value="nid">{{ translate('messages.nid') }}</option>
                                        <option value="restaurant_id">{{ translate('messages.restaurant') }}
                                            {{ translate('messages.id') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.identity') }}
                                        {{ translate('messages.number') }}</label>
                                    <input type="text" name="identity_number" class="form-control"
                                        value="{{ old('identity_number') }}" placeholder="{{ translate('messages.Ex :') }} DH-23434-LS" required>
                                </div>
                            </div>
                            <div class="col-md-12 col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.identity') }}
                                        {{ translate('messages.image') }}</label>
                                    <div>
                                        <div class="row" id="coba"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <small class="nav-subtitle text-capitalize">{{ translate('messages.login') }}
                            {{ translate('messages.info') }}</small>
                        <br>
                        <div class="row mt-3">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="input-label" for="phone">{{ translate('messages.phone') }}</label>
                                    <div class="input-group">
                                        <input type="tel" name="tel" id="phone" placeholder="{{ translate('messages.Ex :') }} 017********"
                                            class="form-control" value="{{ old('tel') }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.password') }}
                                        <span class="input-label-secondary ps-1" title="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"><img src="{{ asset('/public/assets/admin/img/info-circle.svg') }}" alt="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"></span>
                                    </label>

                                    <input type="text" id="password" name="password" class="form-control"
                                    pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"
                                    placeholder="{{ translate('messages.Ex :') }} Abc@1234"
                                        value="{{ old('password') }}" required>

                                </div>
                            </div>
                        </div>
                        <div class="row d-flex justify-content-center">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <center class="pt-4">
                                        <img class="initial-95" style="max-width: 130px" id="viewer"
                                            src="{{ asset('public/assets/admin/img/400x400/img2.jpg') }}"
                                            alt="delivery-man image" />
                                    </center>
                                    <label  class="input-label">{{ translate('messages.deliveryman') }} {{ translate('messages.image') }}<small class="text-danger">* ( {{ translate('messages.ratio') }} 1:1 )</small></label>
                                    <div class="custom-file">
                                        <input type="file" name="image" id="customFileEg1" class="form-control"
                                            accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit"
                            class="btn btn-primary float-right submitBtn">{{ translate('messages.submit') }}</button>
                    </form>
                </div>
            </div>
        </div>

    </section>
  <!-- Page Header Gap -->
  <div class="h-148px"></div>
  <!-- Page Header Gap -->
@endsection

@push('script_2')
    {{-- <script src="{{ asset('public/assets/admin') }}/js/toastr.js"></script>
    {!! Toastr::message() !!} --}}

    @if ($errors->any())
        <script>
            @foreach ($errors->all() as $error)
                toastr.error('{{ $error }}', Error, {
                CloseButton: true,
                ProgressBar: true
                });
            @endforeach
        </script>
    @endif
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/js/intlTelInput.min.js"
        integrity="sha512-QMUqEPmhXq1f3DnAVdXvu40C8nbTgxvBGvNruP6RFacy3zWKbNTmx7rdQVVM2gkd2auCWhlPYtcW2tHwzso4SA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/js/intlTelInput-jquery.min.js"
        integrity="sha512-hkmipUFWbNGcKnR0nayU95TV/6YhJ7J9YUAkx4WLoIgrVr7w1NYz28YkdNFMtPyPeX1FrQzbfs3gl+y94uZpSw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/js/utils.min.js"
             integrity="sha512-lv6g7RcY/5b9GMtFgw1qpTrznYu1U4Fm2z5PfDTG1puaaA+6F+aunX+GlMotukUFkxhDrvli/AgjAu128n2sXw=="
             crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->
    <link rel="shortcut icon" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/img/flags.png"
        type="image/x-icon">
    <link rel="shortcut icon" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/img/flags@2x.png"
        type="image/x-icon">
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
        @php($country = \App\Models\BusinessSetting::where('key', 'country')->first())
        var phone = $("#phone").intlTelInput({
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/8.4.6/js/utils.js",
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
                rowHeight: '120px',
                groupClassName: 'col-lg-2 col-md-4 col-sm-4 col-6',
                maxFileSize: '',
                placeholderImage: {
                    image: '{{ asset('public/assets/admin/img/400x400/img2.jpg') }}',
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

@endpush
