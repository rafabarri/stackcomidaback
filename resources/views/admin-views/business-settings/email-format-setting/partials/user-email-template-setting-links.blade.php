<div class="d-flex flex-wrap justify-content-between align-items-center mb-5 mt-4 __gap-12px">
    <div class="js-nav-scroller hs-nav-scroller-horizontal mt-2">
        <!-- Nav -->
        <ul class="nav nav-tabs border-0 nav--tabs nav--pills">
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/email-setup/user/registration') ? 'active' : '' }}"
                href="{{ route('admin.business-settings.email-setup', ['user','registration']) }}">
                    {{translate('New_Customer_Registration')}}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/email-setup/user/pos-registration') ? 'active' : '' }}"
                href="{{ route('admin.business-settings.email-setup', ['user','pos-registration']) }}">
                    {{translate('POS_New_Customer_Registration')}}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/email-setup/user/registration-otp') ? 'active' : '' }}"
                href="{{ route('admin.business-settings.email-setup', ['user','registration-otp']) }}">
                    {{translate('Registration_OTP')}}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/email-setup/user/login-otp') ? 'active' : '' }}"
                href="{{ route('admin.business-settings.email-setup', ['user','login-otp']) }}">
                    {{translate('Login_OTP')}}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/email-setup/user/profile-verification') ? 'active' : '' }}"
                href="{{ route('admin.business-settings.email-setup', ['user','profile-verification']) }}">
                    {{translate('Email_Verification')}}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/email-setup/user/order-verification') ? 'active' : '' }}"
                href="{{ route('admin.business-settings.email-setup', ['user','order-verification']) }}">
                    {{translate('Delivery_Verification')}}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/email-setup/user/new-order') ? 'active' : '' }}"
                href="{{ route('admin.business-settings.email-setup', ['user','new-order']) }}">{{translate('messages.Order_Placement')}}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/email-setup/user/refund-order') ? 'active' : '' }}"
                href="{{ route('admin.business-settings.email-setup', ['user','refund-order']) }}">{{translate('messages.refund_order')}}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/email-setup/user/refund-request-deny') ? 'active' : '' }}"
                href="{{ route('admin.business-settings.email-setup', ['user','refund-request-deny']) }}">
                    {{translate('Refund_Request_Rejected')}}
                </a>
            </li>
            {{-- <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/email-setup/user/forgot-password') ? 'active' : '' }}"
                href="{{ route('admin.business-settings.email-setup', ['user','forgot-password']) }}">
                    {{translate('Forgot_Password')}}
                </a>
            </li> --}}

            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/email-setup/user/add-fund') ? 'active' : '' }}"
                href="{{ route('admin.business-settings.email-setup', ['user','add-fund']) }}">
                    {{translate('Fund_Add')}}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/email-setup/user/offline-payment-approve') ? 'active' : '' }}"
                href="{{ route('admin.business-settings.email-setup', ['user','offline-payment-approve']) }}">
                    {{translate('Offline_Payment_Approve')}}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/email-setup/user/offline-payment-deny') ? 'active' : '' }}"
                href="{{ route('admin.business-settings.email-setup', ['user','offline-payment-deny']) }}">
                    {{translate('Offline_Payment_Deny')}}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/email-setup/user/suspend') ? 'active' : '' }}"
                href="{{ route('admin.business-settings.email-setup', ['user','suspend']) }}">
                    {{translate('Account_Suspension')}}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/email-setup/user/unsuspend') ? 'active' : '' }}"
                href="{{ route('admin.business-settings.email-setup', ['user','unsuspend']) }}">
                    {{translate('Account_Unsuspension')}}
                </a>
            </li>
        </ul>
        <!-- End Nav -->
    </div>
</div>
