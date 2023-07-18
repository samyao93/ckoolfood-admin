<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ translate('Password_Reset') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style type="text/css">
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap');
        body {
            font-family: 'Roboto', sans-serif;
            width: 100% !important;
            height: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
            color: #334257;
            font-size: 13px;
            line-height: 1.5;
            display: flex;align-items: center;justify-content: center;
            min-height: 100vh;

        }

        table {
            border-collapse: collapse !important;
        }
        .border-top {
            border-top: 1px solid rgba(0, 170, 109, 0.3);
            padding: 15px 0 10px;
            display: block;
        }
        .d-block {
            display: block;
        }
        .privacy {
            display: flex;
            align-items: center;
            justify-content: center;

        }
        .privacy a {
            text-decoration: none;
            color: #334257;
            position: relative;
        }
        .privacy a:not(:last-child)::after {
            content:'';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #334257;
            display: inline-block;
            margin: 0 15px
        }
        .social {
            margin: 15px 0 8px;
            display: block;
        }
        .copyright{
            text-align: center;
            display: block;
        }

    </style>
</head>

<body style="background-color: #e9ecef;padding:15px">

    <table style="width:100%;max-width:500px;margin:0 auto;text-align:center;background:#fff">
        <tr>
            <td style="padding:30px 30px 0">
                <img src="{{asset('/public/assets/admin/img/email-template-img.png')}}" alt="">
                <h3 style="font-size:17px;font-weight:500">{{ translate('Change_password_request') }}</h3>

            </td>
        </tr>
        <tr>
            <td style="padding:0 30px 30px; text-align:left">
                <span style="font-weight:500;display:block;margin: 20px 0 11px;">{{ translate('Hi') }} {{ $name }},</span>
                <span style="display:block;margin-bottom:14px">
                    {{ translate('Please_click') }}   <a href="" style="font-weight:500;color:#0177CD">{{ translate('Here') }}</a> {{ translate('or_click_the_link_below_to_change_your_password') }}
                </span>
                <span style="display:block;margin-bottom:14px">
                    <span style="display:block">{{ translate('Click_here') }} </span>
                    <a href="{{ $url }}" style="color: #0177CD">{{ $url }}</a>
                </span>
                <span class="border-top"></span>
                <span class="d-block" style="margin-bottom:14px"> {{ translate('Please_contact_us_for_any_queries,_we’re_always_happy_to_help.') }} </span>
                <span class="d-block">
                    {{ translate('Thanks_&_Regards') }},</span>


                    @php($business_name = \App\Models\BusinessSetting::where(['key' => 'business_name'])->first()->value)
                <span class="d-block" style="margin-bottom:20px">{{ $business_name }}</span>
                @php($restaurant_logo = \App\Models\BusinessSetting::where(['key' => 'logo'])->first()->value)

                <img style="width:120px;display:block;margin:10px auto" onerror="this.src='{{asset('/public/assets/admin/img/favicon.png')}}'" src="{{ asset('storage/app/public/business/' . $restaurant_logo) }}" alt="img">
                <span class="privacy">
                    <a href="{{ route('privacy-policy') }}">{{ translate('Privacy_Policy') }}</a> &nbsp; &nbsp;  <a href="{{ route('contact-us') }}">{{ translate('Contact_Us') }}</a>
                </span>

                @php($social_media = \App\Models\SocialMedia::active()->get())

                <span class="social" style="text-align:center">
                    @foreach ($social_media as $item)
                    <a href="{{$item->link}}" style="margin: 0 5px;text-decoration:none">
                        <img  src="{{asset('public/assets/admin/img/'.$item->name.'.png')}}" alt="{{ $item->name }}">
                    </a>
                    @endforeach
                </span>
                @php($footer_text = \App\Models\BusinessSetting::where(['key' => 'footer_text'])->first())
                <span class="copyright">
                    {{ $footer_text?->value ?? translate('All_right_reserved') }}
                </span>
            </td>
        </tr>
    </table>

</body>

</html>
