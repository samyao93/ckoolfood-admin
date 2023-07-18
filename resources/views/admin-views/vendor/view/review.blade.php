@extends('layouts.admin.app')

@section('title',$restaurant->name."'s".translate('messages.review'))

@push('css_or_js')
    <!-- Custom styles for this page -->
    <link href="{{asset('public/assets/admin/css/croppie.css')}}" rel="stylesheet">

@endpush

@section('content')
<div class="content container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex flex-wrap justify-content-between align-items-center">
            <h1 class="page-header-title text-break">
                <i class="tio-museum"></i> <span>{{$restaurant->name}}</span>
            </h1>
        </div>
        <!-- Nav Scroller -->
        <div class="js-nav-scroller hs-nav-scroller-horizontal">
            <span class="hs-nav-scroller-arrow-prev initial-hidden">
                <a class="hs-nav-scroller-arrow-link" href="javascript:;">
                    <i class="tio-chevron-left"></i>
                </a>
            </span>

            <span class="hs-nav-scroller-arrow-next initial-hidden">
                <a class="hs-nav-scroller-arrow-link" href="javascript:;">
                    <i class="tio-chevron-right"></i>
                </a>
            </span>

            <!-- Nav -->
            <ul class="nav nav-tabs page-header-tabs">
                <li class="nav-item">
                    <a class="nav-link" href="{{route('admin.restaurant.view', $restaurant->id)}}">{{translate('messages.overview')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('admin.restaurant.view', ['restaurant'=>$restaurant->id, 'tab'=> 'order'])}}"  aria-disabled="true">{{translate('messages.orders')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('admin.restaurant.view', ['restaurant'=>$restaurant->id, 'tab'=> 'product'])}}"  aria-disabled="true">{{translate('messages.foods')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{route('admin.restaurant.view', ['restaurant'=>$restaurant->id, 'tab'=> 'reviews'])}}"  aria-disabled="true">{{translate('messages.reviews')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('admin.restaurant.view', ['restaurant'=>$restaurant->id, 'tab'=> 'discount'])}}"  aria-disabled="true">{{translate('discounts')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('admin.restaurant.view', ['restaurant'=>$restaurant->id, 'tab'=> 'transaction'])}}"  aria-disabled="true">{{translate('messages.transactions')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('admin.restaurant.view', ['restaurant'=>$restaurant->id, 'tab'=> 'settings'])}}"  aria-disabled="true">{{translate('messages.settings')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('admin.restaurant.view', ['restaurant'=>$restaurant->id, 'tab'=> 'conversations'])}}"  aria-disabled="true">{{translate('messages.conversations')}}</a>
                </li>
                @if ($restaurant->restaurant_model != 'none' && $restaurant->restaurant_model != 'commission'  )
                <li class="nav-item">
                    <a class="nav-link" href="{{route('admin.restaurant.view', ['restaurant'=>$restaurant->id, 'tab'=> 'subscriptions'])}}"  aria-disabled="true">{{translate('messages.subscription')}}</a>
                </li>
                @endif
            </ul>
            <!-- End Nav -->
        </div>
        <!-- End Nav Scroller -->
    </div>
        <!-- End Page Header -->
    <!-- Reviews -->
    <div class="resturant-review-top" id="restaurant_details">
        <div class="resturant-review-left mb-3">
            @php($reviews = $restaurant->reviews()->with('food',function($query){
                $query->withoutGlobalScope(\App\Scopes\RestaurantScope::class);
            })->get())
            {{-- {{ dd($reviews) }} --}}
            @php($user_rating = null)
            @php($total_rating = 0)
            @php($total_reviews = 0)
            @foreach ($reviews as $key=>$value)
                @php($user_rating += $value->rating)
                @php($total_rating +=1)
                @php($total_reviews +=1)
            @endforeach
            @php($user_rating = isset($user_rating) ? ($user_rating)/count($reviews) : 0)
            {{-- {{$review[0]->rating}} --}}
            <h1 class="title">{{ number_format($user_rating, 1)}}<span class="out-of">/5</span></h1>

            {{-- {{ dd(number_format($user_rating, 1))  }} --}}
            @if ($user_rating == 5)
            <div class="rating">
                <span><i class="tio-star"></i></span>
                <span><i class="tio-star"></i></span>
                <span><i class="tio-star"></i></span>
                <span><i class="tio-star"></i></span>
                <span><i class="tio-star"></i></span>
            </div>
            @elseif ($user_rating < 5 && $user_rating >= 4.5)
            <div class="rating">
                <span><i class="tio-star"></i></span>
                <span><i class="tio-star"></i></span>
                <span><i class="tio-star"></i></span>
                <span><i class="tio-star"></i></span>
                <span><i class="tio-star-half"></i></span>
            </div>
            @elseif ($user_rating < 4.5 && $user_rating >= 4)
            <div class="rating">
                <span><i class="tio-star"></i></span>
                <span><i class="tio-star"></i></span>
                <span><i class="tio-star"></i></span>
                <span><i class="tio-star"></i></span>
                <span><i class="tio-star-outlined"></i></span>
            </div>
            @elseif ($user_rating < 4 && $user_rating >= 3.5)
            <div class="rating">
                <span><i class="tio-star"></i></span>
                <span><i class="tio-star"></i></span>
                <span><i class="tio-star"></i></span>
                <span><i class="tio-star-half"></i></span>
                <span><i class="tio-star-outlined"></i></span>
            </div>
            @elseif ($user_rating < 3.5 && $user_rating >= 3)
            <div class="rating">
                <span><i class="tio-star"></i></span>
                <span><i class="tio-star"></i></span>
                <span><i class="tio-star"></i></span>
                <span><i class="tio-star-outlined"></i></span>
                <span><i class="tio-star-outlined"></i></span>
            </div>
            @elseif ($user_rating < 3 && $user_rating >= 2.5)
            <div class="rating">
                <span><i class="tio-star"></i></span>
                <span><i class="tio-star"></i></span>
                <span><i class="tio-star-half"></i></span>
                <span><i class="tio-star-outlined"></i></span>
                <span><i class="tio-star-outlined"></i></span>
            </div>
            @elseif ($user_rating < 2.5 && $user_rating > 2)
            <div class="rating">
                <span><i class="tio-star"></i></span>
                <span><i class="tio-star"></i></span>
                <span><i class="tio-star-outlined"></i></span>
                <span><i class="tio-star-outlined"></i></span>
                <span><i class="tio-star-outlined"></i></span>
            </div>
            @elseif ($user_rating < 2 && $user_rating >= 1.5)
            <div class="rating">
                <span><i class="tio-star"></i></span>
                <span><i class="tio-star-half"></i></span>
                <span><i class="tio-star-outlined"></i></span>
                <span><i class="tio-star-outlined"></i></span>
                <span><i class="tio-star-outlined"></i></span>
            </div>
            @elseif ($user_rating < 1.5 && $user_rating > 1)
            <div class="rating">
                <span><i class="tio-star"></i></span>
                <span><i class="tio-star-outlined"></i></span>
                <span><i class="tio-star-outlined"></i></span>
                <span><i class="tio-star-outlined"></i></span>
                <span><i class="tio-star-outlined"></i></span>
            </div>
            @elseif ($user_rating < 1 && $user_rating > 0)
            <div class="rating">
                <span><i class="tio-star-half"></i></span>
                <span><i class="tio-star-outlined"></i></span>
                <span><i class="tio-star-outlined"></i></span>
                <span><i class="tio-star-outlined"></i></span>
                <span><i class="tio-star-outlined"></i></span>
            </div>
            @elseif ($user_rating == 1)
            <div class="rating">
                <span><i class="tio-star"></i></span>
                <span><i class="tio-star-outlined"></i></span>
                <span><i class="tio-star-outlined"></i></span>
                <span><i class="tio-star-outlined"></i></span>
                <span><i class="tio-star-outlined"></i></span>
            </div>
            @elseif ($user_rating == 0)
            <div class="rating">
                <span><i class="tio-star-outlined"></i></span>
                <span><i class="tio-star-outlined"></i></span>
                <span><i class="tio-star-outlined"></i></span>
                <span><i class="tio-star-outlined"></i></span>
                <span><i class="tio-star-outlined"></i></span>
            </div>
            @endif
            <div class="info">
                {{-- <span class="mr-3">{{$total_rating}} {{translate('messages.ratings')}}</span> --}}
                <span>{{$total_reviews}} {{translate('messages.reviews')}}</span>
            </div>
        </div>
        <div class="resturant-review-right">
            <ul class="list-unstyled list-unstyled-py-2 mb-0">
            @php($ratings = $restaurant->rating)
            @php($five = $ratings[0])
            @php($four = $ratings[1])
            @php($three = $ratings[2])
            @php($two = $ratings[3])
            @php($one = $ratings[4])
            @php($total_rating = $one+$two+$three+$four+$five)
            @php($total_rating = $total_rating==0?1:$total_rating)
            <!-- Review Ratings -->
                <li class="d-flex align-items-center font-size-sm">
                    <span
                        class="progress-name mr-3">{{translate('messages.excellent')}}</span>
                    <div class="progress flex-grow-1">
                        <div class="progress-bar" role="progressbar"
                                style="width: {{($five/$total_rating)*100}}%;"
                                aria-valuenow="{{($five/$total_rating)*100}}"
                                aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <span class="ml-3">{{$five}}</span>
                </li>
                <!-- End Review Ratings -->

                <!-- Review Ratings -->
                <li class="d-flex align-items-center font-size-sm">
                    <span class="progress-name mr-3">{{translate('messages.good')}}</span>
                    <div class="progress flex-grow-1">
                        <div class="progress-bar" role="progressbar"
                                style="width: {{($four/$total_rating)*100}}%;"
                                aria-valuenow="{{($four/$total_rating)*100}}"
                                aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <span class="ml-3">{{$four}}</span>
                </li>
                <!-- End Review Ratings -->

                <!-- Review Ratings -->
                <li class="d-flex align-items-center font-size-sm">
                    <span class="progress-name mr-3">{{translate('messages.average')}}</span>
                    <div class="progress flex-grow-1">
                        <div class="progress-bar" role="progressbar"
                                style="width: {{($five/$total_rating)*100}}%;"
                                aria-valuenow="{{($five/$total_rating)*100}}"
                                aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <span class="ml-3">{{$three}}</span>
                </li>
                <!-- End Review Ratings -->

                <!-- Review Ratings -->
                <li class="d-flex align-items-center font-size-sm">
                    <span class="progress-name mr-3">{{translate('messages.below_average')}}</span>
                    <div class="progress flex-grow-1">
                        <div class="progress-bar" role="progressbar"
                                style="width: {{($two/$total_rating)*100}}%;"
                                aria-valuenow="{{($two/$total_rating)*100}}"
                                aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <span class="ml-3">{{$two}}</span>
                </li>
                <!-- End Review Ratings -->

                <!-- Review Ratings -->
                <li class="d-flex align-items-center font-size-sm">

                    <span class="progress-name mr-3">{{translate('messages.poor')}}</span>
                    <div class="progress flex-grow-1">
                        <div class="progress-bar" role="progressbar"
                                style="width: {{($one/$total_rating)*100}}%;"
                                aria-valuenow="{{($one/$total_rating)*100}}"
                                aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <span class="ml-3">{{$one}}</span>
                </li>
                <!-- End Review Ratings -->
            </ul>
        </div>
    </div>
    <!-- Reviews -->
    <!-- Page Heading -->
    <div class="card h-100">
        <div class="card-body p-0 verticle-align-middle-table">
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
                            <th class="text-center max-90px">{{translate('messages.sl')}}</th>
                            <th>{{translate('messages.food')}}</th>
                            <th class="pl-4">{{translate('messages.reviewer_info')}}</th>
                            <th>{{translate('messages.review')}}</th>
                            <th>{{translate('messages.date')}}</th>
                            <th class="text-center w-100px">{{translate('messages.status')}}</th>
                        </tr>
                    </thead>

                    <tbody id="set-rows">
                    @php($reviews = $restaurant->reviews()->with('food',function($query){
                        $query->withoutGlobalScope(\App\Scopes\RestaurantScope::class);
                    })->latest()->paginate(25))

                    @foreach($reviews as $key=>$review)
                        <tr>
                            <td class="text-center">{{$key+$reviews->firstItem()}}</td>
                            <td>
                            @if ($review->food)
                                <a class="media align-items-center" href="{{route('admin.food.view',[$review->food['id']])}}">
                                    <img class="avatar avatar-lg mr-3" src="{{asset('storage/app/public/product')}}/{{$review->food['image']}}"
                                        onerror="this.src='{{asset('public/assets/admin/img/100x100/food-default-image.png')}}'" alt="{{$review->food->name}} image">
                                    <div class="media-body">
                                        <h5 class="text-hover-primary mb-0">{{Str::limit($review->food['name'],10)}}</h5>
                                        <!-- Static Order ID -->
                                        {{-- <small class="text-body">Order ID: {{$review->order_id}}</small> --}}
                                        <a class="text-body" href="{{route('admin.order.details',['id'=>$review->order_id])}}">Order ID: {{$review->order_id}}</a>
                                        <!-- Static Order ID -->
                                    </div>
                                </a>
                            @else
                                {{translate('messages.Food deleted!')}}
                            @endif
                            </td>
                            <td>
                            @if($review->customer)
                                <a
                                href="{{route('admin.customer.view',[$review['user_id']])}}">
                                    <div>
                                    <span class="d-block h5 text-hover-primary mb-0">{{Str::limit($review->customer['f_name']." ".$review->customer['l_name'], 15)}} <i
                                            class="tio-verified text-primary" data-toggle="tooltip" data-placement="top"
                                            title="Verified Customer"></i></span>
                                        <span class="d-block font-size-sm text-body">{{Str::limit($review->customer->phone)}}</span>
                                    </div>
                                </a>
                                @else
                                    {{translate('messages.customer_not_found')}}
                                @endif
                            </td>
                            <td>
                                <div class="text-wrap w-18rem">
                                    <span class="d-block rating">
                                        {{$review->rating}} <i class="tio-star"></i>
                                    </span>
                                    <small class="d-block">
                                        {{Str::limit($review['comment'], 80)}}
                                    </small>
                                </div>
                            </td>
                            <td>
                                {{ Carbon\Carbon::parse($review['created_at'])->locale(app()->getLocale())->translatedFormat( 'd M Y ' .config('timeformat')) }}
                            </td>
                            <td>
                                <label class="toggle-switch toggle-switch-sm" for="reviewCheckbox{{$review->id}}">
                                    <input type="checkbox" onclick="status_form_alert('status-{{$review['id']}}','{{$review->status?translate('messages.you_want_to_hide_this_review_for_customer'):translate('messages.you_want_to_show_this_review_for_customer')}}', event)" class="toggle-switch-input" id="reviewCheckbox{{$review->id}}" {{$review->status?'checked':''}}>
                                    <span class="toggle-switch-label">
                                        <span class="toggle-switch-indicator"></span>
                                    </span>
                                </label>
                                <form action="{{route('admin.food.reviews.status',[$review['id'],$review->status?0:1])}}" method="get" id="status-{{$review['id']}}">
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="page-area px-4 pb-3">
                    <div class="d-flex align-items-center justify-content-end">
                        <div>
                        {!! $reviews->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script_2')
    <!-- Page level plugins -->
    <script>
        // Call the dataTables jQuery plugin
        $(document).ready(function () {
            $('#dataTable').DataTable();
        });
    </script>
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

            $('#column2_search').on('keyup', function () {
                datatable
                    .columns(2)
                    .search(this.value)
                    .draw();
            });

            $('#column3_search').on('change', function () {
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

        $('#search-form').on('submit', function () {
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('admin.food.search')}}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    $('#set-rows').html(data.view);
                    $('.page-area').hide();
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        });

        function status_form_alert(id, message, e) {
            e.preventDefault();
            Swal.fire({
                title: '{{translate('messages.are_you_sure')}}',
                text: message,
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#FC6A57',
                cancelButtonText: '{{ translate('messages.no') }}',
                confirmButtonText: '{{ translate('messages.yes') }}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $('#'+id).submit()
                }
            })
        }
    </script>
@endpush
