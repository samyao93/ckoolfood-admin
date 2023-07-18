@extends('layouts.vendor.app')

@section('title',translate('Review List'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
    <!-- Page Header -->
     <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h2 class="page-header-title text-capitalize">
                        <div class="card-header-icon d-inline-flex mr-2 img">
                            <img src="{{asset('/public/assets/admin/img/resturant-panel/page-title/review.png')}}" alt="public">
                        </div>
                        <span>
                            {{translate('messages.customers')}} {{translate('messages.reviews')}}
                        </span>
                    </h2>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <!-- Card -->
        <div class="card">
            <!-- Header -->
            <div class="card-header py-2 border-0">
                <div class="search--button-wrapper justify-content-end">
                    <form action="javascript:" id="search-form" class="my-2 vendor--search">
                        <div class="input--group input-group">
                            <input type="search" name="search" id="column1_search" class="form-control" placeholder="{{ translate('messages.Ex :') }} {{ translate('Search by food name, name or phone...') }}" required>
                            <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- End Header -->

            <!-- Table -->
            <div class="table-responsive datatable-custom">
                <table id="columnSearchDatatable"
                        class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                        data-hs-datatables-options='{
                            "order": [],
                            "orderCellsTop": true,
                            "paging": false
                        }'>
                    <thead class="thead-light">
                    <tr>
                        <th>{{ translate('messages.sl') }}</th>
                        <th>{{translate('messages.food')}}</th>
                        <th>{{translate('messages.reviewer')}}</th>
                        <th>{{translate('messages.review')}}</th>
                        <th>{{translate('messages.date')}}</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($reviews as $key=>$review)
                        <tr>
                            <td>{{$key+$reviews->firstItem()}}</td>
                            <td>
                                @if ($review->food)
                                <div class="position-relative media align-items-center">
                                    <a class="absolute--link" href="{{route('vendor.food.view',[$review->food['id']])}}">
                                    </a>
                                    <img class="avatar avatar-lg mr-3" src="{{asset('storage/app/public/product')}}/{{$review->food['image']}}"
                                    onerror="this.src='{{asset('/public/assets/admin/img/100x100/food-default-image.png')}}'" alt="{{$review->food->name}} image">
                                    <div class="media-body">
                                        <h5 class="text-hover-primary mb-0">{{Str::limit($review->food['name'],10)}}</h5>
                                        <!-- Static -->
                                        <a href=""  class="fz--12 text-body important--link">Order ID #100070</a>
                                        <!-- Static -->
                                    </div>
                                </div>
                                @else
                                    {{translate('messages.Food deleted!')}}
                                @endif
                            </td>
                            <td>
                                @if($review->customer)
                                <div>
                                    <h5 class="d-block text-hover-primary mb-1">{{Str::limit($review->customer['f_name']." ".$review->customer['l_name'])}} <i
                                            class="tio-verified text-primary" data-toggle="tooltip" data-placement="top"
                                            title="Verified Customer"></i></h5>
                                    <span class="d-block font-size-sm text-body">{{Str::limit($review->customer->phone)}}</span>
                                </div>
                                @else
                                {{translate('messages.customer_not_found')}}
                                @endif
                            </td>
                            <td>
                                <div class="text-wrap w-18rem">
                                    <label class="rating">
                                        <i class="tio-star"></i>
                                        <span>{{$review->rating}}</span>
                                    </label>
                                    <p>
                                        {{Str::limit($review['comment'], 80)}}
                                    </p>
                                </div>
                            </td>
                            <td>
                                <span class="d-block">
                                    {{date('d M Y',strtotime($review['created_at']))}}
                                </span>
                                <span class="d-block">{{date(config('timeformat'),strtotime($review['created_at']))}}</span>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @if(count($reviews) === 0)
                <div class="empty--data">
                    <img src="{{asset('/public/assets/admin/img/empty.png')}}" alt="public">
                    <h5>
                        {{translate('no_data_found')}}
                    </h5>
                </div>
                @endif
                <table>
                    <tfoot>
                    {!! $reviews->links() !!}
                    </tfoot>
                </table>
            </div>
            <!-- End Table -->
        </div>
        <!-- End Card -->
    </div>

@endsection

@push('script_2')
    <script>
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
        });
    </script>
@endpush
