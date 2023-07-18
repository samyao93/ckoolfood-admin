@php($restaurant_id = \App\CentralLogics\Helpers::get_restaurant_id())
@foreach($campaigns as $key=>$campaign)
    <tr>
        <td>{{$key+1}}</td>
        <td>
            <span class="d-block font-size-sm text-body">
                {{Str::limit($campaign['title'],25,'...')}}
            </span>
        </td>
        <td>
            <div class="overflow-hidden">
                <img class="initial-74" src="{{asset('storage/app/public/campaign')}}/{{$campaign['image']}}" onerror="this.src='{{asset('public/assets/admin/img/160x160/img2.jpg')}}'">
            </div>
        </td>
        <td>
            {{-- <span class="bg-gradient-light text-dark">{{$campaign->start_date? Carbon\Carbon::parse($campaign->start_date)->locale(app()->getLocale())->translatedFormat('d M Y'). ' - ' .$campaign->end_date->format('d M, Y'): 'N/A'}}</span> --}}
            <span class="bg-gradient-light text-dark">{{$campaign->start_date?Carbon\Carbon::parse($campaign->start_date)->locale(app()->getLocale())->translatedFormat('d M Y') : 'N/A'}}
            </span>
            <span class="bg-gradient-light text-dark">-</span>
            <span class="bg-gradient-light text-dark">{{$campaign->start_time? Carbon\Carbon::parse($campaign->end_date)->locale(app()->getLocale())->translatedFormat('d M Y'): 'N/A' }}</span>
        </td>
        <td>
            {{-- <span class="bg-gradient-light text-dark">{{$campaign->start_time?date(config('timeformat'),strtotime($campaign->start_time)). ' - ' .date(config('timeformat'),strtotime($campaign->end_time)): 'N/A'}}</span> --}}
            <span class="bg-gradient-light text-dark">{{$campaign->start_time?Carbon\Carbon::parse($campaign->start_time)->locale(app()->getLocale())->translatedFormat(config('timeformat')). ' - ' .Carbon\Carbon::parse($campaign->end_time)->locale(app()->getLocale())->translatedFormat(config('timeformat')): 'N/A'}}</span>
        </td>
        <td>
            <?php
                $restaurant_ids = [];
                foreach($campaign->restaurants as $restaurant)
                {
                    $restaurant_ids[] = $restaurant->id;
                }
            ?>
                @if(in_array($restaurant_id,$restaurant_ids))
                <!-- <button type="button" onclick="location.href='{{route('vendor.campaign.remove-restaurant',[$campaign['id'],$restaurant_id])}}'" title="You are already joined. Click to out from the campaign." class="btn btn-outline-danger">Out</button> -->
                <button type="button" onclick="form_alert('campaign-{{$campaign['id']}}','{{translate('messages.alert_restaurant_out_from_campaign')}}')" title="You are already joined. Click to out from the campaign." class="btn btn--danger text-white">{{  translate('Leave Campaign') }}</button>
                <form action="{{route('vendor.campaign.remove-restaurant',[$campaign['id'],$restaurant_id])}}"
                        method="GET" id="campaign-{{$campaign['id']}}">
                    @csrf
                </form>
                @else
                <button type="button" class="btn btn--primary text-white" onclick="form_alert('campaign-{{$campaign['id']}}','{{translate('messages.alert_restaurant_join_campaign')}}')" title="Click to join the campaign">{{  translate('Join Campaign') }}</button>
                <form action="{{route('vendor.campaign.addrestaurant',[$campaign['id'],$restaurant_id])}}"
                        method="GET" id="campaign-{{$campaign['id']}}">
                    @csrf
                </form>
                @endif


            </td>
    </tr>
@endforeach
