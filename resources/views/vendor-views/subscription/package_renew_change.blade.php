@extends('layouts.vendor.app')

@section('title', translate('messages.Subscription'))
@push('css_or_js')
@endpush


@section('content')
    <div class="content container-fluid">
        <!-- Page Heading -->

        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title">
                        <span class="page-header-icon"></span>
                        {{ translate('Packages') }}
                    </h1>
                </div>
            </div>
        </div>
 @php( $package_id=$subs->package_id ?? 0 )


        <div id="generic_price_table">

            <div class="container">

                <!--BLOCK ROW START-->
                <div class="row">


                    @forelse ($packages as $package)
                        <div class="col-md-4">

                            <!--PRICE CONTENT START-->
                            <div class="generic_content {{ ($package->id == $package_id) ? 'active': '' }}  clearfix">

                                <!--HEAD PRICE DETAIL START-->
                                <div class="generic_head_price clearfix">

                                    <!--HEAD CONTENT START-->
                                    <div class="generic_head_content clearfix">

                                        <!--HEAD START-->
                                        <div class="head_bg"></div>
                                        <div class="head">
                                            <span><h2>
                                                {{ $package->package_name }}
                                            </h2></span>
                                        </div>
                                        <!--//HEAD END-->

                                    </div>
                                    <!--//HEAD CONTENT END-->

                                    <!--PRICE START-->
                                    <div class="generic_price_tag clearfix">
                                        <span class="price">
                                            <span class="sign">{{ \App\CentralLogics\Helpers::format_currency($package->price)}}</span>
                                            {{-- <span class="currency"> {{  }}  </span> --}}
                                            {{-- <span class="cent">.99</span> --}}
                                            <span class="month"> {{ $package->validity }}/Days</span>
                                        </span>
                                    </div>
                                    <!--//PRICE END-->

                                </div>
                                <!--//HEAD PRICE DETAIL END-->

                                <!--FEATURE LIST START-->
                                <div class="generic_feature_list">
                                    <ul>
                                        <li><span>
                                            @if ( $package->max_order == 'unlimited')
                                                {{ translate('Unlimited') }}
                                            @else
                                            {{$package->max_order }}
                                            @endif
                                        </span> {{ translate('messages.Order') }} {{ translate('Places') }} </li>

                                        <li><span>@if ( $package->max_product  == 'unlimited')
                                            {{ translate('Unlimited') }}
                                        @else
                                        {{$package->max_product }}
                                        @endif
                                        </span> {{ translate('messages.product') }} {{ translate('messages.Uploads') }}</li>
                                        @if ($package->pos ==1)
                                        <li><span>POS </span>{{ translate('messages.System') }} </li>
                                        @endif
                                        @if ($package->mobile_app ==1)
                                        <li><span>{{ translate('messages.Mobile App') }}</span>  </li>
                                        @endif
                                        @if ($package->self_delivery ==1)
                                        <li><span>{{ translate('messages.self_delivery') }}</span> {{ translate('messages.option') }} </li>
                                        @endif
                                        @if ($package->chat ==1)
                                        <li><span>{{ translate('messages.chat') }}</span> {{ translate('messages.option') }}  </li>
                                        @endif
                                        @if ($package->review ==1)
                                        <li><span>{{ translate('messages.Review') }}</span> {{ translate('Selection') }} </li>
                                        @endif

                                    </ul>
                                </div>
                                <!--//FEATURE LIST END-->

                                <!--BUTTON START-->
                                <div class="generic_price_btn clearfix">
                                    <a  data-toggle="modal" data-id="{{ $package->id }}" class="package_id"
                                    data-target=".bd-example-modal-sm">
                                    @if ($package->id == $package_id )
                                    {{ translate('Renew Now') }}
                                    @else
                                    {{ translate('Get Started') }}
                                    @endif
                                </a>
                                </div>
                                <!--//BUTTON END-->

                            </div>
                            <!--//PRICE CONTENT END-->

                        </div>













                            {{-- <form action="{{  }}" method="post"
                                id="package-{{ $package->id }}">


                            </form> --}}


                    @empty
                        <div class="col-lg-12">
                            <div class="card mb-3 mb-lg-5">
                                <div class="card-body">
                                    <div class="list-group list-group-flush list-group-no-gutters">
                                        <li class="list-group-item">
                                            <div class="media align-items-center">
                                                <div class="media-body">
                                                    <span class="h5">{{ translate('No Package Found') }}</span>
                                                </div>
                                            </div>
                                        </li>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforelse









                </div>
                <!--//BLOCK ROW END-->

            </div>

        </div>








    </div>


    </div>
    <!-- Modal -->
    <div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h4" id="mySmallModalLabel">{{ translate('Package Subscribtion Confirmation') }}
                        </h5>
                    <button type="button" class="btn btn-xs btn-icon btn-ghost-secondary" data-dismiss="modal"
                        aria-label="Close">
                        <i class="tio-clear tio-lg"></i>
                    </button>
                </div>

                <form action="{{ route('vendor.subscription.package_renew_change_update') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <!-- Input Group -->
                        <div class="form-group">
                            <label for=""> {{ translate('messages.reference') }}
                                {{ translate('messages.code') }} {{ translate('messages.add') }}</label>
                            <input type="text" name="reference" class="form-control"
                                placeholder="{{ translate('messages.Ex :') }} Code123" >
                        </div>
                        @csrf @method('post')
                        {{-- <input type="hidden" name="package_id" value="{{ $package->id }}" > --}}
                        <input type="hidden" class="form-control" name="package_id" id="idkl" value="">
                        <input type="hidden" name="restaurant_id" value="{{ $restaurant_id}}" >
                        <input type="hidden" name="type" value="{{ $package_id  }}" >

                        <div class="form-group">
                            <label for="inputState">{{ translate('messages.Payment Method') }}</label>
                            <select id="inputState" name="payment_method" class="form-control">
                                <option  value="wallet" selected>{{ translate('Restaurant Wallet') }}</option>
                                <option value="others" >  {{ translate('Others') }}</option>
                            </select>
                        </div>

                        <!-- End Input Group -->
                        <button type="submit" class="btn btn-primary">{{ translate('messages.Confirm') }}</button>
                        <button type="button" type="reset" class="btn btn-secondary" data-dismiss="modal">{{ translate('messages.Cancel') }}</button>

                    </div>
                </form>

            </div>
        </div>
    </div>
    <!-- End Modal -->



@endsection
@push('script_2')
<script>

    $(".package_id").click(function () {
        var ids = $(this).attr('data-id');
        $("#idkl").val( ids );
        // $('#myModal').modal('show');
    });
    </script>
@endpush
