@foreach ($transcations as $key => $transcation)

    <tr>
        <td>{{ $transcation->id }}</td>
        <td>
            {{ $transcation->created_at->format('d M Y') }}
        </td>
            <td>
                {{ $transcation->package->package_name }}
            </td>
            <td>{{ \App\CentralLogics\Helpers::format_currency($transcation->package->price) }}</td>
        <td>
            {{ $transcation->validity }} {{ translate('messages.Days') }}
        </td>
        <td>
            <div>
                {{translate('paid')}}   {{ \App\CentralLogics\Helpers::format_currency($transcation->paid_amount) }}
        </div>
        <small class="text-success text-capitalize">
            @if ($transcation->payment_method == 'wallet')
                {{ translate('messages.Wallet payment') }}
            @elseif($transcation->payment_method == 'manual_payment_admin')
                {{ translate('messages.Manual payment') }}
            @elseif($transcation->payment_method == 'manual_payment_by_restaurant')
                {{ translate('messages.Manual payment') }}
            @elseif($transcation->payment_method == 'free_trial')
                {{ translate('messages.free_trial') }}
            @else
            {{ translate($transcation->payment_method) }}
            @endif

        </small>
        </td>
        <td>
            <div class="btn--container justify-content-center">
                <a class="btn btn-sm btn--warning btn-outline-warning action-btn"
                    href="{{ route('admin.subscription.invoice', [$transcation['id']]) }}"
                    title="{{ translate('messages.view') }} {{ translate('messages.restaurant') }}"><i
                        class="tio-invisible"></i>
                </a>
            </div>
        </td>
    </tr>
@endforeach
