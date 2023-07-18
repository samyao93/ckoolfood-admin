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
    <title>{{ translate('messages.Verify_otp') }} | {{$app_name??translate('STACKFOOD')}}</title>

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
                <div class="text-center">
                    <img class="mb-4" src="{{asset('/public/assets/admin/img/lock.svg')}}" alt="">
                    <div class="mb-2">
                        {{ translate('A_5_digit_verification_code_has_been') }} <br> {{ translate('sent_to') }} <strong>{{ substr($admin->phone, 0, 3) . str_repeat('X', strlen($admin->phone) - 5) . substr($admin->phone, -2) }}</strong>
                    </div>
                    <div>{{ translate('Enter_the_verification_code') }}</div>
                </div>
                <div class="mt-4">
                    <form action="{{ route('verify-otp') }}" method="POST" class="otp-form">
                        @csrf
                        <input type="hidden" name="reset_token" id="reset_token" value="{{ $token }}">
                        <input type="hidden" name="phone" value="{{ $admin->phone }}">
                        <div class="d-flex align-items-end justify-content-center __gap-15px">
                            <input class="otp-field"  required  type="text" name="opt-field[]" maxlength="1" autocomplete="off">
                            <input class="otp-field" required  type="text" name="opt-field[]" maxlength="1" autocomplete="off">
                            <input class="otp-field" required  type="text" name="opt-field[]" maxlength="1" autocomplete="off">
                            <input class="otp-field"  required type="text" name="opt-field[]" maxlength="1" autocomplete="off">
                            <input class="otp-field" required  type="text" name="opt-field[]" maxlength="1" autocomplete="off">
                        </div>

                        <!-- Store OTP Value -->
                        <input class="otp-value" type="hidden" name="opt-value">
                        <br>
                        <button type="submit" class="btn btn-lg btn-block btn--primary">{{translate('Verify')}}</button>
                    </form>
                </div>
                {{-- <button id="">Send OTP</button> --}}
                <!-- End Form -->
                <div class="d-flex justify-content-between mt-2">
                    <span>{{ translate('Didn`t receive the code?') }}</span>
                    <button class="text--primary resend" onclick="otp_resent()" disabled id="otp-button">{{ translate('Resend_it') }}
                        {{-- (<span class="verifyCounter"></span>s) --}}
                    </button>
                </div>
                <!-- End Content -->
            </div>

        </div>
    </div>
</main>
<!-- ========== END MAIN CONTENT ========== -->

<!-- JS Implementing Plugins -->
<script src="{{asset('public/assets/admin')}}/js/vendor.min.js"></script>

<!-- JS Front -->
<script src="{{asset('public/assets/admin')}}/js/theme.min.js"></script>
<script src="{{asset('public/assets/admin')}}/js/toastr.js"></script>
{!! Toastr::message() !!}

@if ($errors->any())
    <script>
        @foreach($errors->all() as $error)
        toastr.error('{{$error}}', Error, {
            CloseButton: true,
            ProgressBar: true
        });
        @endforeach
    </script>
@endif


<script>

        function otp_resent(token) {

            $.ajax({
                url: "{{ route('otp_resent') }}",
                type: "GET",
                dataType: 'json',
                data: {
                            "token": $('#reset_token').val()
                        },
                        success: function(data) {

                    if (data.errors == 'link_expired') {
                        toastr.error('{{ translate('Link_expired') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }
                    if (data.otp_fail == 'otp_fail') {
                        toastr.error('{{ translate('Failed_to_sent_otp') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }
                    if (data.success == 'otp_send') {
                        toastr.success('{{ translate('Otp_successfull_sent') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }
                }
            });
        }
</script>




<!-- IE Support -->
<script>
    if (/MSIE \d|Trident.*rv:/.test(navigator.userAgent)) document.write('<script src="{{asset('public/assets/admin')}}/vendor/babel-polyfill/polyfill.min.js"><\/script>');
</script>

<script>
  $(document).ready(function () {
    $(".otp-form *:input[type!=hidden]:first").focus();
    let otp_fields = $(".otp-form .otp-field"),
      otp_value_field = $(".otp-form .otp-value");
    otp_fields
      .on("input", function (e) {
        $(this).val(
          $(this)
            .val()
            .replace(/[^0-9]/g, "")
        );
        let opt_value = "";
        otp_fields.each(function () {
          let field_value = $(this).val();
          if (field_value != "") opt_value += field_value;
        });
        otp_value_field.val(opt_value);
      })
      .on("keyup", function (e) {
        let key = e.keyCode || e.charCode;
        if (key == 8 || key == 46 || key == 37 || key == 40) {
          // Backspace or Delete or Left Arrow or Down Arrow
          $(this).prev().focus();
        } else if (key == 38 || key == 39 || $(this).val() != "") {
          // Right Arrow or Top Arrow or Value not empty
          $(this).next().focus();
        }
      })
      .on("paste", function (e) {
        let paste_data = e.originalEvent.clipboardData.getData("text");
        let paste_data_splitted = paste_data.split("");
        $.each(paste_data_splitted, function (index, value) {
          otp_fields.eq(index).val(value);
        });
      });
  });




  $(document).ready(function() {
  var otpButton = $("#otp-button");
  var countdownTimer;

  function startCountdown() {
    otpButton.prop("disabled", true);
    otpButton.addClass("resend");
    var countdown = 30;
    countdownTimer = setInterval(function() {
      otpButton.text("Resend it (" + countdown + ")");
      countdown--;
      if (countdown < 0) {
        clearInterval(countdownTimer);
        otpButton.prop("disabled", false);
        otpButton.addClass("resend");
        otpButton.text("Resend it");
      }
    }, 1000);
  }

  otpButton.click(function() {
    // TODO: Send OTP code here
    startCountdown();
  });
  startCountdown();
});


</script>


</body>
</html>
