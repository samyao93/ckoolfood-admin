<div class="d-flex flex-wrap justify-content-between align-items-center mb-5 mt-4 __gap-12px">
    <div class="js-nav-scroller hs-nav-scroller-horizontal mt-2">
        <!-- Nav -->
        <ul class="nav nav-tabs border-0 nav--tabs nav--pills">
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/email-setup/restaurant/registration') ? 'active' : '' }}"
                href="{{ route('admin.business-settings.email-setup', ['restaurant','registration']) }}">
                    {{translate('New Restaurant Registration')}}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/email-setup/restaurant/approve') ? 'active' : '' }}"
                href="{{ route('admin.business-settings.email-setup', ['restaurant','approve']) }}">
                    {{translate('New_Restaurant_Approval')}}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/email-setup/restaurant/deny') ? 'active' : '' }}"
                href="{{ route('admin.business-settings.email-setup', ['restaurant','deny']) }}">
                    {{translate('New_Restaurant_Rejection')}}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/email-setup/restaurant/withdraw-approve') ? 'active' : '' }}"
                href="{{ route('admin.business-settings.email-setup', ['restaurant','withdraw-approve']) }}">
                    {{translate('Withdraw_Approval')}}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/email-setup/restaurant/withdraw-deny') ? 'active' : '' }}"
                href="{{ route('admin.business-settings.email-setup', ['restaurant','withdraw-deny']) }}">
                    {{translate('Withdraw_Rejection')}}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/email-setup/restaurant/campaign-request') ? 'active' : '' }}"
                href="{{ route('admin.business-settings.email-setup', ['restaurant','campaign-request']) }}">
                    {{translate('Campaign_Join_Request')}}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/email-setup/restaurant/campaign-approve') ? 'active' : '' }}"
                href="{{ route('admin.business-settings.email-setup', ['restaurant','campaign-approve']) }}">
                    {{translate('Campaign_Join_Approval')}}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/email-setup/restaurant/campaign-deny') ? 'active' : '' }}"
                href="{{ route('admin.business-settings.email-setup', ['restaurant','campaign-deny']) }}">
                    {{translate('Campaign_Join_Rejection')}}
                </a>
            </li>
        </ul>
        <!-- End Nav -->
    </div>
</div>
