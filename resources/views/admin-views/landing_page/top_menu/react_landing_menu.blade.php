<!-- Nav -->
        <ul class="nav nav-tabs border-0 nav--tabs nav--pills pt-3">
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/react-landing-page/header') ? 'active' : '' }}"
                href="{{ route('admin.react_landing_page.react_header') }}">{{translate('messages.Header')}}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/react-landing-page/service*') ? 'active' : '' }}"
                href="{{ route('admin.react_landing_page.react_services') }}">{{translate('messages.Services')}}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/react-landing-page/promotional-banner*') ? 'active' : '' }}"
                href="{{ route('admin.react_landing_page.promotional_banner') }}">{{translate('messages.Promotional_Banner')}}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/react-landing-page/registration-scetion*') ? 'active' : '' }}"
                href="{{ route('admin.react_landing_page.registration_scetion') }}">{{translate('messages.Registration_Scetion')}}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/react-landing-page/download-apps*') ? 'active' : '' }}"
                href="{{ route('admin.react_landing_page.download_apps') }}">{{translate('messages.Download_Apps')}}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/react-landing-page/fixed-data*') ? 'active' : '' }}"
                href="{{ route('admin.react_landing_page.react_fixed_data') }}">{{translate('messages.Fixed_data')}}</a>
            </li>
        </ul>
        <!-- End Nav -->

    <!-- How it Works -->
    <div class="modal fade" id="how-it-works">
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
                            <div class="max-349 mx-auto mb-20 text-center">
                                <img src="{{asset('/public/assets/admin/img/landing-how.png')}}" alt="" class="mb-20">
                                <h5 class="modal-title">{{translate('Notice!')}}</h5>
                                <p>
                                    {{-- {{translate("If you want to disable or turn off any section please leave that section empty, don’t make any changes there!")}} --}}
                                    {{translate("Don’t_forget_to_click_the_‘Save’_button_below_to_save_changes")}}
                                </p>
                            </div>
                        </div>
                        <div class="item">
                            <div class="max-349 mx-auto mb-20 text-center">
                                <img src="{{asset('/public/assets/admin/img/notice-2.png')}}" alt="" class="mb-20">
                                <h5 class="modal-title">{{translate('If_You_Want_to_Change_Language')}}</h5>
                                <p>
                                    {{translate("Change_the_language_on_tab_bar_and_input_your_data_again!")}}
                                </p>
                            </div>
                        </div>
                        <div class="item">
                            <div class="max-349 mx-auto mb-20 text-center">
                                <img src="{{asset('/public/assets/admin/img/notice-3.png')}}" alt="" class="mb-20">
                                <h5 class="modal-title">{{translate('Let’s_See_The_Changes!')}}</h5>
                                <p>
                                    {{translate('Visit_landing_page_to_see_the_changes_you_made_in_the_settings_option!')}}
                                </p>
                                {{-- <div class="btn-wrap">
                                    <button type="submit" class="btn btn--primary w-100" data-dismiss="modal">Visit Now</button>
                                </div> --}}
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
