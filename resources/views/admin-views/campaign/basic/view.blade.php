@extends('layouts.admin.app')

@section('title',translate('Campaign view'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title text-break">{{$campaign->title}}
                <span class="badge badge-soft-secondary badge-pill" id="itemCount"> {{ $restaurants->total() }}</span></h1>
        </div>
        <!-- End Page Header -->
        <!-- Card -->
        <div class="card mb-3 mb-lg-5">
            <!-- Body -->
            <div class="card-body">
                <div class="row align-items-md-center">
                    <div class="col-md-4 mb-3 mb-md-0">
                            <img class="rounded initial-13" src="{{asset('storage/app/public/campaign')}}/{{$campaign->image}}"
                                 onerror="this.src='{{asset('/public/assets/admin/img/900x400/img1.png')}}'"
                                 alt="Image Description">
                    </div>
                    <div class="col-md-8">
                        <h4>{{translate('messages.short')}} {{translate('messages.description')}} : </h4>
                        <p>{{$campaign->description}}</p>

                        <form action="{{route('admin.campaign.addrestaurant',$campaign->id)}}" id="restaurant-add-form" method="POST">
                            @csrf
                            <!-- Search -->
                            <div class="d-flex flex-wrap g-2">
                                @php($allrestaurants=App\Models\Restaurant::Active()->get(['id','name']))
                                <div class="flex-grow-1">
                                    <select name="restaurant_id" id="restaurant_id" class="form-control js-select2-custom h--45px" required>
                                        <option value="" selected disabled>{{ translate('Select Restaurant') }}</option>
                                        @forelse($allrestaurants as $restaurant)
                                        @if(!in_array($restaurant->id, $restaurant_ids))
                                            <option value="{{$restaurant->id}}" >{{$restaurant->name}}</option>
                                        @endif
                                        @empty
                                        <option value="">{{translate('no_data_found')}}</option>
                                        @endforelse
                                    </select>
                                </div>
                                <div>
                                    <button type="submit" class="btn btn--primary">{{translate('messages.add')}} {{translate('messages.restaurant')}}</button>
                                </div>
                            </div>
                            <!-- End Search -->
                        </form>
                    </div>

                </div>
            </div>
            <!-- End Body -->
        </div>
        <!-- End Card -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <!-- Card -->
                <div class="card">
                    <!-- Table -->
                    <div class="card-header border-0 search--button-wrapper">
                        <h5 class="card-title"></h5>
                        <form  id="search-form">
                            <!-- Search -->
                            {{-- <input type="hidden" name="campaign_id" value="{{ $campaign->id }}" > --}}
                            <div class="input--group input-group input-group-merge input-group-flush">
                                <input id="datatableSearch_" type="search" name="search" class="form-control"
                                        placeholder="{{translate('messages.search')}} {{ translate('by_restaurant_name') }}" aria-label="Search" required>
                                <button type="submit" class="btn btn--secondary">
                                    <i class="tio-search"></i>
                                </button>

                            </div>
                            <!-- End Search -->
                        </form>
                    </div>
                    <div class="table-responsive datatable-custom">
                        <table id="columnSearchDatatable"
                               class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                               data-hs-datatables-options='{
                                 "order": [],
                                 "orderCellsTop": true
                               }'>
                            <thead class="thead-light">
                            <tr>
                                <th>{{ translate('messages.sl') }}</th>
                                {{-- <th class="w-15p">{{translate('messages.logo')}}</th> --}}
                                <th >{{translate('messages.restaurant')}}</th>
                                <th>{{translate('messages.owner')}}</th>
                                <th>{{translate('messages.email')}}</th>
                                <th>{{translate('messages.zone')}}</th>
                                <th>{{translate('messages.status')}}</th>
                                <th class="text-center">{{translate('messages.action')}}</th>
                            </tr>
                            </thead>

                            <tbody id="set-rows">
                                @include('admin-views.campaign.basic.partials._restaurant_table' ,['restaurants' =>$restaurants])
                            </tbody>
                        </table>

                        @if(count($restaurants) === 0)
                        <div class="empty--data">
                            <img src="{{asset('/public/assets/admin/img/empty.png')}}" alt="public">
                            <h5>
                                {{translate('no_data_found')}}
                            </h5>
                        </div>
                        @endif
                        <div class="page-area px-4 pb-3">
                            <div class="d-flex align-items-center justify-content-end">
                                <div>
                                    {!! $restaurants->links() !!}
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- End Table -->
                </div>
                <!-- End Card -->
            </div>
        </div>
    </div>

@endsection

@push('script_2')
    <script>
                function status_change_alert(url, message, e) {
            e.preventDefault();
            Swal.fire({
                title: '{{ translate('Are you sure?') }}',
                text: message,
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#FC6A57',
                cancelButtonText: '{{ translate('no') }}',
                confirmButtonText: '{{ translate('yes') }}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    location.href=url;
                }
            })
        }
        $(document).on('ready', function () {
            // INITIALIZATION OF DATATABLES
            // =======================================================
            var datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'));

            $('#column1_search').on('keyup', function () {
                datatable
                    .columns(1)
                    .search(this.value)
                    .draw();
            });

            $('#column2_search').on('keyup', function () {
                datatable
                    .columns(2)
                    .search(this.value)
                    .draw();
            });

            $('#column3_search').on('keyup', function () {
                datatable
                    .columns(3)
                    .search(this.value)
                    .draw();
            });

            $('#column4_search').on('keyup', function () {
                datatable
                    .columns(4)
                    .search(this.value)
                    .draw();
            });


            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function () {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });
    </script>
@endpush
