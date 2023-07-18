<!DOCTYPE html>
    <?php

    $log_email_succ = session()->get('log_email_succ');
    ?>
<html dir="{{ $site_direction }}" lang="{{ $locale }}" class="{{ $site_direction === 'rtl'?'active':'' }}">
    <head>
    <!-- Required Meta Tags Always Come First -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    @php
        $app_name = \App\CentralLogics\Helpers::get_business_settings('business_name', false);
        $icon = \App\CentralLogics\Helpers::get_business_settings('icon', false);
    @endphp
    <!-- Title -->
    <title>{{ translate('messages.Password_recovery') }} | {{$app_name??translate('STACKFOOD')}}</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{asset($icon ? 'storage/app/public/business/'.$icon : 'public/favicon.ico')}}">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&amp;display=swap" rel="stylesheet">
    <!-- CSS Implementing Plugins -->
    <link rel="stylesheet" href="{{asset('public/assets/admin')}}/css/vendor.min.css">
    <link rel="stylesheet" href="{{asset('public/assets/admin')}}/vendor/icon-set/style.css">
    <!-- CSS Front Template -->
    <link rel="stylesheet" href="{{asset('public/assets/admin')}}/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{asset('public/assets/admin')}}/css/theme.minc619.css?v=1.0">
    <link rel="stylesheet" href="{{asset('public/assets/admin')}}/css/style.css">
    <link rel="stylesheet" href="{{asset('public/assets/admin')}}/css/toastr.css">
</head>

<body>
    <!-- ========== MAIN CONTENT ========== -->
    <main id="content" role="main" class="main auth-bg">
        <!-- Content -->
        <div class="d-flex flex-wrap align-items-center justify-content-between">
            <div class="auth-content">
                <div class="content">
                    <h2 class="title text-uppercase">{{translate('messages.welcome_to_app',['app_name'=>$app_name??'STACKFOOD'])}}</h2>
                    <p>
                        {{translate('Manage your app & website easily')}}
                    </p>
                </div>
            </div>
            <div class="auth-wrapper">
                <div class="auth-wrapper-body auth-form-appear">
                    @php($systemlogo=\App\Models\BusinessSetting::where(['key'=>'logo'])->first())
                    <a class="auth-logo mb-5" href="javascript:">
                        <img class="z-index-2"
                        @if (isset($systemlogo))
                        src="{{ asset('storage/app/public/business/' . $systemlogo->value) }}"
                        @else
                        src="{{asset('/public/assets/admin/img/auth-fav.png')}}"
                        @endif
                        >
                    </a>
                    <div class="text-center">
                        <div class="auth-header mb-5">
                            <h2 class="signin-txt">{{ translate('messages.Password_reset')}}</h2>
                            {{-- <p class="text-capitalize">{{ translate('Want To Login Your Restaurants') }}? --}}

                            </p>

                        </div>
                    </div>
                    <!-- Content -->
                    <label class="badge badge-soft-success float-right initial-1">
                        {{translate('messages.software_version')}} : {{env('SOFTWARE_VERSION')}}
                    </label>
                    <!-- Form -->
                    <form action="{{ route('reset-password-submit') }}" method="POST">
                        @csrf
                        <input type="hidden" name="reset_token" value="{{ $token }}">
                        <!-- Form Group -->
                        <div class="js-form-message form-group mb-4">
                            <label class="input-label">
                                {{translate('New Password')}}
                                <span class="form-label-secondary" data-toggle="tooltip" data-placement="right"
                                data-original-title="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"><img src="{{ asset('/public/assets/admin/img/info-circle.svg') }}" alt="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"></span>

                            </label>
                            <div class="input-group input-group-merge">
                                <input type="password" class="js-toggle-password form-control form-control-lg"
                                        name="password" id="signupSrPassword" placeholder="{{translate('messages.password_length_placeholder',['length'=>'8+'])}}"
                                        aria-label="{{translate('messages.password_length_placeholder',['length'=>'8+'])}}" required
                                        data-msg="{{translate('messages.invalid_password_warning')}}"
                                        data-hs-toggle-password-options='{
                                                    "target": "#new-pass",
                                        "defaultClass": "tio-hidden-outlined",
                                        "showClass": "tio-visible-outlined",
                                        "classChangeTarget": "#new-pass-icon"
                                        }'
                                        pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"

                                        >
                                <div id="new-pass" class="input-group-append">
                                    <a class="input-group-text" href="javascript:">
                                        <i id="new-pass-icon" class="tio-visible-outlined"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!-- End Form Group -->
                        <!-- Form Group -->
                        <div class="js-form-message form-group mb-4">
                            <label class="input-label">
                                <span class="d-flex justify-content-between align-items-center">
                                    {{translate('Confirm Password')}}
                                </span>
                            </label>
                            <div class="input-group input-group-merge">
                                <input type="password" class="js-toggle-password form-control form-control-lg"
                                        name="confirm_password" id="signupSrPassword" placeholder="{{translate('messages.password_length_placeholder',['length'=>'8+'])}}"
                                        aria-label="{{translate('messages.password_length_placeholder',['length'=>'8+'])}}" required
                                        data-msg="{{translate('messages.invalid_password_warning')}}"
                                        data-hs-toggle-password-options='{
                                                    "target": "#conf-pass",
                                        "defaultClass": "tio-hidden-outlined",
                                        "showClass": "tio-visible-outlined",
                                        "classChangeTarget": "#conf-pass-icon"
                                        }'
                                        pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"

                                        >
                                <div id="conf-pass" class="input-group-append">
                                    <a class="input-group-text" href="javascript:">
                                        <i id="conf-pass-icon" class="tio-visible-outlined"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!-- End Form Group -->
                        <button type="submit" class="btn btn-block btn--primary">{{translate('Change Password')}}</button>
                    </form>
                    <!-- End Form -->

                    <!-- End Content -->
                </div>

            </div>
        </div>
    </main>
















