<div class="footer">
    <div class="row gy-1 justify-content-between align-items-center">
        <div class="col-md-auto">
            <p class="font-size-sm mb-0">
                &copy; {{\App\Models\BusinessSetting::where(['key'=>'business_name'])->first()->value}}. <span
                    class="d-none d-sm-inline-block">{{\App\Models\BusinessSetting::where(['key'=>'footer_text'])->first()->value}}</span>
            </p>
        </div>
        <div class="col-md-auto">
            <div class="d-flex justify-content-end">
                <!-- List Dot -->
                <ul class="list-inline list-separator d-flex flex-wrap justify-content-evenly justify-content-md-end flex-grow-1">
                    <li class="list-inline-item py-1">
                        <a class="list-separator-link" href="{{route('admin.business-settings.business-setup')}}">{{translate('messages.business')}} {{translate('messages.setup')}} <i class="tio-settings-outlined ml-xl-2"></i></a>
                    </li>

                    <li class="list-inline-item py-1">
                        <a class="list-separator-link" href="{{route('admin.settings')}}">
                            {{translate('messages.profile')}}
                            <i class="tio-user ml-xl-2"></i>
                        </a>
                    </li>


                    <li class="list-inline-item py-1">
                        <a class="list-separator-link" href="{{route('admin.dashboard')}}">
                            {{translate('messages.home')}}
                            <i class="tio-home ml-xl-2"></i>
                        </a>
                    </li>


                    <li class="list-inline-item py-1">
                        <span class="badge app--version">{{ translate('App_Version')  }} {{env('SOFTWARE_VERSION')}}</span>
                    </li>

                </ul>
                <!-- End List Dot -->
            </div>
        </div>
    </div>
</div>
