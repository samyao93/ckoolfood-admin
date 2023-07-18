<!-- Nav -->
        <ul class="nav nav-tabs border-0 nav--tabs nav--pills pt-3">
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/landing-page/header') ? 'active' : '' }}"
                href="{{ route('admin.landing_page.header') }}">{{translate('messages.Header')}}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/landing-page/about-us') ? 'active' : '' }}"
                href="{{ route('admin.landing_page.about_us') }}">{{translate('messages.about_us')}}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/landing-page/feature*') ? 'active' : '' }}"
                href="{{ route('admin.landing_page.features') }}">{{translate('messages.Features')}}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/landing-page/services') ? 'active' : '' }}"
                href="{{ route('admin.landing_page.services') }}">{{translate('messages.Services')}}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/landing-page/earn-money') ? 'active' : '' }}"
                href="{{ route('admin.landing_page.earn_money') }}">{{translate('messages.Earn_money')}}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/landing-page/why-choose-us*') ? 'active' : '' }}"
                href="{{ route('admin.landing_page.why_choose_us') }}">{{translate('messages.why_choose_us')}}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/landing-page/testimonial*') ? 'active' : '' }}"
                href="{{ route('admin.landing_page.testimonial') }}">{{translate('messages.Testimonials')}}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/landing-page/fixed-data*') ? 'active' : '' }}"
                href="{{ route('admin.landing_page.fixed_data') }}">{{translate('messages.Fixed_data')}}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/landing-page/links*') ? 'active' : '' }}"
                href="{{ route('admin.landing_page.links') }}">{{translate('messages.button_&_links')}}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/landing-page/backgroung-color') ? 'active' : '' }}"
                href="{{ route('admin.landing_page.backgroung_color') }}">{{translate('messages.backgroung_color')}}</a>
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
                                    {{translate("If_you_want_to_disable_or_turn_off_any_section_please_leave_that_section_empty,_don’t_make_any_changes_there!")}}
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
                                    {{translate('Visit landing page to see the changes you made in the settings option!')}}
                                </p>
                                <div class="btn-wrap">
                                    <a href="{{ url('/') }}" type="submit" class="btn btn--primary w-100" >{{ translate('Visit_Now') }}</a>
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
