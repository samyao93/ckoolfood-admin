@extends('layouts.admin.app')

@section('title',translate('FCM Settings'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{asset('public/assets/admin/img/firebase.png')}}" class="w--26" alt="">
                </span>
                <span>{{translate('messages.firebase')}} {{translate('messages.push')}} {{translate('messages.notification')}} {{translate('messages.setup')}}
                </span>
            </h1>
        </div>
        <!-- End Page Header -->
        <div class="card">
            <div class="card-header card-header-shadow pb-0">
                <div class="d-flex flex-wrap justify-content-between w-100 row-gap-1">
                    <ul class="nav nav-tabs nav--tabs border-0 gap-2">
                        <li class="nav-item mr-2 mr-md-4">
                            <a href="{{ route('admin.business-settings.fcm-index') }}" class="nav-link pb-2 px-0 pb-sm-3 " data-slide="1">
                                <img src="{{asset('/public/assets/admin/img/notify.png')}}" alt="">
                                <span>{{translate('Push Notification')}}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.business-settings.fcm-config') }}" class="nav-link pb-2 px-0 pb-sm-3 active" data-slide="2">
                                <img src="{{asset('/public/assets/admin/img/firebase2.png')}}" alt="">
                                <span>{{translate('Firebase Configuration')}}</span>
                            </a>
                        </li>
                    </ul>
                    <div class="py-1">
                            <div class="item text--primary-2 d-flex flex-wrap align-items-center" type="button" data-toggle="modal" data-target="#firebase-modal">
                                <strong class="mr-2">{{translate('Where to get this information')}}</strong>
                                <div class="blinkings">
                                    <i class="tio-info-outined"></i>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="{{env('APP_MODE')!='demo'?route('admin.business-settings.update-fcm'):'javascript:'}}" method="post"
                        enctype="multipart/form-data">
                    @csrf
                    @php($key=\App\Models\BusinessSetting::where('key','push_notification_key')->first())
                    <div class="form-group">
                        <label class="input-label form-label"
                                >{{translate('messages.server')}} {{translate('messages.key')}}</label>
                        <div class="d-flex">
                            <textarea name="push_notification_key" class="form-control" placeholder="{{translate('Ex: AAAAaBcDeFgHiJkLmNoPqRsTuVwXyZ0123456789')}}"
                                required>{{env('APP_MODE')!='demo'?$key->value??'':''}}</textarea>
                            </div>
                        </div>
                    <div class="form-group">
                        <label class="input-label" >{{translate('messages.api_key')}}</label>
                        <div class="d-flex">
                            <input type="text" value="{{isset($fcm_credentials['apiKey'])?$fcm_credentials['apiKey']:''}}"
                                name="apiKey" class="form-control" placeholder="{{translate('Ex : Api key')}}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-4 col-sm-6">
                            @php($project_id=\App\Models\BusinessSetting::where('key','fcm_project_id')->first())
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('FCM Project ID')}}</label>
                                <div class="d-flex">
                                    <input type="text" value="{{$project_id->value??''}}"
                                        name="projectId" class="form-control" placeholder="{{ translate('Ex: my-awesome-app-12345') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('messages.auth_domain')}}</label>
                                <div class="d-flex">
                                    <input type="text" value="{{isset($fcm_credentials['authDomain'])?$fcm_credentials['authDomain']:''}}"
                                        name="authDomain" class="form-control" placeholder="{{ translate('Ex: my-awesome-app.firebaseapp.com') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('messages.storage_bucket')}}</label>
                                <div class="d-flex">
                                    <input type="text" value="{{isset($fcm_credentials['storageBucket'])?$fcm_credentials['storageBucket']:''}}"
                                        name="storageBucket" class="form-control" placeholder="{{ translate('Ex: my-awesome-app.appspot.com') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('messages.messaging_sender_id')}}</label>
                                <div class="d-flex">
                                    <input type="text" value="{{isset($fcm_credentials['messagingSenderId'])?$fcm_credentials['messagingSenderId']:''}}"
                                        name="messagingSenderId" class="form-control" placeholder="{{ translate('Ex: 1234567890') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('messages.app_id')}}</label>
                                <div class="d-flex">
                                    <input type="text" value="{{isset($fcm_credentials['appId'])?$fcm_credentials['appId']:''}}"
                                        name="appId" class="form-control" placeholder="{{ translate('Ex: 9876543210') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('messages.measurement_id')}}</label>
                                <div class="d-flex">
                                    <input type="text" value="{{isset($fcm_credentials['measurementId'])?$fcm_credentials['measurementId']:''}}"
                                        name="measurementId" class="form-control" placeholder="{{ translate('Ex: F-12345678') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-right">
                        <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}" onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}" class="btn btn--primary">{{translate('messages.submit')}}</button>
                    </div>
                </form>
            </div>
        </div>



        <!-- Firebase Modal -->
        <div class="modal fade" id="firebase-modal">
            <div class="modal-dialog status-warning-modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true" class="tio-clear"></span>
                        </button>
                    </div>
                    <div class="modal-body pb-5 pt-0">
                        <div class="max-349 mx-auto mb-20">
                            <div class="text-center">
                                <img src="{{asset('/public/assets/admin/img/firebase/slide-4.png')}}" alt="" class="mb-20">
                                <h5 class="modal-title">{{translate('Please_Visit_the_Docs_to_Set_FCM_on_Mobile_Apps')}}</h5>
                            </div>
                            <div class="text-center">
                                <p>
                                    {{translate('Please_check_the_documentation_below_for_detailed_instructions_on_setting_up_your_mobile_app_to_receive_Firebase_Cloud_Messaging_(FCM)_notifications.')}}
                                </p>
                                <a href="#">{{translate('Click Here')}}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('script_2')
<script>
    $('[data-slide]').on('click', function(){
        let serial = $(this).data('slide')
        $(`.tab--content .item`).removeClass('show')
        $(`.tab--content .item:nth-child(${serial})`).addClass('show')
    })
</script>

@endpush


