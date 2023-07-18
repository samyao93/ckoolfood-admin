@php
$schedules = $subscription->schedules()->get();
@endphp
<div class="card mb-2">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-4">
                <div class="media">
                    <div class="media-body">
                        <h6 class="card-subtitle">{{translate('messages.Total_amount')}}</h6>
                        {{-- <h6 class="card-subtitle">{{translate('messages.billing_amount')}}</h6> --}}
                        <span class="card-title h3">{{\App\CentralLogics\Helpers::format_currency($subscription->billing_amount)}}</span>
                    </div>
                </div>
                <div class="d-lg-none">
                    <hr>
                </div>
            </div>

            {{-- <div class="col-sm-4 column-divider-sm">
                <div class="media">
                    <div class="media-body">
                        <h6 class="card-subtitle">{{translate('messages.paid_amount')}}</h6>
                        <span class="card-title h3">{{\App\CentralLogics\Helpers::format_currency($subscription->paid_amount)}}</span>
                    </div>
                </div>
                <div class="d-lg-none">
                    <hr>
                </div>
            </div> --}}

            {{-- <div class="col-sm-4 column-divider-lg">
                <div class="media">
                    <div class="media-body">
                        <h6 class="card-subtitle">{{translate('messages.due')}}</h6>
                        @php
                            $due = $subscription->billing_amount - $subscription->paid_amount;
                        @endphp
                        <span class="card-title h3">{{\App\CentralLogics\Helpers::format_currency($due > 0 ? $due : 0)}}</span>
                    </div>
                </div>
                <div class="d-lg-none">
                    <hr>
                </div>
            </div> --}}
        </div>
    </div>
</div>
<div class="card mb-2">
    <div class="card-header">
        <h5 class="card-header-title">{{translate('messages.subscription_items')}}<span class="badge badge-soft-dark ml-2">{{count($subscription->order ? $subscription->order->details : [])}}</span></h5>
    </div>
    <div class="card-body">
        <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
            <thead>
                <tr>
                    <th>{{translate('messages.sl#')}}</th>
                    <th>{{translate('messages.food_descriptions')}}</th>
                    <th>{{translate('messages.Unit_price')}}</th>
                    <th>{{translate('messages.quantity')}}</th>
                </tr>
            </thead>
            <tbody>
                @if ($subscription->order)
                    @foreach ($subscription->order->details as $key => $detail)
                        @php
                            if (isset($detail->food_id))
                            {
                                $detail->food = json_decode($detail->food_details, true);
                            }else{
                                $detail->campaign = json_decode($detail->food_details, true);
                            }
                        @endphp

                    <tr>
                        <td>{{$key+1}}</td>
                        <td>
                            <a class="media align-items-center"  href="{{isset($detail->food_id) ? route('vendor.food.view',[$detail->food['id']]) :  '#'}}">
                            <img class="avatar avatar-lg mr-3"
                                @if (isset($detail->food['image']))
                                src="{{asset('storage/app/public/product')}}/{{$detail->food['image']}}"
                                @else
                                src="{{ asset('storage/app/public/campaign')}}/{{ isset($detail->campaign['image']) ? $detail->campaign['image'] :'' }}"
                                @endif
                                onerror="this.src='{{asset('public/assets/admin/img/160x160/img2.jpg')}}'" alt="{{isset($detail->food_id) ? $detail->food['name'] : $detail->campaign['name']}} image">
                                <div class="media-body">
                                    <h5 class="text-hover-primary mb-0">{{isset($detail->food_id) ? Str::limit($detail->food['name'],30)  : Str::limit($detail->campaign['name'],30)  }}</h5>
                                    @if (count(json_decode($detail['variation'], true)) > 0)
                                        @foreach(json_decode($detail['variation'],true) as  $variation)
                                            @if ( isset($variation['name'])  && isset($variation['values']))
                                                <span class="d-block text-capitalize">
                                                        <strong>
                                                    {{  $variation['name']}} -
                                                        </strong>
                                                </span>
                                                    @foreach ($variation['values'] as $value)
                                                    <span class="d-block text-capitalize">
                                                        &nbsp;   &nbsp; {{ $value['label']}} :
                                                        <strong>{{\App\CentralLogics\Helpers::format_currency( $value['optionPrice'])}}</strong>
                                                    </span>
                                                    @endforeach
                                            @else
                                                @if (isset(json_decode($detail['variation'],true)[0]))
                                                    <strong><u> {{  translate('messages.Variation') }} : </u></strong>
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
                                    @endif

                                    @foreach (json_decode($detail['add_ons'], true) as $key2 => $addon)
                                        @if ($key2 == 0)
                                            <strong><u>{{ translate('messages.addons') }} : </u></strong>
                                        @endif
                                        <div class="font-size-sm text-body">
                                            <span>{{ Str::limit($addon['name'], 20, '...') }} : </span>
                                            <span class="font-weight-bold">
                                                {{ $addon['quantity'] }} x
                                                {{ \App\CentralLogics\Helpers::format_currency($addon['price']) }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </a>
                        </td>
                        <td>
                            <h6>{{ \App\CentralLogics\Helpers::format_currency($detail['price']) }}</h6>
                        </td>
                        <td>
                            <h5>{{ $detail['quantity'] }}</h5>
                        </td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>

    </div>
</div>
<div class="card">
    <div class="card-header">
        <h5 class="card-header-title">{{translate('messages.subscription_schedules')}}<span class="badge badge-soft-dark ml-2">{{count($schedules)}}</span></h5>
    </div>
    <!-- Table -->
    <div class="table-responsive datatable-custom">
        <table id="columnSearchDatatable"
            class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
            data-hs-datatables-options='{
                "order": [],
                "orderCellsTop": true,
                "paging":false
            }'>
            <thead class="thead-light">
            <tr>
                <th>{{translate('messages.#')}}</th>
                <th style="width: 33%" class="text-center">{{translate('messages.day')}}</th>
                <th style="width: 33%">{{translate('messages.time')}}</th>
            </tr>
            </thead>

            <tbody>
            @php
                $days = ['sunday', 'monday', 'tuesday', 'webnesday', 'thursday', 'friday', 'saturday'];
            @endphp
            @foreach($schedules as $key=>$schedule)
                <tr>
                    <td>{{$key+1}}</td>
                    <td class="table-column-pl-0 text-center">
                        @if ($schedule->type == 'weekly')
                        {{ translate('messages.'.$days[$schedule->day]) }}
                        @elseif ($schedule->type == 'daily')
                        {{ translate('messages.daily') }}
                        @else
                        {{ $schedule->day }}
                        @endif
                        {{-- {{$schedule->type == 'weekly' ? translate('messages.'.$days[$schedule->day]) : ''}} --}}
                    </td>
                        <td>
                            {{  Carbon\Carbon::parse($schedule->time)->locale(app()->getLocale())->translatedFormat(config('timeformat')) }}

                        {{-- {{ Carbon\Carbon::parse($schedule->time)->format('h:i A')}} --}}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <!-- Footer -->
        <div class="card-footer">
            <!-- Pagination -->

        <!-- End Pagination -->
        </div>
        <!-- End Footer -->
    </div>
</div>
