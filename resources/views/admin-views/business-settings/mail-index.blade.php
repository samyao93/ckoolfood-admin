@extends('layouts.admin.app')

@section('title',translate('messages.settings'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{asset('public/assets/admin/img/email.png')}}" class="w--26" alt="">
                </span>
                <span>{{ translate('messages.smtp') }} {{ translate('messages.mail') }}
                        {{ translate('messages.setup') }}
                </span>
            </h1>
            @include('admin-views.business-settings.partials.third-party-links')
        </div>
        <!-- End Page Header -->

        <div class="card min-h-60vh">
            <div class="card-header card-header-shadow pb-0">
                <div class="d-flex flex-wrap justify-content-between w-100 row-gap-1">
                    <ul class="nav nav-tabs nav--tabs border-0 gap-2">
                        <li class="nav-item mr-2 mr-md-4">
                            <a href="#mail-config" data-toggle="tab" class="nav-link pb-2 px-0 pb-sm-3 active">
                                <img src="{{asset('/public/assets/admin/img/mail-config.png')}}" alt="">
                                <span>{{translate('Mail_Config')}}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#test-mail" data-toggle="tab" class="nav-link pb-2 px-0 pb-sm-3">
                                <img src="{{asset('/public/assets/admin/img/test-mail.png')}}" alt="">
                                <span>{{translate('Send_Test_Mail')}}</span>
                            </a>
                        </li>
                    </ul>
                    <div class="py-1">
                        <div class="text--primary-2 d-flex flex-wrap align-items-center" type="button" data-toggle="modal" data-target="#works-modal">
                            <strong class="mr-2">{{translate('How_it_Works')}}</strong>
                            <div class="blinkings">
                                <i class="tio-info-outined"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane fade show active " id="mail-config">
                        @php($config = \App\Models\BusinessSetting::where(['key' => 'mail_config'])->first())
                        @php($data = $config ? json_decode($config['value'], true) : null)
                        <form action="{{ env('APP_MODE') != 'demo' ? route('admin.business-settings.mail-config-status') : 'javascript:' }}"
                            method="post" id="mail-config-disable_form">
                            @csrf

                            <div class="form-group text-center d-flex flex-wrap align-items-center">
                                <label class="toggle-switch h--45px toggle-switch-sm d-flex justify-content-between border rounded px-3 py-0 form-control mb-2" >
                                    <span class="pr-1 d-flex align-items-center switch--label text--primary">
                                        <span class="line--limit-1">
                                            {{translate('Turn_OFF')}}
                                        </span>
                                    </span>
                                    <input class="toggle-switch-input" id="mail-config-disable" type="checkbox" onclick="toogleStatusModal(event,'mail-config-disable','mail-success.png','mail-warning.png','{{translate('Important!')}}','{{translate('Warning!')}}',`<p>{{translate('Enabling_mail_configuration_services_will_allow_the_system_to_send_emails._Please_ensure_that_you_have_correctly_configured_the_SMTP_settings_to_avoid_potential_issues_with_email_delivery.')}}</p>`,`<p>{{translate('Disabling_mail_configuration_services_will_prevent_the_system_from_sending_emails._Please_only_turn_off_this_service_if_you_intend_to_temporarily_suspend_email_sending._Note_that_this_may_affect_system_functionality_that_relies_on_email_communication.')}}</p>`)" name="status" value="1" {{isset($data['status'])&&$data['status']==1?'checked':''}}>
                                    <span class="toggle-switch-label text p-0">
                                        <span class="toggle-switch-indicator"></span>
                                    </span>
                                </label>
                                <small>{{translate('*By_Turning_OFF_mail_configuration,_all_your_mailing_services_will_be_off.')}}</small>
                            </div>
                        </form>
                        <form action="javascript:"
                            method="post" id="mail-config-form" >
                            @csrf
                            <div class="disable-on-turn-of {{ isset($data) && isset($data['status']) && $data['status'] == 1 ? '' :'inactive'}}">
                                <input type="hidden" name="status" value="{{(isset($data)&& isset($data['status'])) ? $data['status']:0 }}">
                                <div class="row g-3">
                                    <div class="col-sm-12">
                                        <div class="form-group mb-0">
                                            <label class="form-label">{{ translate('messages.mailer') }}
                                                {{ translate('messages.name') }}</label><br>
                                            <input type="text" placeholder="{{ translate('messages.Ex:') }} Alex" class="form-control" name="name"
                                                value="{{ env('APP_MODE') != 'demo' ? $data['name'] ?? '' : '' }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-0">
                                            <label class="form-label">{{ translate('messages.host') }}</label><br>
                                            <input type="text" class="form-control" name="host" placeholder="{{translate('messages.Ex_:_mail.6am.one')}}"
                                                value="{{ env('APP_MODE') != 'demo' ? $data['host'] ?? '' : '' }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-0">
                                            <label class="form-label">{{ translate('messages.driver') }}</label><br>
                                            <input type="text" class="form-control" name="driver" placeholder="{{translate('messages.Ex : smtp')}}"
                                                value="{{ env('APP_MODE') != 'demo' ? $data['driver'] ?? '' : '' }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-0">
                                            <label class="form-label">{{ translate('messages.port') }}</label><br>
                                            <input type="text" class="form-control" name="port" placeholder="{{translate('messages.Ex : 587')}}"
                                                value="{{ env('APP_MODE') != 'demo' ? $data['port'] ?? '' : '' }}" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group mb-0">
                                            <label class="form-label">{{ translate('messages.username') }}</label><br>
                                            <input type="text" placeholder="{{ translate('messages.Ex:') }} ex@yahoo.com" class="form-control" name="username"
                                                value="{{ env('APP_MODE') != 'demo' ? $data['username'] ?? '' : '' }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-0">
                                            <label class="form-label">{{ translate('messages.email') }}
                                                {{ translate('messages.id') }}</label><br>
                                            <input type="text" placeholder="{{ translate('messages.Ex:') }} ex@yahoo.com" class="form-control" name="email"
                                                value="{{ env('APP_MODE') != 'demo' ? $data['email_id'] ?? '' : '' }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-0">
                                            <label class="form-label">{{ translate('messages.encryption') }}</label><br>
                                            <input type="text" placeholder="{{ translate('messages.Ex:') }} tls" class="form-control" name="encryption"
                                                value="{{ env('APP_MODE') != 'demo' ? $data['encryption'] ?? '' : '' }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-0">
                                            <label class="form-label">{{ translate('messages.password') }}</label><br>
                                            <input type="text" class="form-control" name="password" placeholder="{{translate('messages.Ex : 5+ Characters')}}"
                                                value="{{ env('APP_MODE') != 'demo' ? $data['password'] ?? '' : '' }}" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="btn--container justify-content-end">
                                            <button type="reset" class="btn btn--reset">{{translate('messages.reset')}}</button>
                                            <button type="{{ env('APP_MODE') != 'demo' ? 'submit' : 'button' }}"
                                            onclick="{{ env('APP_MODE') != 'demo' ? '' : 'call_demo()' }}"
                                            class="btn btn--primary">{{ translate('messages.save') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade show disable-on-turn-of {{isset($data['status'])&&$data['status']==1?'':'inactive'}}"  id="test-mail">
                        <div class="row">
                            <div class="col-lg-8">
                                <form class="" action="javascript:">
                                    <label class="form-label">{{translate('Email')}}</label>
                                    <div class="row gx-3 gy-1">
                                        <div class="col-md-8 col-sm-7">
                                            <div>
                                                <label for="inputPassword2" class="sr-only">
                                                    {{ translate('mail') }}</label>
                                                <input type="email" id="test-email" class="form-control"
                                                    placeholder="{{ translate('messages.Ex:') }} jhon@email.com">
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-5">
                                            <button type="button" onclick="{{env('APP_MODE') == 'demo' ? 'call_demo()' : 'send_mail()'}}" class="btn btn--primary h--45px btn-block" >
                                                <i class="tio-telegram"></i>
                                                {{ translate('send_mail') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Mail setup -->
    <div class="modal fade" id="sent-mail-modal">
        <div class="modal-dialog status-warning-modal">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true" class="tio-clear"></span>
                    </button>
                </div>
                <div class="modal-body pt-0">
                    <div class="text-center mb-20">
                        <img src="{{asset('/public/assets/admin/img/sent-mail-box.png')}}" alt="" class="mb-20">
                        <h5 class="modal-title">{{translate('Congratulations!_Your_SMTP_mail_has_been_setup_successfully!')}}</h5>
                        <p class="txt">
                            {{translate("Go_to_test_mail_to_check_that_its_work_perfectly_or_not!")}}
                        </p>
                    </div>
                    <div class="btn--container justify-content-center">
                        <button type="submit" onclick="showTab()" class="btn btn--primary min-w-120" data-dismiss="modal">
                            <img src="{{asset('/public/assets/admin/img/paper-plane.png')}}" alt=""> {{translate('Send Test Mail')}}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Data Modal -->
    {{-- <div class="modal fade" id="update-data-modal">
        <div class="modal-dialog status-warning-modal">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true" class="tio-clear"></span>
                    </button>
                </div>
                <div class="modal-body pt-0">
                    <div class="text-center mb-20">
                        <img src="{{asset('/public/assets/admin/img/mail-config/save-data.png')}}" alt="" class="mb-20">
                        <h5 class="modal-title">{{translate('Send a Test Mail to Your Email ? ')}}</h5>
                        <p class="txt">
                            {{translate("A test mail will be send to your email to confirm it works perfectly.")}}
                        </p>
                    </div>
                    <div class="btn--container justify-content-center">
                        <button type="submit" class="btn btn--primary min-w-120" data-dismiss="modal">
                            {{translate('Send Test Mail')}}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- How it Works Modal -->
    <div class="modal fade" id="works-modal">
        <div class="modal-dialog status-warning-modal">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true" class="tio-clear"></span>
                    </button>
                </div>
                <div class="modal-body pb-5 pt-0">
                    <div class="single-item-slider owl-carousel">
                        <div class="item">
                            <div class="mb-20">
                                <div class="text-center">
                                    <img src="{{asset('/public/assets/admin/img/mail-config/slide-1.png')}}" alt="" class="mb-20">
                                    <h5 class="modal-title">{{translate('Find SMTP Server Details')}}</h5>
                                </div>
                                <ul>
                                    <li>
                                        {{translate('Contact_your_email_service_provider_or_IT_administrator_to_obtain_the_SMTP_server_details,_such_as_hostname,_port,_username,_and_password.')}}
                                    </li>
                                    <li>
                                        {{translate("Note:_If_you're_not_sure_where_to_find_these_details,_check_the_email_provider's_documentation_or_support_resources_for_guidance.")}}
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="item">
                            <div class="mb-20">
                                <div class="text-center">
                                    <img src="{{asset('/public/assets/admin/img/mail-config/slide-2.png')}}" alt="" class="mb-20">
                                    <h5 class="modal-title">{{translate('Configure SMTP Settings')}}</h5>
                                </div>
                                <ul>
                                    <li>
                                        {{translate('Go_to_the_SMTP_mail_setup_page_in_the_admin_panel.')}}
                                    </li>
                                    <li>
                                        {{translate('Enter_the_obtained_SMTP_server_details,_including_the_hostname,_port,_username,_and_password.')}}
                                    </li>
                                    <li>
                                        {{translate('Choose_the_appropriate_encryption_method_(e.g.,_SSL,_TLS)_if_required._Save_the_settings.')}}
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="item">
                            <div class="mb-20">
                                <div class="text-center">
                                    <img src="{{asset('/public/assets/admin/img/mail-config/slide-3.png')}}" alt="" class="mb-20">
                                    <h5 class="modal-title">{{translate('Test SMTP Connection')}}</h5>
                                </div>
                                <ul>
                                    <li>
                                        {{translate('Click_on_the_"Send_Test_Mail"_button_to_verify_the_SMTP_connection.')}}
                                    </li>
                                    <li>
                                        {{translate('If_successful,_you_will_see_a_confirmation_message_indicating_that_the_connection_is_working_fine.')}}
                                    </li>
                                    <li>
                                        {{translate('If_not,_double-check_your_SMTP_settings_and_try_again.')}}
                                    </li>
                                    <li>
                                        {{translate("Note:_If_you're_unsure_about_the_SMTP_settings,_contact_your_email_service_provider_or_IT_administrator_for_assistance.")}}
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="item">
                            <div class="mw-353px mb-20 mx-auto">
                                <div class="text-center">
                                    <img src="{{asset('/public/assets/admin/img/mail-config/slide-4.png')}}" alt="" class="mb-20">
                                    <h5 class="modal-title">{{translate('Enable Mail Configuration')}}</h5>
                                </div>
                                <ul class="px-3">
                                    <li>
                                        {{translate('If_the_SMTP_connection_test_is_successful,_you_can_now_enable_the_mail_configuration_services_by_toggling_the_switch_to_"ON."')}}
                                    </li>
                                    <li>
                                        {{translate('This_will_allow_the_system_to_send_emails_using_the_configured_SMTP_settings.')}}
                                    </li>
                                </ul>
                                <div class="btn-wrap">
                                    <button type="submit" class="btn btn--primary w-100" data-dismiss="modal">{{translate('Got It')}}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center">
                        <div class="slide-counter"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script_2')

<script>
    const disableMailConf = () => {
        if($('#mail-config-disable').is(':checked')) {
            $('.disable-on-turn-of').removeClass('inactive')
        }else {
            $('.disable-on-turn-of').addClass('inactive')
        }
    }
    $('#mail-config-disable').on('change', function(){
        disableMailConf()
    })



    function showTab() {
        $("#mail-config").removeClass("in active");
        $("#test-mail").addClass("fade in active");
        $('.nav--tabs').find('a').removeClass('active')
        $("#modal_active").addClass("active");
        $('.nav--tabs').find('[href="#test-mail"]').addClass('active')
    }

</script>

<script>
    function ValidateEmail(inputText) {
        var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        if (inputText.match(mailformat)) {
            return true;
        } else {
            return false;
        }
    }
    function send_mail() {
        if (ValidateEmail($('#test-email').val())) {
            Swal.fire({
                title: '{{translate('Are you sure?')}}?',
                text: "{{translate('a_test_mail_will_be_sent_to_your_email')}}!",
                showCancelButton: true,
                confirmButtonColor: '#377dff',
                cancelButtonColor: 'secondary',
                confirmButtonText: '{{translate('Yes')}}!'
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{route('admin.business-settings.mail.send')}}",
                        method: 'GET',
                        data: {
                            "email": $('#test-email').val()
                        },
                        beforeSend: function () {
                            $('#loading').show();
                        },
                        success: function (data) {
                            if (data.success === 2) {
                                toastr.error('{{translate('email_configuration_error')}} !!');
                            } else if (data.success === 1) {
                                toastr.success('{{translate('email_configured_perfectly!')}}!');
                            } else {
                                toastr.info('{{translate('email_status_is_not_active')}}!');
                            }
                        },
                        complete: function () {
                            $('#loading').hide();

                        }
                    });
                }
            })
        } else {
            toastr.error('{{translate('invalid_email_address')}} !!');
        }
    }
</script>
<script>
    $('#mail-config-form').submit(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{ route('admin.business-settings.mail-config') }}",
            method: 'POST',
            data: $('#mail-config-form').serialize(),
            beforeSend: function() {
                $('#loading').show();
            },
            success: function(data) {
                toastr.success('{{ translate('messages.configuration_updated_successfully') }}');
                $('#sent-mail-modal').modal('show');


                // $('.nav--tabs').find('[href="#test-mail"]').addClass('active');
            },
            complete: function() {
                $('#loading').hide();
            }
        });
    })
</script>
@endpush
