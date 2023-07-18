@extends('layouts.admin.app')

@section('title',translate('messages.Order').' '.translate('messages.Invoice'))


@section('content')

    <div class="content container-fluid initial-38">
        <div class="row justify-content-center" id="printableArea">
            <div class="col-md-12">
                <center>
                    <input type="button" class="btn btn-primary non-printable" onclick="printDiv('printableArea')"
                        value="{{ translate('messages.Proceed_If_thermal_printer_is_ready.') }}" />
                    <a href="{{ url()->previous() }}" class="btn btn-danger non-printable">{{ translate('messages.back') }}</a>
                </center>
                <hr class="non-printable">
                <div class="initial-38-1">
                    <div class="pt-3">
                        <img src="{{asset('/public/assets/admin/img/restaurant-invoice.png')}}" class="initial-38-2" alt="">
                    </div>
                    <div class="text-center pt-2 mb-3">
                        <h2  class="initial-38-3">{{ $order->restaurant->name }}</h2>
                        <h5 class="text-break initial-38-4">
                            {{ $order->restaurant->address }}
                        </h5>
                        <h5 class="initial-38-4 initial-38-3">
                            <span>{{ translate('phone') }}</span> <span>:</span> <span>{{ $order->restaurant->phone }}</span>
                        </h5>
                        @if ($order->restaurant->gst_status)
                            <h5 class="initial-38-4 initial-38-3 fz-12px">
                                <span>{{ translate('Gst No') }}</span> <span>:</span> <span>{{ $order->restaurant->gst_code }}</span>
                            </h5>
                        @endif
                        {{-- <span class="text-center">Gst: {{$order->restaurant->gst_code}}</span> --}}
                    </div>

                    <span class="initial-38-5">---------------------------------------------------------------------------------</span>
                    <div class="row mt-3">
                        <div class="col-6">
                            <h5>{{ translate('Order ID') }} :
                                <span class="font-light"> {{ $order['id'] }} </span>
                            </h5>
                        </div>
                        <div class="col-6">
                                <span class="font-light">
                                {{  Carbon\Carbon::parse($order['created_at'])->locale(app()->getLocale())->translatedFormat('d/M/Y ' . config('timeformat')) }}

                                {{-- {{ date('d/M/Y ' . config('timeformat'), strtotime($order['created_at'])) }} --}}
                            </h5>
                        </div>
                        <div class="col-12">
                            <h5>
                                {{ translate('Customer Name') }} :
                                <span class="font-light">
                                    {{ isset($order->delivery_address) ? json_decode($order->delivery_address, true)['contact_person_name'] : '' }}
                                </span>
                            </h5>
                            <h5>
                                {{ translate('messages.phone') }} :
                                <span class="font-light">
                                    {{ isset($order->delivery_address) ? json_decode($order->delivery_address, true)['contact_person_number'] : '' }}
                                </span>
                            </h5>

                            @if (isset($order->delivery_address) && json_decode($order->delivery_address, true)['road'] != null)
                            <h5>
                                {{ translate('messages.Road') }} :
                                <span class="font-light">
                                    {{ json_decode($order->delivery_address, true)['road'] }}
                                </span>
                            </h5>
                            @endif


                            @if (isset($order->delivery_address) && json_decode($order->delivery_address, true)['house'] != null)
                            <h5>
                                {{ translate('messages.House') }} :
                                <span class="font-light">
                                    {{  json_decode($order->delivery_address, true)['house'] }}
                                </span>
                            </h5>
                            @endif

                            @if (isset($order->delivery_address) && json_decode($order->delivery_address, true)['floor'] != null)
                                <h5>
                                    {{ translate('messages.floor') }} :
                                    <span class="font-light">
                                        {{ json_decode($order->delivery_address, true)['floor'] }}
                                    </span>
                                </h5>
                            @endif

                            <h5 class="text-break">
                                {{ translate('messages.address') }} :
                                <span class="font-light">
                                    {{ isset($order->delivery_address) ? json_decode($order->delivery_address, true)['address'] : '' }}
                                </span>
                            </h5>
                        </div>
                    </div>
                    <h5 class="text-uppercase"></h5>
                    <span class="initial-38-5">---------------------------------------------------------------------------------</span>
                    <table class="table table-bordered mt-1 mb-1">
                        <thead>
                            <tr>
                                <th class="initial-38-6">{{ translate('messages.qty') }}</th>
                                <th class="initial-38-7">{{ translate('messages.DESC') }}</th>
                                <th class="initial-38-7">{{ translate('messages.price') }}</th>
                            </tr>
                        </thead>

                        <tbody>
                            @php($sub_total = 0)
                            @php($total_tax = 0)
                            @php($total_dis_on_pro = 0)
                            @php($add_ons_cost = 0)
                            @foreach ($order->details as $detail)
                                @if ($detail->food_id || $detail->campaign == null)
                                    <tr>
                                        <td class="">
                                            {{ $detail['quantity'] }}
                                        </td>
                                        <td class="text-break">
                                        {{ json_decode($detail->food_details, true)['name'] }} <br>

                                    @if (count(json_decode($detail['variation'], true)) > 0)
                                                <strong><u>{{ translate('messages.variation') }} : </u></strong>
                                                @foreach(json_decode($detail['variation'],true) as  $variation)
                                                @if ( isset($variation['name'])  && isset($variation['values']))
                                                    <span class="d-block text-capitalize">
                                                            <strong>{{  $variation['name']}} - </strong>
                                                    </span>
                                                    @foreach ($variation['values'] as $value)
                                                    <span class="d-block text-capitalize">
                                                        &nbsp;   &nbsp; {{ $value['label']}} :
                                                        <strong>{{\App\CentralLogics\Helpers::format_currency( $value['optionPrice'])}}</strong>
                                                        </span>
                                                    @endforeach
                                                @else
                                                    @if (isset(json_decode($detail['variation'],true)[0]))
                                                        @foreach(json_decode($detail['variation'],true)[0] as $key1 =>$variation)
                                                            <div class="font-size-sm text-body">
                                                                <span>{{$key1}} :  </span>
                                                                <span class="font-weight-bold">{{$variation}}</span>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                        @break
                                                @endif
                                                        @endforeach
                                                        @else
                                                        <div class="font-size-sm text-body">
                                                            <span>{{ translate('messages.Price') }} : </span>
                                                            <span
                                                                class="font-weight-bold">{{ \App\CentralLogics\Helpers::format_currency($detail->price) }}</span>
                                                        </div>
                                        @endif




                                            @foreach (json_decode($detail['add_ons'], true) as $key2 => $addon)
                                                @if ($key2 == 0)
                                                    <strong><u>{{ translate('messages.addons') }} : </u></strong>
                                                @endif
                                                <div class="font-size-sm text-body">
                                                    <span class="text-break">{{ $addon['name'] }} : </span>
                                                    <span class="font-weight-bold">
                                                        {{ $addon['quantity'] }} x
                                                        {{ \App\CentralLogics\Helpers::format_currency($addon['price']) }}
                                                    </span>
                                                </div>
                                                @php($add_ons_cost += $addon['price'] * $addon['quantity'])
                                            @endforeach
                                        </td>
                                        <td class="w-28p">
                                            @php($amount = $detail['price'] * $detail['quantity'])
                                            {{ \App\CentralLogics\Helpers::format_currency($amount) }}
                                        </td>
                                    </tr>
                                    @php($sub_total += $amount)
                                    @php($total_tax += $detail['tax_amount'] * $detail['quantity'])
                                @elseif($detail->campaign)
                                    <tr>
                                        <td class="">
                                            {{ $detail['quantity'] }}
                                        </td>
                                        <td class="">
                                            {{ $detail->campaign['title'] }} <br>
                                        @if (count(json_decode($detail['variation'], true)) > 0)
                                            <strong><u>{{ translate('messages.variation') }} : </u></strong>
                                            @foreach(json_decode($detail['variation'],true) as  $variation)
                                            @if ( isset($variation['name'])  && isset($variation['values']))
                                                <span class="d-block text-capitalize">
                                                        <strong>{{  $variation['name']}} - </strong>
                                                </span>
                                                @foreach ($variation['values'] as $value)
                                                <span class="d-block text-capitalize">
                                                    &nbsp;   &nbsp; {{ $value['label']}} :
                                                    <strong>{{\App\CentralLogics\Helpers::format_currency( $value['optionPrice'])}}</strong>
                                                    </span>
                                                @endforeach
                                            @else
                                                @if (isset(json_decode($detail['variation'],true)[0]))
                                                    @foreach(json_decode($detail['variation'],true)[0] as $key1 =>$variation)
                                                        <div class="font-size-sm text-body">
                                                            <span>{{$key1}} :  </span>
                                                            <span class="font-weight-bold">{{$variation}}</span>
                                                        </div>
                                                    @endforeach
                                                @endif
                                                    @break
                                            @endif
                                                    @endforeach
                                                    @else
                                                    <div class="font-size-sm text-body">
                                                        <span>{{ translate('messages.Price') }} : </span>
                                                        <span
                                                            class="font-weight-bold">{{ \App\CentralLogics\Helpers::format_currency($detail->price) }}</span>
                                                    </div>
                                    @endif

                                            @foreach (json_decode($detail['add_ons'], true) as $key2 => $addon)
                                                @if ($key2 == 0)
                                                    <strong><u>{{ translate('messages.addons') }} : </u></strong>
                                                @endif
                                                <div class="font-size-sm text-body">
                                                    <span>{{ $addon['name'] }} : </span>
                                                    <span class="font-weight-bold">
                                                        {{ $addon['quantity'] }} x
                                                        {{ \App\CentralLogics\Helpers::format_currency($addon['price']) }}
                                                    </span>
                                                </div>
                                                @php($add_ons_cost += $addon['price'] * $addon['quantity'])
                                            @endforeach
                                        </td>
                                        <td class="w-28p">
                                            @php($amount = $detail['price'] * $detail['quantity'])
                                            {{ \App\CentralLogics\Helpers::format_currency($amount) }}
                                        </td>
                                    </tr>
                                    @php($sub_total += $amount)
                                    @php($total_tax += $detail['tax_amount'] * $detail['quantity'])
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                    <span class="initial-38-5">---------------------------------------------------------------------------------</span>
                    <div class="mb-2 initial-38-9">
                        <div class="px-3">
                            <dl class="row text-right">
                                <dt class="col-6 text-left">{{ translate('Items Price') }}:</dt>
                                <dd class="col-6">{{ \App\CentralLogics\Helpers::format_currency($sub_total) }}</dd>
                                <dt class="col-6 text-left">{{ translate('Addon Cost') }}:</dt>
                                <dd class="col-6">
                                    {{ \App\CentralLogics\Helpers::format_currency($add_ons_cost) }}
                                    <hr>
                                </dd>
                                <dt class="col-6 text-left">{{ translate('messages.subtotal') }}
                                    @if ($order->tax_status == 'included' )
                                    ({{ translate('messages.TAX_Included') }})
                                    @endif
                                    :</dt>
                                <dd class="col-6">
                                    {{ \App\CentralLogics\Helpers::format_currency($sub_total + $add_ons_cost) }}</dd>
                                <dt class="col-6 text-left">{{ translate('messages.discount') }}:</dt>
                                <dd class="col-6">
                                    - {{ \App\CentralLogics\Helpers::format_currency($order['restaurant_discount_amount']) }}
                                </dd>
                                <dt class="col-6 text-left">{{ translate('messages.coupon_discount') }}:</dt>
                                <dd class="col-6">
                                    - {{ \App\CentralLogics\Helpers::format_currency($order['coupon_discount_amount']) }}</dd>
                                    @if ($order->tax_status == 'excluded' || $order->tax_status == null  )
                                    <dt class="col-6 text-left">{{ translate('messages.vat/tax') }}:</dt>
                                    <dd class="col-6">
                                        {{ \App\CentralLogics\Helpers::format_currency($order['total_tax_amount']) }}
                                    </dd>
                                    @endif
                                <dt class="col-6 text-left">{{ translate('messages.delivery_man_tips') }}:</dt>
                                <dd class="col-6">
                                    @php($dm_tips = $order['dm_tips'])
                                    {{ \App\CentralLogics\Helpers::format_currency($dm_tips) }}
                                </dd>
                                <dt class="col-6 text-left">{{ translate('messages.delivery_charge') }}:</dt>
                                <dd class="col-6">
                                    @php($del_c = $order['delivery_charge'])
                                    {{ \App\CentralLogics\Helpers::format_currency($del_c) }}
                                    <hr>
                                </dd>

                                <dt class="col-6 text-left fz-20px">{{ translate('messages.total') }}:</dt>
                                <dd class="col-6 fz-20px">
                                    {{ \App\CentralLogics\Helpers::format_currency($order['order_amount'] ) }}
                                </dd>
                            </dl>
                        </div>
                    </div>

                    <div class="d-flex flex-row justify-content-between border-top pt-3">
                        <span class="text-capitalize d-flex"><span>{{ translate('Paid by') }}</span> <span>:</span> <span>{{ translate(str_replace('_', ' ', $order['payment_method'])) }}</span> </span>
                        {{-- <span>{{translate('messages.amount')}}: {{$order->adjusment}}</span>
                        <span>{{translate('messages.change')}}: {{$order->adjusment - $order->order_amount}}</span> --}}

                    </div>
                    <span class="initial-38-7">-------------------------------------------------------------------</span>
                    <h5 class="text-center pt-1  justify-content-center">
                        <span class="d-block">"""{{ translate('THANK YOU') }}"""</span>
                    </h5>
                    <span class="initial-38-7">-------------------------------------------------------------------</span>
                    <span class="d-block text-center">© {{date('Y')}} {{\App\Models\BusinessSetting::where(['key'=>'business_name'])->first()->value}}. {{translate('messages.all_right_reserved')}}</span>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')
    <script>
        function printDiv(divName) {

            if($('html').attr('dir') === 'rtl') {
                $('html').attr('dir', 'ltr')
                var printContents = document.getElementById(divName).innerHTML;
                var originalContents = document.body.innerHTML;
                document.body.innerHTML = printContents;
                $('.initial-38-1').attr('dir', 'rtl')
                window.print();
                document.body.innerHTML = originalContents;
                $('html').attr('dir', 'rtl')
                location.reload()
            }else{
                var printContents = document.getElementById(divName).innerHTML;
                var originalContents = document.body.innerHTML;
                document.body.innerHTML = printContents;
                window.print();
                document.body.innerHTML = originalContents;
                location.reload()
            }

        }
    </script>
@endpush
