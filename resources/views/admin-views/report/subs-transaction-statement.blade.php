<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{translate('order_transaction_statement')}}</title>
    <meta http-equiv="Content-Type" content="text/html;"/>
    <meta charset="UTF-8">
    <style media="all">
        * {
            margin: 0;
            padding: 0;
            line-height: 1.3;
            font-family: sans-serif;
            color: #333542;
        }


        /* IE 6 */
        * html .footer {
            position: absolute;
            top: expression((0-(footer.offsetHeight)+(document.documentElement.clientHeight ? document.documentElement.clientHeight : document.body.clientHeight)+(ignoreMe = document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop))+'px');
        }

        body {
            font-size: .75rem;
        }

        img {
            max-width: 100%;
        }

        .customers {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }
        table {
            width: 100%;
        }

        table thead th {
            padding: 8px;
            font-size: 11px;
            text-align: start;
        }

        table tbody th,
        table tbody td {
            padding: 8px;
            font-size: 11px;
        }

        table.fz-12 thead th {
            font-size: 12px;
        }

        table.fz-12 tbody th,
        table.fz-12 tbody td {
            font-size: 12px;
        }

        table.customers thead th {
            background-color: #0177CD;
            color: #fff;
        }

        table.customers tbody th,
        table.customers tbody td {
            background-color: #FAFCFF;
        }

        table.calc-table th {
            text-align: start;
        }

        table.calc-table td {
            text-align: end;
        }
        table.calc-table td.text-left {
            text-align: start;
        }

        .table-total {
            font-family: Arial, Helvetica, sans-serif;
        }


        .text-left {
            text-align: start !important;
        }

        .pb-2 {
            padding-bottom: 8px !important;
        }

        .pb-3 {
            padding-bottom: 16px !important;
        }

        .text-right {
            text-align: end;
        }

        .content-position {
            padding: 15px 40px;
        }

        .content-position-y {
            padding: 0px 40px;
        }

        .text-white {
            color: white !important;
        }

        .bs-0 {
            border-spacing: 0;
        }
        .text-center {
            text-align: center;
        }
        .mb-1 {
            margin-bottom: 4px !important;
        }
        .mb-2 {
            margin-bottom: 8px !important;
        }
        .mb-4 {
            margin-bottom: 24px !important;
        }
        .mb-30 {
            margin-bottom: 30px !important;
        }
        .px-10 {
            padding-inline-start: 10px;
            padding-inline-end: 10px;
        }
        .fz-14 {
            font-size: 14px;
        }
        .fz-12 {
            font-size: 12px;
        }
        .fz-10 {
            font-size: 10px;
        }
        .font-normal {
            font-weight: 400;
        }
        .border-dashed-top {
            border-top: 1px dashed #ddd;
        }
        .font-weight-bold {
            font-weight: 700;
        }
        .bg-light {
            background-color: #F7F7F7;
        }
        .py-30 {
            padding-top: 30px;
            padding-bottom: 30px;
        }
        .py-4 {
            padding-top: 24px;
            padding-bottom: 24px;
        }
        .d-flex {
            display: flex;
        }
        .gap-2 {
            gap: 8px;
        }
        .flex-wrap {
            flex-wrap: wrap;
        }
        .align-items-center {
            align-items: center;
        }
        .justify-content-center {
            justify-content: center;
        }
        a {
            color: rgba(0, 128, 245, 1);
        }
        .p-1 {
            padding: 4px !important;
        }
        .h2 {
            font-size: 1.5em;
            margin-block-start: 0.83em;
            margin-block-end: 0.83em;
            margin-inline-start: 0px;
            margin-inline-end: 0px;
            font-weight: bold;
        }

        .h4 {
            margin-block-start: 1.33em;
            margin-block-end: 1.33em;
            margin-inline-start: 0px;
            margin-inline-end: 0px;
            font-weight: bold;
        }

    </style>
</head>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<body>
<div class="first">
    <table class="bs-0 mb-30 px-10">
        <tr>
            <th class="content-position-y text-left">
                <h2>{{translate('messages.Subscription_Transaction_Statement')}}</h2>
                <p class="fz-14">{{translate('date')}} : {{ date('d M Y ' . config('timeformat'), strtotime(now())) }}</p>
                <h4 class="text-uppercase mb-1 fz-14">
                    {{translate('statement')}}: #{{ $transaction->id }}
                </h4>
            </th>
            <th class="content-position-y text-right">
                <img height="50" style="max-width:200px" src="{{asset("/storage/app/public/business/$company_web_logo")}}" alt="">
            </th>
        </tr>
    </table>
</div>

