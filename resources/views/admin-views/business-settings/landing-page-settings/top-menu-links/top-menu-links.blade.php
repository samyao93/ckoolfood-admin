<ul class="nav nav-tabs page-header-tabs">
    <li class="nav-item">
        <a class="nav-link {{ Request::is('admin/business-settings/landing-page-settings/index') ? 'active' : '' }}"
            href="{{ route('admin.business-settings.landing-page-settings', 'index') }}">{{ translate('messages.text') }}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ Request::is('admin/business-settings/landing-page-settings/links') ? 'active' : '' }}"
            href="{{ route('admin.business-settings.landing-page-settings', 'links') }}"
            aria-disabled="true">{{ translate('messages.button_links') }}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ Request::is('admin/business-settings/landing-page-settings/speciality') ? 'active' : '' }}"
            href="{{ route('admin.business-settings.landing-page-settings', 'speciality') }}"
            aria-disabled="true">{{ translate('messages.speciality') }}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ Request::is('admin/business-settings/landing-page-settings/platform-order') ||
                    Request::is('admin/business-settings/landing-page-settings/platform-restaurant') ||
                    Request::is('admin/business-settings/landing-page-settings/platform-delivery')
                    ? 'active' : '' }}"
            href="{{ route('admin.business-settings.landing-page-settings', 'platform-order') }}"

            aria-disabled="true">{{ translate('messages.our_platform') }}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ Request::is('admin/business-settings/landing-page-settings/testimonial') ? 'active' : '' }}"
            href="{{ route('admin.business-settings.landing-page-settings', 'testimonial') }}"
            aria-disabled="true">{{ translate('messages.testimonial') }}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ Request::is('admin/business-settings/landing-page-settings/feature') ? 'active' : '' }}"
            href="{{ route('admin.business-settings.landing-page-settings', 'feature') }}"
            aria-disabled="true">{{ translate('messages.feature') }}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ Request::is('admin/business-settings/landing-page-settings/image') ? 'active' : '' }}"
            href="{{ route('admin.business-settings.landing-page-settings', 'image') }}"
            aria-disabled="true">{{ translate('messages.image') }}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ Request::is('admin/business-settings/landing-page-settings/backgroundChange') ? 'active' : '' }}"
            href="{{ route('admin.business-settings.landing-page-settings', 'backgroundChange') }}"
            aria-disabled="true">{{ translate('messages.header_footer_bg') }}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{
                Request::is('admin/business-settings/landing-page-settings/react-half-banner') ||
                Request::is('admin/business-settings/landing-page-settings/react') ||
                Request::is('admin/business-settings/landing-page-settings/react-self-registration') ||
                Request::is('admin/business-settings/landing-page-settings/react-feature')    ? 'active' : '' }} "
            href="{{ route('admin.business-settings.landing-page-settings', 'react') }}"
            aria-disabled="true">{{ translate('React Landing Page') }}</a>
    </li>
</ul>