<!-- JS Implementing Plugins -->
<script src="{{asset('public/assets/admin')}}/js/vendor.min.js"></script>

<!-- JS Front -->
<script src="{{asset('public/assets/admin')}}/js/theme.min.js"></script>
<script src="{{asset('public/assets/admin')}}/js/toastr.js"></script>
{!! Toastr::message() !!}

@if ($errors->any())
    <script>
        @foreach($errors->all() as $error)
        toastr.error('{{translate($error)}}', Error, {
            CloseButton: true,
            ProgressBar: true
        });
        @endforeach
    </script>
@endif

<!-- JS Plugins Init. -->
<script>
    $(document).on('ready', function () {
        // INITIALIZATION OF SHOW PASSWORD
        // =======================================================
        $('.js-toggle-password').each(function () {
            new HSTogglePassword(this).init()
        });

        // INITIALIZATION OF FORM VALIDATION
        // =======================================================
        $('.js-validate').each(function () {
            $.HSCore.components.HSValidation.init($(this));
        });
    });
</script>

{{-- recaptcha scripts start --}}
@if(isset($recaptcha) && $recaptcha['status'] == 1)
    <script type="text/javascript">
        var onloadCallback = function () {
            grecaptcha.render('recaptcha_element', {
                'sitekey': '{{ \App\CentralLogics\Helpers::get_business_settings('recaptcha')['site_key'] }}'
            });
        };
    </script>
    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
    <script>
        $("#form-id").on('submit',function(e) {
            var response = grecaptcha.getResponse();

            if (response.length === 0) {
                e.preventDefault();
                toastr.error("{{translate('messages.Please check the recaptcha')}}");
            }
        });
    </script>
@endif
{{-- recaptcha scripts end --}}

<script>
        function reloadCaptcha() {
            $.ajax({
                url: "{{ route('reload-captcha') }}",
                type: "GET",
                dataType: 'json',
                beforeSend: function () {
                    $('#loading').show()
                },
                success: function(data) {
                    $('#reload-captcha').html(data.view);
                },
                complete: function () {
                    $('#loading').hide()
                }
            });
        }
</script>

@if(env('APP_MODE')=='demo')
    <script>
        function copy_cred() {
            $('#signinSrEmail').val('admin@admin.com');
            $('#signupSrPassword').val('12345678');
            toastr.success('Copied successfully!', 'Success!', {
                CloseButton: true,
                ProgressBar: true
            });
        }
    </script>
@endif

<!-- IE Support -->
<script>
    if (/MSIE \d|Trident.*rv:/.test(navigator.userAgent)) document.write('<script src="{{asset('public//assets/admin')}}/vendor/babel-polyfill/polyfill.min.js"><\/script>');
</script>
</body>
</html>