<?php
use App\Models\BusinessSetting;
$company_phone =BusinessSetting::where('key', 'phone')->first()->value;
$company_email =BusinessSetting::where('key', 'email_address')->first()->value;
$company_name =BusinessSetting::where('key', 'business_name')->first()->value;
$company_address =BusinessSetting::where('key', 'address')->first()->value;
$logo =BusinessSetting::where('key', 'logo')->first()->value;
$company_mobile_logo = $logo;
$company_links = json_decode(BusinessSetting::where('key','landing_page_links')->first()->value, true);
?>
<div class="content container-fluid initial-38">
    <div class="row justify-content-center" id="printableArea">
        <div class="col-md-12">
            <hr class="non-printable">
            <div class="initial-38-1 __trx-print">
                <div class="pt-3 text-center mb-3 pb-1">
                    <img src="{{asset('/public/assets/admin/img/success_image 2.png')}}" class="initial-38-2" alt="">
                </div>

                <div class="text-center pt-2 mb-3">
                    <h1 class="initial-38-3" >{{ translate('messages.Transaction Sucessfull') }}</h1>
                    <h4 class="initial-38-4"> {{ translate('messages.for') }}
                        {{ $transaction->package->package_name }} {{ translate('messages.Package') }}</h4>

                    <h4> <span class="text--base">{{ translate('Purches Status: ') }}</span> {{ translate('Subscribed.') }}</h4>
                    <h3 class="initial-38-3 name my-3">{{ translate('messages.dear') }} {{$transaction->restaurant->vendor->f_name ?? null}} {{$transaction->restaurant->vendor->l_name ?? null}}</h3>
                    <h5 class="pb-4 pt-2 mv-2">
                        {{ translate('Thank You for transcation with') }} <span class="text--base">{{ $company_name }}</span> {{ translate('messages.in') }}
                        {{ $transaction->package->package_name }} {{ translate('messages.Package') }}
                    </h5>
                </div>
                <table class="table __subscribe-table table-borderless mt-3" style="color: rgb(105, 101, 101)">
                    <thead>
                        <tr>
                            <th>
                                <span>{{ translate('Transaction ID') }}</span>
                            </th>
                            <th >
                                <span>{{ translate('Package Name') }}</span>
                            </th>
                            <th>
                                <span>{{ translate('Transaction Time') }}</span>
                            </th>
                            <th>
                                <span>{{ translate('Validity Time') }}</span>
                            </th>
                            <th>
                                <span>{{ translate('Amount') }}</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <span>{{ $transaction->id }}</span>
                            </td>
                            <td>
                                <span>{{ $transaction->package->package_name }}</span>
                            </td>
                            <td>
                                <span>{{ $transaction->created_at->format('d M Y') }}</span>
                            </td>
                            <td>
                                <span>{{ $transaction->validity }} {{ translate('messages.Days') }}</span>
                            </td>
                            <td>
                                <span class="__txt-nowrap">
                                    {{ \App\CentralLogics\Helpers::format_currency($transaction->paid_amount)}}
                                </span>
                            </td>
                        </tr>
                    </tbody>

                </table>

                <div class="text-center my-5 py-4">
                    {{ translate('If you require any assistance or have feedback or suggestions about our site, you can email us at ') }}
                    <a href="mailto:{{$company_email}}" style="text-decoration: none; color: inherit;">{{translate('messages.email')}}: {{$company_email}}</a>
                </div>

                <div class="d-block text-center mt-3" >
                    @php($social_media = \App\Models\SocialMedia::active()->get())
                    @if(isset($social_media))
                    <div style="display:flex !important;justify-content:center; gap: 12px;">
                            @foreach ($social_media as $item)
                                <a href="{{$item->link}}" target=”_blank” style="text-decoration: none !important">
                                    <img src="{{asset('public/assets/admin/img/'.$item->name.'.png')}}" alt="" style="height: 14px; width:14px;object-fit:contain">
                                </a>
                            @endforeach
                    </div>
                    @endif
                    @if ($company_links['web_app_url_status'])
                    <div class="mb-3 mt-2" style="font-weight: 400;font-size: 11px;line-height: 22px;color: #242A30;">
                        <a href="{{$company_links['web_app_url']}}" style="text-decoration: none; color: inherit;">{{$company_links['web_app_url']}}</a></div>
                    @endif
                        <div style="font-weight: 400;font-size: 11px;line-height: 22px;color: #242A30;">
                            <span style="margin-inline-end:5px;">
                                <a href="tel:{{$company_phone}}" style="text-decoration: none; color: inherit;">{{translate('messages.phone')}}: {{$company_phone}}</a>
                            </span>
                            <span>
                                <a href="mailto:{{$company_email}}" style="text-decoration: none; color: inherit;">{{translate('messages.email')}}: {{$company_email}}</a>
                            </span>
                        </div>
                        <div style="font-weight: 400;font-size: 11px;line-height: 22px;color: #242A30;">{{$company_address}}</div>
                        <span style="font-weight: 400;font-size: 10px;line-height: 22px;color: #242A30;">{{translate('messages.All copy right reserved',['year'=>date('Y'),'title'=>$company_name])}}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<h1></h1>

<br>
<br>

<div class="row">
    <section>
        <table class="">
            <tr>
                <th class="fz-12 font-normal pb-3">
                    {{translate('If_you_require_any_assistance_or_have_feedback_or_suggestions_about_our_site,_you')}} <br /> {{translate('can_email_us_at')}} <a href="mail::to({{ $company_email }})">{{ $company_email }}</a>
                </th>
            </tr>
            <tr>
                <th class="content-position-y bg-light py-4">
                    <div class="d-flex justify-content-center gap-2">
                        <div class="mb-2">
                            <i class="fa fa-phone"></i>
                            {{translate('phone')}}
                            : {{ $company_phone }}
                        </div>
                        <div class="mb-2">
                            <i class="fa fa-envelope" aria-hidden="true"></i>
                            {{translate('email')}}
                            : {{$company_email}}
                        </div>
                    </div>
                    <div class="mb-2">
                        {{url('/')}}
                    </div>
                    <div>
                        &copy; {{$company_name}}. <span
                    class="d-none d-sm-inline-block">{{$footer_text}}</span>
                    </div>
                </th>
            </tr>
        </table>
    </section>
</div>

</body>
</html>
