@php($params = session('dash_params'))
@if($params['zone_id']!='all')
@php($zone_name=\App\Models\Zone::where('id',$params['zone_id'])->first()->name)
@else
@php($zone_name=translate('All'))
@endif
<span class="badge badge-soft--info my-2">{{translate('messages.zone')}} : {{$zone_name}}</span>
