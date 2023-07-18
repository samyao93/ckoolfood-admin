@extends('layouts.admin.app')

@section('title',translate('Food Preview'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex flex-wrap justify-content-between align-items-center">
                <h1 class="page-header-title text-break">
                    {{$product['name']}}
                </h1>
                <a href="{{route('admin.food.edit',[$product['id']])}}" class="btn btn--primary">
                    <i class="tio-edit"></i> {{translate('Edit Info')}}
                </a>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row review--information-wrapper g-2 mb-3">
            <div class="col-lg-9">
                <!-- Card -->
                <div class="card h-100">
                    <!-- Body -->
                    <div class="card-body">
                        <div class="row align-items-md-center">
                            <div class="col-lg-5 col-md-6 mb-3 mb-md-0">
                                <div class="d-flex flex-wrap align-items-center food--media">
                                    <img class="avatar avatar-xxl avatar-4by3 mr-4 initial-53"
                                            src="{{asset('storage/app/public/product')}}/{{$product['image']}}"
                                            onerror="this.src='{{asset('/public/assets/admin/img/100x100/food-default-image.png')}}'"
                                            alt="Image Description">
                                    <div class="d-block">
                                            <div class="rating--review">
                                                {{-- {{ dd($product->restaurant) }} --}}
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
                                            class="progress-name mr-3">{{ translate('Excellent') }}</span>
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
                                        <span class="progress-name mr-3">{{ translate('Good') }}</span>
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
                                        <span class="progress-name mr-3">{{ translate('Average') }}</span>
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
                                        <span class="progress-name mr-3">{{ translate('Below Average') }}</span>
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
                                        <span class="progress-name mr-3">{{ translate('Poor') }}</span>
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
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card h-100">

                    <div class="card-body d-flex flex-column justify-content-center">
                    @if($product->restaurant)
                        <a class="resturant--information-single" href="{{route('admin.restaurant.view', $product->restaurant_id)}}" title="{{$product->restaurant['name']}}">
                            <img class="avatar-img initial-54" onerror="this.src='{{asset('public/assets/admin/img/160x160/img1.jpg')}}'"
                            src="{{asset('storage/app/public/restaurant/'.$product->restaurant->logo)}}"
                            alt="Image Description">
                            <div class="text-center">
                                <h5 class="text-capitalize text--title font-semibold text-hover-primary d-block mb-1">
                                    {{$product->restaurant['name']}}
                                </h5>
                                <span class="text--title">
                                    <i class="tio-poi"></i> {{$product->restaurant['address']}}
                                </span>
                            </div>
                        </a>
                    @else
                        <div class="badge badge-soft-danger py-2">{{translate('messages.restaurant')}} {{translate('messages.deleted')}}</div>
                    @endif
                    </div>


                </div>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-borderless table-thead-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th class="px-4 w-140px"><h4 class="m-0">{{ translate('Short Description') }}</h4></th>
                                <th class="px-4 w-120px"><h4 class="m-0">{{translate('messages.price')}}</h4></th>
                                <th class="px-4 w-100px"><h4 class="m-0">{{translate('messages.variations')}}</h4></th>
                                <th class="px-4 w-100px"><h4 class="m-0">{{ translate('Addons') }}</h4></th>
                                <th class="px-4 w-100px"><h4 class="m-0">{{ translate('Tags') }}</h4></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="px-4">
                                    <div>
                                        {!!$product['description'] !!}
                                    </div>
                                </td>
                                <td class="px-4">
                                    <span class="d-block mb-1">
                                        <span>{{ translate('Price') }}</span>
                                        <strong>{{\App\CentralLogics\Helpers::format_currency($product['price'])}}</strong>
                                    </span>
                                    <span class="d-block mb-1">{{translate('messages.discount')}} :
                                        <strong>{{\App\CentralLogics\Helpers::format_currency(\App\CentralLogics\Helpers::discount_calculate($product,$product['price']))}}</strong>
                                    </span>
                                    <span class="d-block mb-1">
                                        {{translate('messages.available')}} {{translate('messages.time')}} {{translate('messages.starts')}} : <strong>{{date(config('timeformat'),strtotime($product['available_time_starts']))}}</strong>
                                    </span>
                                    <span class="d-block">
                                        {{translate('messages.available')}} {{translate('messages.time')}} {{translate('messages.ends')}} : <strong>{{date(config('timeformat'), strtotime($product['available_time_ends']))}}</strong>
                                    </span>
                                </td>
                                <td class="px-4">
                                    {{-- {{ dd(json_decode($product['variations'],true)) }} --}}
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
                                    @foreach(\App\Models\AddOn::withOutGlobalScope(App\Scopes\RestaurantScope::class)->whereIn('id',json_decode($product['add_ons'],true))->get() as $addon)
                                        <span class="d-block text-capitalize">
                                        {{$addon['name']}} : <strong>{{\App\CentralLogics\Helpers::format_currency($addon['price'])}}</strong>
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
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- <div class="col-12">
                @if(isset($product->variations))
                    @foreach(json_decode($product->variations,true) as $variation)

                            <div class="col-12 mr-2">
                                <span class="ml-2"
                                style="font-size: 12px;">{{$variation['name']}}:
                                    @if ($variation['type']=='single')
                                        <span>{{translate('input_filed')}}</span>
                                    @elseif($variation['type']=='multi')
                                        <span>{{translate('textarea_filed')}}</span>

                                    @else

                                    @endif
                                </span>
                            </div>

                    @endforeach
                @endif
            </div> --}}
            <!-- End Body -->
        </div>
        <!-- End Card -->

        <!-- Card -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">{{translate('messages.product')}} {{translate('messages.reviews')}}</h5>
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
                        <th>{{translate('messages.reviewer')}}</th>
                        <th>{{translate('messages.review')}}</th>
                        <th>{{translate('messages.date')}}</th>
                        <th>{{translate('messages.status')}}</th>
                    </tr>
                    </thead>

                    <tbody>

                    @foreach($reviews as $review)
                        <tr>
                            <td>
                                @if ($review->customer)
                                    <a class="d-flex align-items-center"
                                    href="{{route('admin.customer.view',[$review['user_id']])}}">
                                        <div class="avatar avatar-circle">
                                            <img class="avatar-img" width="75" height="75"
                                                onerror="this.src='{{asset('public/assets/admin/img/160x160/img1.jpg')}}'"
                                                src="{{asset('storage/app/public/profile/'.$review->customer->image)}}"
                                                alt="Image Description">
                                        </div>
                                        <div class="ml-3">
                                        <span class="d-block h5 text-hover-primary mb-0">{{$review->customer['f_name']." ".$review->customer['l_name']}} <i
                                                class="tio-verified text-primary" data-toggle="tooltip" data-placement="top"
                                                title="Verified Customer"></i></span>
                                            <span class="d-block font-size-sm text-body">{{$review->customer->email}}</span>
                                        </div>
                                    </a>
                                @else
                                {{translate('messages.customer_not_found')}}
                                @endif
                            </td>
                            <td>
                                <div class="text-wrap mw-400">
                                    <label class="m-0 rating">
                                        {{$review->rating}} <i class="tio-star"></i>
                                    </label>

                                    <p class="line--limit-1">
                                        {{$review['comment']}}
                                    </p>
                                </div>
                            </td>
                            <td>
                                {{date('d M Y '.config('timeformat'),strtotime($review['created_at']))}}
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
            </div>
            @if(count($reviews) === 0)
            <div class="empty--data">
                <img src="{{asset('/public/assets/admin/img/empty.png')}}" alt="public">
                <h5>
                    {{translate('no_data_found')}}
                </h5>
            </div>
            @endif
            <!-- End Table -->

            <!-- Pagination -->
            <div class="page-area px-4 pb-3">
                <div class="d-flex align-items-center justify-content-end">
                                        {{-- <div>
                        1-15 of 380
                    </div> --}}
                    <div>
                        {!! $reviews->links() !!}
                    </div>
                </div>
            </div>
            <!-- End Pagination -->
        </div>
        <!-- End Card -->
    </div>
@endsection

@push('script_2')
<script>
    function status_form_alert(id, message, e) {
        e.preventDefault();
        Swal.fire({
            title: '{{translate('messages.are_you_sure')}}',
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
                $('#'+id).submit()
            }
        })
    }
</script>
@endpush
