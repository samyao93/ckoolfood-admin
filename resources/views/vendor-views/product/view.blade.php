@extends('layouts.vendor.app')

@section('title',translate('Food Preview'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex flex-wrap justify-content-between align-items-center">
                <h1 class="page-header-title text-break">{{$product['name']}}</h1>
                <a href="{{route('vendor.food.edit',[$product['id']])}}" class="btn btn--primary">
                    <i class="tio-edit"></i> {{translate('messages.edit')}} {{ translate('Info') }}
                </a>
            </div>
        </div>
        <!-- End Page Header -->

        <!-- Card -->
        <div class="card mb-3">
            <!-- Body -->
            <div class="card-body">
                <div class="row align-items-md-center">
                    <div class="col-lg-5 col-md-6 mb-3 mb-md-0">
                        <div class="d-flex flex-wrap align-items-center food--media">
                            <img class="avatar avatar-xxl avatar-4by3 mr-4 initial-90"
                                 src="{{asset('storage/app/public/product')}}/{{$product['image']}}"
                                 onerror="this.src='{{asset('public/assets/admin/img/160x160/img2.jpg')}}'"
                                 alt="Image Description">
                            <div class="d-block">
                                <div class="rating--review">

                                    {{--<h4 class="title">{{round($product->avg_rating,1)}}</h4>

                                    <p> {{translate('messages.of')}} {{$product->reviews->count()}} {{translate('messages.reviews')}}
                                        <span class="badge badge-soft-dark badge-pill ml-1"></span>
                                    </p>

                                    --}}

                                    <h1 class="title">{{ number_format($product->avg_rating, 1)}}<span class="out-of">/5</span></h1>
                                    @if ($product->avg_rating == 5)
                                            <div class="rating">
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star"></i></span>
                                            </div>
                                            @elseif ($product->avg_rating < 5 && $product->avg_rating >= 4.5)
                                            <div class="rating">
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star-half"></i></span>
                                            </div>
                                            @elseif ($product->avg_rating < 4.5 && $product->avg_rating >= 4)
                                            <div class="rating">
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                            </div>
                                            @elseif ($product->avg_rating < 4 && $product->avg_rating >= 3.5)
                                            <div class="rating">
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star-half"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                            </div>
                                            @elseif ($product->avg_rating < 3.5 && $product->avg_rating >= 3)
                                            <div class="rating">
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                            </div>
                                            @elseif ($product->avg_rating < 3 && $product->avg_rating >= 2.5)
                                            <div class="rating">
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star-half"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                            </div>
                                            @elseif ($product->avg_rating < 2.5 && $product->avg_rating > 2)
                                            <div class="rating">
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                            </div>
                                            @elseif ($product->avg_rating < 2 && $product->avg_rating >= 1.5)
                                            <div class="rating">
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star-half"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                            </div>
                                            @elseif ($product->avg_rating < 1.5 && $product->avg_rating > 1)
                                            <div class="rating">
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                            </div>
                                            @elseif ($product->avg_rating < 1 && $product->avg_rating > 0)
                                            <div class="rating">
                                                <span><i class="tio-star-half"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                            </div>
                                            @elseif ($product->avg_rating == 1)
                                            <div class="rating">
                                                <span><i class="tio-star"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                            </div>
                                            @elseif ($product->avg_rating == 0)
                                            <div class="rating">
                                                <span><i class="tio-star-outlined"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                                <span><i class="tio-star-outlined"></i></span>
                                            </div>
                                            @endif
                                    <div class="info">
                                        {{-- <span class="mr-3">of {{ $product->rating ? count(json_decode($product->rating, true)): 0 }} Rating</span> --}}
                                        <span>{{translate('messages.of')}} {{$product->reviews->count()}} {{translate('messages.reviews')}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-7 col-md-6 mx-auto">
                        <ul class="list-unstyled list-unstyled-py-2 mb-0 rating--review-right py-3">

                        @php($total=$product->rating?array_sum(json_decode($product->rating, true)):0)
                        <!-- Review Ratings -->
                            <li class="d-flex align-items-center font-size-sm">
                                @php($five=$product->rating?json_decode($product->rating, true)[5]:0)
                                <span
                                    class="progress-name mr-3">5 {{translate('messages.star')}}</span>
                                <div class="progress flex-grow-1">
                                    <div class="progress-bar" role="progressbar"
                                        style="width: {{$total==0?0:($five/$total)*100}}%;"
                                        aria-valuenow="{{$total==0?0:($five/$total)*100}}"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="ml-3">{{$five}}</span>
                            </li>
                            <!-- End Review Ratings -->

                            <!-- Review Ratings -->
                            <li class="d-flex align-items-center font-size-sm">
                                @php($four=$product->rating?json_decode($product->rating, true)[4]:0)
                                <span class="progress-name mr-3">4 {{translate('messages.star')}}</span>
                                <div class="progress flex-grow-1">
                                    <div class="progress-bar" role="progressbar"
                                        style="width: {{$total==0?0:($four/$total)*100}}%;"
                                        aria-valuenow="{{$total==0?0:($four/$total)*100}}"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="ml-3">{{$four}}</span>
                            </li>
                            <!-- End Review Ratings -->

                            <!-- Review Ratings -->
                            <li class="d-flex align-items-center font-size-sm">
                                @php($three=$product->rating?json_decode($product->rating, true)[3]:0)
                                <span class="progress-name mr-3">3 {{translate('messages.star')}}</span>
                                <div class="progress flex-grow-1">
                                    <div class="progress-bar" role="progressbar"
                                        style="width: {{$total==0?0:($three/$total)*100}}%;"
                                        aria-valuenow="{{$total==0?0:($three/$total)*100}}"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="ml-3">{{$three}}</span>
                            </li>
                            <!-- End Review Ratings -->

                            <!-- Review Ratings -->
                            <li class="d-flex align-items-center font-size-sm">
                                @php($two=$product->rating?json_decode($product->rating, true)[2]:0)
                                <span class="progress-name mr-3">2 {{translate('messages.star')}}</span>
                                <div class="progress flex-grow-1">
                                    <div class="progress-bar" role="progressbar"
                                        style="width: {{$total==0?0:($two/$total)*100}}%;"
                                        aria-valuenow="{{$total==0?0:($two/$total)*100}}"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="ml-3">{{$two}}</span>
                            </li>
                            <!-- End Review Ratings -->

                            <!-- Review Ratings -->
                            <li class="d-flex align-items-center font-size-sm">
                                @php($one=$product->rating?json_decode($product->rating, true)[1]:0)
                                <span class="progress-name mr-3">1 {{translate('messages.star')}}</span>
                                <div class="progress flex-grow-1">
                                    <div class="progress-bar" role="progressbar"
                                        style="width: {{$total==0?0:($one/$total)*100}}%;"
                                        aria-valuenow="{{$total==0?0:($one/$total)*100}}"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="ml-3">{{$one}}</span>
                            </li>
                            <!-- End Review Ratings -->
                        </ul>
                    </div>
                </div>
            </div>
            <!-- End Body -->
        </div>
        <!-- End Card -->
        <div class="card mb-3">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless table-thead-bordered table-align-middle">
                        <thead class="thead-light">
                            <tr>
                                <th class="px-4 w-140px"><h4 class="m-0">{{ translate('Short Description') }}</h4></th>
                                <th class="px-4 w-120px"><h4 class="m-0">{{translate('messages.price')}}</h4></th>
                                <th class="px-4 w-100px"><h4 class="m-0">{{translate('messages.variations')}}</h4></th>
                                <th class="px-4 w-100px"><h4 class="m-0">{{ translate('messages.addons') }}</h4></th>
                                <th class="px-4 w-100px"><h4 class="m-0">{{ translate('Tags') }}</h4></th>

                            </tr>
                            <tbody>
                                <td class="px-4">
                                    <p class="max-315px">{{$product['description']}}</p>
                                </td>
                                <td class="px-4">
                                    <span class="d-block mb-1">
                                        <span>
                                            {{translate('messages.price')}} :
                                        </span>
                                        <strong>
                                            {{\App\CentralLogics\Helpers::format_currency($product['price'])}}
                                        </strong>
                                    </span>

                                    <span class="d-block mb-1">
                                        <span>
                                            {{translate('messages.discount')}} :
                                        </span>
                                        <strong>
                                            {{\App\CentralLogics\Helpers::format_currency(\App\CentralLogics\Helpers::discount_calculate($product,$product['price']))}}
                                        </strong>
                                    </span>
                                    <span class="d-block mb-1">
                                        <span>
                                            {{translate('messages.available')}} {{translate('messages.time')}} {{translate('messages.starts')}} :
                                        </span>
                                        <strong>
                                            {{date(config('timeformat'), strtotime($product['available_time_starts']))}}
                                        </strong>
                                    </span>
                                    <span class="d-block">
                                        <span>
                                            {{translate('messages.available')}} {{translate('messages.time')}} {{translate('messages.ends')}} :
                                        </span>
                                        <strong>
                                            {{date(config('timeformat'), strtotime($product['available_time_ends']))}}
                                        </strong>
                                    </span>
                                </td>
                                <td class="px-4">
                                    {{-- @foreach(json_decode($product['variations'],true) as $variation)
                                        <span class="d-block mb-1 text-capitalize">
                                        <span>
                                            {{$variation['type']}} :
                                        </span>
                                        <strong>
                                            {{\App\CentralLogics\Helpers::format_currency($variation['price'])}}
                                        </strong>
                                        </span>
                                    @endforeach --}}


                                    @foreach(json_decode($product->variations,true) as $variation)
                                    @if(isset($variation["price"]))
                                    <span class="d-block mb-1 text-capitalize">
                                        <strong>
                                            {{ translate('please_update_the_food_variations.') }}
                                        </strong>
                                        </span>
                                        @break
                                        @else
                                        <span class="d-block text-capitalize">
                                                <strong>
                                        {{$variation['name']}} -
                                    </strong>
                                        @if ($variation['type'] == 'multi')
                                        {{ translate('messages.multiple_select') }}
                                        @elseif($variation['type'] =='single')
                                        {{ translate('messages.single_select') }}

                                        @endif
                                        @if ($variation['required'] == 'on')
                                        - ({{ translate('messages.required') }})
                                        @endif
                                        </span>

                                        @if ($variation['min'] != 0 && $variation['max'] != 0)
                                       ({{ translate('messages.Min_select') }}: {{ $variation['min'] }} - {{ translate('messages.Max_select') }}: {{ $variation['max'] }})
                                        @endif

                                            @if (isset($variation['values']))
                                            @foreach ($variation['values'] as $value)
                                            <span class="d-block text-capitalize">
                                                &nbsp;   &nbsp; {{ $value['label']}} :
                                                <strong>{{\App\CentralLogics\Helpers::format_currency( $value['optionPrice'])}}</strong>
                                                </span>
                                            @endforeach
                                        @endif
                                    @endif
                                @endforeach
                                </td>
                                <td class="px-4">
                                    @foreach(\App\Models\AddOn::whereIn('id',json_decode($product['add_ons'],true))->get() as $addon)
                                        <span class="d-block mb-1 text-capitalize">
                                            <span>
                                                {{$addon['name']}} :
                                            </span>
                                            <strong>
                                                {{\App\CentralLogics\Helpers::format_currency($addon['price'])}}
                                            </strong>
                                        </span>
                                    @endforeach
                                </td>
                                <td class="px-4">
                                    @forelse($product->tags as $c)
                                        {{$c->tag.','}}
                                        @empty
                                        {{ translate('No_tags_found') }}
                                    @endforelse
                                </td>
                            </tbody>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        @php($restaurant=\App\CentralLogics\Helpers::get_restaurant_data())
        @if ($restaurant->restaurant_model == 'commission' && $restaurant->reviews_section || ($restaurant->restaurant_model == 'subscription' && isset($restaurant->restaurant_sub) && $restaurant->restaurant_sub->review))
        <!-- Card -->
        <div class="card">
            <div class="card-header py-2 border-0">
            <div class="search--button-wrapper">
                <h5 class="card-title">{{ translate('Reviewer Table List') }} <span class="badge badge-soft-dark ml-2" id="itemCount">{{ count($reviews) }}</span></h5>
                {{-- <form action="javascript:" id="search-form">
                    <div class="input--group input-group">
                        <input type="search" name="search" class="form-control" placeholder="{{ translate('messages.Ex :') }} Search by Reviewer Name">
                        <button type="submit" class="btn btn--secondary">
                            <i class="tio-search"></i>
                        </button>
                    </div>
                    <!-- End Search -->
                </form> --}}
            </div>
        </div>
            <!-- Table -->
            <div class="table-responsive datatable-custom">
                <table id="datatable" class="table table-borderless table-thead-bordered table-nowrap card-table"
                       data-hs-datatables-options='{
                     "columnDefs": [{
                        "targets": [0, 3, 6],
                        "orderable": false
                      }],
                     "order": [],
                     "info": {
                       "totalQty": "#datatableWithPaginationInfoTotalQty"
                     },
                     "search": "#datatableSearch",
                     "entries": "#datatableEntries",
                     "pageLength": 25,
                     "isResponsive": false,
                     "isShowPaging": false,
                     "pagination": "datatablePagination"
                   }'>
                    <thead class="thead-light">
                    <tr>
                        <th class="w-80px text-center">{{ translate('messages.sl') }}</th>
                        <th>{{translate('messages.reviewer')}}</th>
                        <th>{{translate('messages.review')}}</th>
                        <th>{{translate('messages.date')}}</th>
                    </tr>
                    </thead>

                    <tbody>

                    @foreach($reviews as $key=>$review)
                        <tr>
                            <td class="text-center">
                                {{ $key + $reviews->firstItem() }}
                            </td>
                            <td>
                                @if ($review->customer)
                                    <div class="d-flex align-items-center">
                                        <div class="avatar rounded">
                                            <img class="avatar-img" width="75" height="75"
                                                onerror="this.src='{{asset('public/assets/admin/img/160x160/img1.jpg')}}'"
                                                src="{{asset('storage/app/public/profile/'.$review->customer->image)}}"
                                                alt="Image Description">
                                        </div>
                                        <div class="ml-3">
                                        <span class="d-block h5 mb-0">{{$review->customer['f_name']." ".$review->customer['l_name']}} <i
                                                class="tio-verified text-primary" data-toggle="tooltip" data-placement="top"
                                                title="Verified Customer"></i></span>
                                            <span class="d-block font-size-sm text-body">{{$review->customer->email}}</span>
                                        </div>
                                    </div>
                                @else
                                    {{translate('messages.customer_not_found')}}
                                @endif
                            </td>
                            <td>
                                <div class="text-wrap w-18rem">
                                    <label class="rating">
                                        {{$review->rating}} <i class="tio-star"></i>
                                    </label>

                                    <p>
                                        {{$review['comment']}}
                                    </p>
                                </div>
                            </td>
                            <td>
                                <strong class="d-block font-semibold">
                                    {{date('d M Y ',strtotime($review['created_at']))}}
                                </strong>
                                <strong class="d-block font-semibold">
                                    {{date(config('timeformat'),strtotime($review['created_at']))}}
                                </strong>
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
            </div>
            <!-- End Table -->

            <!-- Footer -->
            <div class="card-footer">
                <!-- Pagination -->
                <div class="row justify-content-center justify-content-sm-between align-items-sm-center">
                    <div class="col-12">
                        {!! $reviews->links() !!}
                    </div>
                </div>
                <!-- End Pagination -->
            </div>
            <!-- End Footer -->
        </div>
        <!-- End Card -->
        @endif
    </div>
@endsection

@push('script_2')

@endpush
