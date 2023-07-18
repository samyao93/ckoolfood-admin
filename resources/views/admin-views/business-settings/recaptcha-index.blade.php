@extends('layouts.admin.app')

@section('title', translate('messages.reCaptcha Setup'))


@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{asset('public/assets/admin/img/captcha.png')}}" class="w--26" alt="">
                </span>
                <span>
                    {{translate('messages.reCaptcha')}} {{translate('messages.credentials')}} {{translate('messages.setup')}}
                </span>
            </h1>
            @include('admin-views.business-settings.partials.third-party-links')
        </div>
        <!-- End Page Header -->

        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between">
                    <span class="status">
                        {{translate('Status')}}
                    </span>
                    <div class="text--primary-2 d-flex flex-wrap align-items-center" type="button" data-toggle="modal" data-target="#works-modal">
                        <strong class="mr-2">{{translate('Credential Setup')}}</strong>
                        <div class="blinkings">
                            <i class="tio-info-outined"></i>
                        </div>
                    </div>
                </div>
                <div class="mt-2">
                    @php($config=\App\CentralLogics\Helpers::get_business_settings('recaptcha'))
                    <form
                        action="{{env('APP_MODE')!='demo'?route('admin.business-settings.recaptcha_update',['recaptcha']):'javascript:'}}"
                        method="post">
                        @csrf
                        <label class="toggle-switch h--45px toggle-switch-sm d-flex justify-content-between border rounded px-3 py-0 form-control mb-4">
                            <span class="pr-1 d-flex align-items-center switch--label">
                                <span class="line--limit-1">
                                    @if (isset($config) && $config['status'] == 1)
                                    {{translate('Turn_OFF')}}
                                    @else

                                    {{translate('Turn_ON')}}
                                    @endif
                                </span>
                            </span>
                            <input class="toggle-switch-input" type="checkbox" onclick="toogleModal(event,'recaptcha_status','recapcha-on.png','recapcha-off.png','{{translate('Important!')}}','{{translate('Warning!')}}',`<p>{{translate('reCAPTCHA_is_now_enabled_for_added_security._Users_may_be_prompted_to_complete_a_reCAPTCHA_challenge_to_verify_their_human_identity_and_protect_against_spam_and_malicious_activity.')}}</p>`,`<p>{{translate('Disabling_reCAPTCHA_may_leave_your_website_vulnerable_to_spam_and_malicious_activity_and_suspects_that_a_user_may_be_a_bot._It_is_highly_recommended_to_keep_reCAPTCHA_enabled_to_ensure_the_security_and_integrity_of_your_website.')}}</p>`)" name="status" id="recaptcha_status" value="1" {{isset($config) && $config['status'] == 1 ? 'checked':''}}>
                            <span class="toggle-switch-label text p-0">
                                <span class="toggle-switch-indicator"></span>
                            </span>
                        </label>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label">{{translate('messages.Site Key')}}</label><br>
                                    <input type="text" class="form-control" name="site_key"
                                            value="{{env('APP_MODE')!='demo'?$config['site_key']??"":''}}">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label">{{translate('messages.Secret Key')}}</label><br>
                                    <input type="text" class="form-control" name="secret_key"
                                            value="{{env('APP_MODE')!='demo'?$config['secret_key']??"":''}}">
                                </div>
                            </div>
                        </div>

                        <div class="btn--container justify-content-end">
                            <button type="reset" class="btn btn--reset">{{translate('messages.reset')}}</button>
                            <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}" onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}" class="btn btn--primary">{{translate('messages.save')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="works-modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog status-warning-modal">
            <div class="modal-content {{Session::get('direction') === "rtl" ? 'text-right' : 'text-left'}}">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pb-0"><b></b>
                    <div class="text-center mb-20">
                        <img src="{{asset('/public/assets/admin/img/modal/warning-recapcha-2.png')}}" alt="" class="mb-20">
                        <h5 class="modal-title">{{translate('messages.reCAPTCHA_Credential Setup')}}</h5>
                    </div>
                    <ol>
                        <li>{{translate('messages.Go to the Credentials page')}}
                            ({{translate('messages.Click')}} <a
                                href="https://www.google.com/recaptcha/admin/create"
                                target="_blank">{{translate('messages.here')}}</a>)
                        </li>
                        <li>{{translate('messages.Add a ')}}
                            <b>{{translate('messages.label')}}</b> {{translate('messages.(Ex: Test Label)')}}
                        </li>
                        <li>
                            {{translate('messages.Select reCAPTCHA v2 as ')}}
                            <b>{{translate('messages.reCAPTCHA Type')}}</b>
                            ({{translate("Sub type: I'm not a robot Checkbox")}}
                            )
                        </li>
                        <li>
                            {{translate('messages.Add')}}
                            <b>{{translate('messages.domain')}}</b>
                            {{translate('messages.(For ex: demo.6amtech.com)')}}
                        </li>
                        <li>
                            {{translate('messages.Check in ')}}
                            <b>{{translate('messages.Accept the reCAPTCHA Terms of Service')}}</b>
                        </li>
                        <li>
                            {{translate('messages.Press')}}
                            <b>{{translate('messages.Submit')}}</b>
                        </li>
                        <li>{{translate('messages.Copy')}} <b>Site
                                Key</b> {{translate('messages.and')}} <b>Secret
                                Key</b>, {{translate('messages.paste in the input filed below and')}}
                            <b>Save</b>.
                        </li>
                        </ol>
                </div>
                <div class="modal-footer justify-content-center border-0">
                    <button type="button" class="btn btn--primary w-100 mw-300px" data-dismiss="modal">{{translate('Got It')}}</button>
                </div>
            </div>
        </div>
    </div>





@endsection

