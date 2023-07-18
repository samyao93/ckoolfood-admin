@php($params = session('dash_params'))
@if ($params['zone_id'] != 'all')
    @php($zone_name = \App\Models\Zone::where('id', $params['zone_id'])->first()->name)
@else
@php($zone_name=translate('All'))
@endif
<div class="chartjs-custom mx-auto">
    <canvas id="user-overview" class="mt-2"></canvas>
</div>
<div class="total--users">
    <span>{{translate('messages.total_users')}}</span>
    <h3>{{ $data['customer'] + $data['restaurants'] + $data['delivery_man'] }}</h3>
</div>

<script src="{{ asset('public/assets/admin') }}/vendor/chart.js/dist/Chart.min.js"></script>

<script>
    var ctx = document.getElementById('user-overview');
    var myChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            datasets: [{
                label: 'User',
                data: ['{{ $data['customer'] }}', '{{ $data['restaurants'] }}',
                    '{{ $data['delivery_man'] }}'
                ],
                backgroundColor: [
                    '#FFC960',
                    '#0661CB',
                    '#7ECAFF'
                ],
                hoverOffset: 3
            }],
            labels: [
                '{{ translate('messages.customer') }}',
                '{{ translate('messages.restaurant') }}',
                '{{ translate('messages.delivery_man') }}'
            ],
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            legend: {
                display: false,
                position: 'chartArea',
            }
        }
    });
</script>
