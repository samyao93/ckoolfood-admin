@foreach ($restaurants as $key => $restaurant)
    <tr>
        <td>{{ $key + 1 }}</td>
        <td>
            <a href="{{ route('admin.restaurant.view', $restaurant->id) }}" alt="view restaurant" class="table-rest-info">
                <img onerror="this.src='{{ asset('public/assets/admin/img/100x100/food-default-image.png') }}'"
                    src="{{ asset('storage/app/public/restaurant') }}/{{ $restaurant['logo'] }}">
                <div class="info">
                    <span class="d-block text-body">
                        {{ Str::limit($restaurant->name, 20, '...') }}<br>
                        <!-- Rating -->
                        <span class="rating">
                            @php($restaurant_rating = $restaurant['rating'] == null ? 0 : array_sum($restaurant['rating']) / 5)
                            <i class="tio-star"></i> {{ $restaurant_rating }}
                        </span>
                        <!-- Rating -->
                    </span>
                </div>
            </a>
        </td>
        <td>
            <span class="d-block owner--name">
                {{ $restaurant->vendor->f_name . ' ' . $restaurant->vendor->l_name }}
            </span>
            <span class="d-block font-size-sm ">
                {{ $restaurant['phone'] }}
            </span>
        </td>
        <td>
            {{ $restaurant->email }}
        </td>
        <td>
            {{ $restaurant->zone ? $restaurant->zone->name : translate('messages.zone') . ' ' . translate('messages.deleted') }}
        </td>
        @php($status = $restaurant->pivot ? $restaurant->pivot->campaign_status : translate('messages.not_found'))
        <td class="text-capitalize">
            @if ($status == 'pending')
                <span class="badge badge-soft-info">
                    {{ translate('messages.not_approved') }}
                </span>
            @elseif($status == 'confirmed')
                <span class="badge badge-soft-success">
                    {{ translate('messages.confirmed') }}
                </span>
            @elseif($status == 'rejected')
                <span class="badge badge-soft-danger">
                    {{ translate('messages.rejected') }}
                </span>
            @else
                <span class="badge badge-soft-info">
                    {{ translate(str_replace('_', ' ', $status)) }}
                </span>
            @endif

        </td>
        <td>
            @if ($restaurant->pivot && $restaurant->pivot->campaign_status == 'pending')
                <div class="btn--container justify-content-center">
                    <a class="btn btn-sm btn--primary btn-outline-primary action-btn"
                        onclick="status_change_alert('{{ route('admin.campaign.restaurant_confirmation', [$campaign->id, $restaurant->id, 'confirmed']) }}', '{{ translate('messages.you_want_to_confirm_this_restaurant') }}', event)"
                        class="toggle-switch-input" data-toggle="tooltip" data-placement="top" title="{{translate('Approve')}}">
                        <i class="tio-done font-weight-bold"></i>
                    </a>
                    <a class="btn btn-sm btn--danger btn-outline-danger action-btn" href="javascript:"
                        onclick="status_change_alert('{{ route('admin.campaign.restaurant_confirmation', [$campaign->id, $restaurant->id, 'rejected']) }}', '{{ translate('messages.you_want_to_reject_this_restaurant') }}', event)" data-toggle="tooltip" data-placement="top" title="{{translate('Deny')}}">
                        <i class="tio-clear font-weight-bold"></i>
                    </a>
                    <div></div>
                </div>
            @elseif ($restaurant->pivot && $restaurant->pivot->campaign_status == 'rejected')

                <div class="btn--container justify-content-center">
                    <a class="btn btn-sm btn--primary btn-outline-primary action-btn"
                        onclick="status_change_alert('{{ route('admin.campaign.restaurant_confirmation', [$campaign->id, $restaurant->id, 'confirmed']) }}', '{{ translate('messages.you_want_to_confirm_this_restaurant') }}', event)"
                        class="toggle-switch-input" data-toggle="tooltip" data-placement="top" title="{{translate('Approve')}}">
                        <i class="tio-done font-weight-bold"></i>
                    </a>
                    {{-- <a class="btn btn-sm btn--danger btn-outline-danger action-btn" href="javascript:"
                    onclick="form_alert('restaurant-{{ $restaurant['id'] }}','Want to remove this restaurant ?')"
                    title="{{ translate('messages.delete') }} {{ translate('messages.restaurant') }}" data-toggle="tooltip" data-placement="top" title="{{translate('Delete')}}"><i
                        class="tio-delete-outlined"></i>
                </a>
                <form action="{{ route('admin.campaign.remove-restaurant', [$campaign->id, $restaurant['id']]) }}"
                    method="get" id="restaurant-{{ $restaurant['id'] }}">
                    @csrf @method('get')
                </form> --}}

                </div>

            @else
                <div class="btn--container justify-content-center">
                    <a class="btn btn-sm btn--danger btn-outline-danger action-btn" href="javascript:"
                        onclick="form_alert('restaurant-{{ $restaurant['id'] }}','Want to remove this restaurant ?')"
                        title="{{ translate('messages.delete') }} {{ translate('messages.restaurant') }}"><i
                            class="tio-delete-outlined"></i>
                    </a>
                    <form action="{{ route('admin.campaign.remove-restaurant', [$campaign->id, $restaurant['id']]) }}"
                        method="get" id="restaurant-{{ $restaurant['id'] }}">
                        @csrf @method('get')
                    </form>
                </div>
            @endif

        </td>

    </tr>
@endforeach
