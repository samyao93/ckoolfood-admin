<div id="sidebarMain" class="d-none">
    <aside
        class="js-navbar-vertical-aside navbar navbar-vertical-aside navbar-vertical navbar-vertical-fixed navbar-expand-xl navbar-bordered">
        <div class="navbar-vertical-container">
            <div class="navbar-brand-wrapper justify-content-between">
                <!-- Logo -->
                <div class="sidebar-logo-container">
                    @php($restaurant_data=\App\CentralLogics\Helpers::get_restaurant_data())
                    <a class="navbar-brand pt-0 pb-0" href="{{route('vendor.dashboard')}}" aria-label="Front">
                        <img class="navbar-brand-logo"
                            onerror="this.src='{{asset('public/assets/admin/img/160x160/img2.jpg')}}'"
                            src="{{asset('storage/app/public/restaurant/'.$restaurant_data->logo)}}"
                            alt="Logo">
                        <img class="navbar-brand-logo-mini"
                            onerror="this.src='{{asset('public/assets/admin/img/160x160/img2.jpg')}}'"
                            src="{{asset('storage/app/public/restaurant/'.$restaurant_data->logo)}}" alt="Logo">
                        <div class="ps-2">
                            <h6>
                                {{\Illuminate\Support\Str::limit($restaurant_data->name,15)}}
                            </h6>
                        </div>
                    </a>
                    <!-- End Logo -->

                    <!-- Navbar Vertical Toggle -->
                    <button type="button"
                            class="js-navbar-vertical-aside-toggle-invoker navbar-vertical-aside-toggle btn btn-icon btn-xs btn-ghost-dark">
                        <i class="tio-clear tio-lg"></i>
                    </button>
                    <!-- End Navbar Vertical Toggle -->
                </div>
                <div class="navbar-nav-wrap-content-left ml-auto d-none d-xl-block">
                    <!-- Navbar Vertical Toggle -->
                    <button type="button" class="js-navbar-vertical-aside-toggle-invoker close">
                        <i class="tio-first-page navbar-vertical-aside-toggle-short-align" data-toggle="tooltip"
                        data-placement="right" title="Collapse"></i>
                        <i class="tio-last-page navbar-vertical-aside-toggle-full-align"
                        data-template='<div class="tooltip d-none d-sm-block" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'></i>
                    </button>
                    <!-- End Navbar Vertical Toggle -->
                </div>

            </div>

            <!-- Content -->
            <div class="navbar-vertical-content text-capitalize bg-334257">
                <ul class="navbar-nav navbar-nav-lg nav-tabs">
                    <!-- Dashboards -->
                    <li class="pt-4"></li>
                    <li class="navbar-vertical-aside-has-menu {{Request::is('restaurant-panel')?'active':''}}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link"
                            href="{{route('vendor.dashboard')}}" title="{{translate('messages.dashboard')}}">
                            <i class="tio-home-vs-1-outlined nav-icon"></i>
                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                {{translate('messages.dashboard')}}
                            </span>
                        </a>
                    </li>
                    <!-- End Dashboards -->

                    @if(\App\CentralLogics\Helpers::employee_module_permission_check('pos'))
                    <!-- POS -->
                    <li class="navbar-vertical-aside-has-menu {{Request::is('restaurant-panel/pos')?'active':''}}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link" href="{{route('vendor.pos.index')}}" title="{{translate('POS')}}"
                        >
                            <i class="tio-shopping nav-icon"></i>
                            <span
                                class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('messages.pos')}}</span>
                        </a>
                    </li>
                    <!-- End POS -->
                    @endif

                    <li class="nav-item">
                        <small
                            class="nav-subtitle">{{translate('Promotions')}}</small>
                        <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                    </li>
                    <!-- Campaign -->
                    @if(\App\CentralLogics\Helpers::employee_module_permission_check('campaign'))
                    <li class="navbar-vertical-aside-has-menu {{Request::is('restaurant-panel/campaign*')?'active':''}}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:" title="{{translate('Campaign')}}">
                            <i class="tio-image nav-icon"></i>
                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('messages.campaign')}}</span>
                        </a>
                        <ul class="js-navbar-vertical-aside-submenu nav nav-sub {{Request::is('restaurant-panel/campaign*')?'d-block':'initial-hidden'}}">
                            <li class="nav-item {{Request::is('restaurant-panel/campaign/list')?'active':''}}">
                                <a class="nav-link " href="{{route('vendor.campaign.list')}}" title="{{translate('messages.basic_campaign')}}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate">{{translate('messages.basic_campaign')}}</span>
                                </a>
                            </li>
                            <li class="nav-item {{Request::is('restaurant-panel/campaign/item/list')?'active':''}}">
                                <a class="nav-link " href="{{route('vendor.campaign.itemlist')}}" title="{{translate('messages.food_campaign')}}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate">{{translate('food_campaign')}}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif
                    <!-- End Campaign -->


                <!-- Coupon -->
                @if (\App\CentralLogics\Helpers::employee_module_permission_check('coupon'))
                <li class="navbar-vertical-aside-has-menu {{ Request::is('restaurant-panel/coupon*') ? 'active' : '' }}">
                <a class="js-navbar-vertical-aside-menu-link nav-link"
                    href="{{ route('vendor.coupon.add-new') }}"
                    title="{{ translate('messages.coupons') }}">
                    <i class="tio-ticket nav-icon"></i>
                    <span
                        class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate('messages.coupons') }}</span>
                </a>
                </li>
                @endif
                <!-- End Coupon -->


                    @if(\App\CentralLogics\Helpers::employee_module_permission_check('order'))
                    <li class="nav-item">
                        <small class="nav-subtitle" title="{{translate('messages.order')}} {{translate('messages.section')}}">{{translate('messages.order')}} {{translate('messages.management')}}</small>
                        <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                    </li>

                    <!-- Order -->
                    <li class="navbar-vertical-aside-has-menu {{Request::is('restaurant-panel/order*') && (Request::is('restaurant-panel/order/subscription*') == false ) ?'active':''}}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:"
                            title="{{translate('messages.orders')}}">
                            <i class="tio-shopping-cart nav-icon"></i>
                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                {{translate('messages.orders')}}
                            </span>
                        </a>


                        @php($data =0)
                        @php($restaurant =\App\CentralLogics\Helpers::get_restaurant_data())
                        @if (($restaurant->restaurant_model == 'subscription' && isset($restaurant->restaurant_sub) && $restaurant->restaurant_sub->self_delivery == 1)  || ($restaurant->restaurant_model == 'commission' && $restaurant->self_delivery_system == 1) )
                        @php($data =1)
                        @endif

                        <ul class="js-navbar-vertical-aside-submenu nav nav-sub {{Request::is('restaurant-panel/order*') && (Request::is('restaurant-panel/order/subscription*') == false )?'d-block':'initial-hidden'}}">
                            <li class="nav-item {{Request::is('restaurant-panel/order/list/all')?'active':''}} @yield('all_order') ">
                                <a class="nav-link" href="{{route('vendor.order.list',['all'])}}" title="{{translate('messages.all')}} {{translate('messages.order')}}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate sidebar--badge-container">
                                        {{translate('messages.all')}}
                                        <span class="badge badge-soft-info badge-pill ml-1">
                                            {{\App\Models\Order::where('restaurant_id', \App\CentralLogics\Helpers::get_restaurant_id())
                                                ->where(function($query) use($data){
                                                    return $query->whereNotIn('order_status',(config('order_confirmation_model') == 'restaurant'|| $data)?['failed','canceled', 'refund_requested', 'refunded']:['pending','failed','canceled', 'refund_requested', 'refunded'])
                                                    ->orWhere(function($query){
                                                        return $query->where('order_status','pending')->where('order_type', 'take_away');
                                                    });
                                            })->Notpos()->HasSubscriptionToday()
                                            ->count()}}
                                        </span>
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item {{Request::is('restaurant-panel/order/list/pending')?'active':'' }} @yield('pending')">
                                <a class="nav-link " href="{{route('vendor.order.list',['pending'])}}" title="{{translate('messages.pending')}}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate sidebar--badge-container">
                                        {{translate('messages.pending')}} {{(config('order_confirmation_model') == 'restaurant' || $data)?'':translate('messages.take_away')}}
                                            <span class="badge badge-soft-success badge-pill ml-1">
                                            @if(config('order_confirmation_model') == 'restaurant' || $data)
                                            {{\App\Models\Order::where(['order_status'=>'pending','restaurant_id'=>\App\CentralLogics\Helpers::get_restaurant_id()])->Notpos()->HasSubscriptionToday()->OrderScheduledIn(30)->count()}}
                                            @else
                                            {{\App\Models\Order::where(['order_status'=>'pending','restaurant_id'=>\App\CentralLogics\Helpers::get_restaurant_id(), 'order_type'=>'take_away'])->Notpos()->HasSubscriptionToday()->OrderScheduledIn(30)->count()}}
                                            @endif
                                        </span>
                                    </span>
                                </a>
                            </li>

                            <li class="nav-item {{Request::is('restaurant-panel/order/list/confirmed')?'active':''}} @yield('confirmed') ">
                                <a class="nav-link " href="{{route('vendor.order.list',['confirmed'])}}" title="{{translate('messages.confirmed')}}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate sidebar--badge-container">
                                        {{translate('messages.confirmed')}}
                                            <span class="badge badge-soft-success badge-pill ml-1">
                                            {{\App\Models\Order::whereIn('order_status',['confirmed', 'accepted'])->Notpos()->whereNotNull('confirmed')->where('restaurant_id', \App\CentralLogics\Helpers::get_restaurant_id())->HasSubscriptionToday()->OrderScheduledIn(30)->count()}}
                                        </span>
                                    </span>
                                </a>
                            </li>

                            <li class="nav-item {{Request::is('restaurant-panel/order/list/cooking')?'active':''}} @yield('processing')">
                                <a class="nav-link" href="{{route('vendor.order.list',['cooking'])}}" title="{{translate('messages.cooking')}}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate sidebar--badge-container">
                                        {{translate('messages.cooking')}}
                                        <span class="badge badge-soft-info badge-pill ml-1">
                                            {{\App\Models\Order::where(['order_status'=>'processing', 'restaurant_id'=>\App\CentralLogics\Helpers::get_restaurant_id()])->HasSubscriptionToday()->Notpos()->count()}}
                                        </span>
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item {{Request::is('restaurant-panel/order/list/ready_for_delivery')?'active':''}} @yield('handover')">
                                <a class="nav-link" href="{{route('vendor.order.list',['ready_for_delivery'])}}" title="{{translate('Ready For Delivery')}}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate sidebar--badge-container">
                                        {{translate('messages.ready_for_delivery')}}
                                        <span class="badge badge-soft-info badge-pill ml-1">
                                            {{\App\Models\Order::where(['order_status'=>'handover', 'restaurant_id'=>\App\CentralLogics\Helpers::get_restaurant_id()])->HasSubscriptionToday()->Notpos()->count()}}
                                        </span>
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item {{Request::is('restaurant-panel/order/list/food_on_the_way')?'active':''}} @yield('picked_up')">
                                <a class="nav-link" href="{{route('vendor.order.list',['food_on_the_way'])}}" title="{{translate('Food On The Way')}}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate sidebar--badge-container">
                                        {{translate('messages.food_on_the_way')}}
                                        <span class="badge badge-soft-info badge-pill ml-1">
                                            {{\App\Models\Order::where(['order_status'=>'picked_up', 'restaurant_id'=>\App\CentralLogics\Helpers::get_restaurant_id()])->HasSubscriptionToday()->Notpos()->count()}}
                                        </span>
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item {{Request::is('restaurant-panel/order/list/delivered')?'active':''}} @yield('delivered')">
                                <a class="nav-link " href="{{route('vendor.order.list',['delivered'])}}"  title="{{translate('Delivered')}}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate sidebar--badge-container">
                                        {{translate('messages.delivered')}}
                                            <span class="badge badge-soft-success badge-pill ml-1">
                                            {{\App\Models\Order::where(['order_status'=>'delivered','restaurant_id'=>\App\CentralLogics\Helpers::get_restaurant_id()])->HasSubscriptionToday()->Notpos()->count()}}
                                        </span>
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item {{Request::is('restaurant-panel/order/list/refunded')?'active':''}} @yield('refunded')">
                                <a class="nav-link " href="{{route('vendor.order.list',['refunded'])}}"  title="{{translate('Refunded')}}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate sidebar--badge-container">
                                        {{translate('messages.refunded')}}
                                            <span class="badge badge-soft-danger bg-light badge-pill ml-1">
                                            {{\App\Models\Order::Refunded()->where(['restaurant_id'=>\App\CentralLogics\Helpers::get_restaurant_id()])->HasSubscriptionToday()->Notpos()->count()}}
                                        </span>
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item {{Request::is('restaurant-panel/order/list/refund_requested')?'active':''}} @yield('refund_requested')">
                                <a class="nav-link " href="{{route('vendor.order.list',['refund_requested'])}}"  title="{{translate('refund_requested')}}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate sidebar--badge-container">
                                        {{translate('messages.refund_requested')}}
                                            <span class="badge badge-soft-danger bg-light badge-pill ml-1">
                                            {{\App\Models\Order::Refund_requested()->where(['restaurant_id'=>\App\CentralLogics\Helpers::get_restaurant_id()])->Notpos()->count()}}
                                        </span>
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item {{Request::is('restaurant-panel/order/list/scheduled')?'active':''}} @yield('scheduled')">
                                <a class="nav-link" href="{{route('vendor.order.list',['scheduled'])}}" title="{{translate('messages.scheduled')}}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate sidebar--badge-container">
                                        {{translate('messages.scheduled')}}
                                        <span class="badge badge-soft-info badge-pill ml-1">
                                            {{\App\Models\Order::where('restaurant_id',\App\CentralLogics\Helpers::get_restaurant_id())->Notpos()->HasSubscriptionToday()->Scheduled()->where(function($q) use($data){
                                                if(config('order_confirmation_model') == 'restaurant' || $data)
                                                {
                                                    $q->whereNotIn('order_status',['failed','canceled', 'refund_requested', 'refunded']);
                                                }
                                                else
                                                {
                                                    $q->whereNotIn('order_status',['pending','failed','canceled', 'refund_requested', 'refunded'])->orWhere(function($query){
                                                        $query->where('order_status','pending')->where('order_type', 'take_away');
                                                    });
                                                }

                                            })->count()}}
                                        </span>
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    {{-- @if ($restaurant->order_subscription_active == 1 || (\App\Models\Order::where('restaurant_id', \App\CentralLogics\Helpers::get_restaurant_id())->whereNotNull('subscription_id')->count() > 0 )) --}}
                    <li class="navbar-vertical-aside-has-menu {{ Request::is('restaurant-panel/order/subscription*') ? 'active' : '' }}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link" href="{{ route('vendor.order.subscription.index') }}" title="{{ translate('messages.order') }} {{ translate('messages.subscriptiona') }}">
                            <i class="tio-appointment nav-icon"></i>
                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                {{ translate('messages.order') }}  {{ translate('messages.subscription') }} </span>
                        </a>
                    </li>
                    {{-- @endif --}}

                    <!-- End Order -->
                    @endif
                    <li class="nav-item">
                        <small
                            class="nav-subtitle">{{translate('messages.food')}} {{translate('messages.management')}}</small>
                        <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                    </li>
                    <!-- End AddOn -->
                    @if(\App\CentralLogics\Helpers::employee_module_permission_check('food'))
                    <li class="navbar-vertical-aside-has-menu {{Request::is('restaurant-panel/category*')?'active':''}}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                            href="javascript:" title="{{translate('messages.categories')}}"
                        >
                            <i class="tio-category nav-icon"></i>
                            <span
                                class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('messages.categories')}}</span>
                        </a>
                        <ul class="js-navbar-vertical-aside-submenu nav nav-sub {{Request::is('restaurant-panel/category*')?'d-block':'initial-hidden'}}">
                            <li class="nav-item {{Request::is('restaurant-panel/category/list')?'active':''}}">
                                <a class="nav-link " href="{{route('vendor.category.add')}}"
                                    title="{{translate('messages.category')}}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate">{{translate('messages.category')}}</span>
                                </a>
                            </li>

                            <li class="nav-item {{Request::is('restaurant-panel/category/sub-category-list')?'active':''}}">
                                <a class="nav-link " href="{{route('vendor.category.add-sub-category')}}"
                                    title="{{translate('messages.sub_category')}}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate">{{translate('messages.sub_category')}}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!-- Food -->
                    <li class="navbar-vertical-aside-has-menu {{Request::is('restaurant-panel/food*')?'active':''}}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:" title="{{translate('Food')}}"
                        >
                            <i class="tio-premium-outlined nav-icon"></i>
                            <span
                                class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('messages.foods')}}</span>
                        </a>
                        <ul class="js-navbar-vertical-aside-submenu nav nav-sub {{Request::is('restaurant-panel/food*')?'d-block':'initial-hidden'}}">
                            <li class="nav-item {{Request::is('restaurant-panel/food/add-new')?'active':''}}">
                                <a class="nav-link " href="{{route('vendor.food.add-new')}}"
                                     title="{{translate('Add New Food')}}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span
                                        class="text-truncate">{{translate('messages.add')}} {{translate('messages.new')}}</span>
                                </a>
                            </li>
                            <li class="nav-item {{Request::is('restaurant-panel/food/list')?'active':''}}">
                                <a class="nav-link " href="{{route('vendor.food.list')}}"  title="{{translate('Food List')}}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate">{{translate('messages.list')}}</span>
                                </a>
                            </li>
                            @if(\App\CentralLogics\Helpers::get_restaurant_data()->food_section)
                            <li class="nav-item {{Request::is('restaurant-panel/food/bulk-import')?'active':''}}">
                                <a class="nav-link " href="{{route('vendor.food.bulk-import')}}"
                                     title="{{translate('Bulk Import')}}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate text-capitalize">{{translate('messages.bulk_import')}}</span>
                                </a>
                            </li>
                            <li class="nav-item {{Request::is('restaurant-panel/food/bulk-export')?'active':''}}">
                                <a class="nav-link " href="{{route('vendor.food.bulk-export-index')}}"
                                     title="{{translate('Bulk Export')}}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate text-capitalize">{{translate('messages.bulk_export')}}</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    <!-- End Food -->
                    @endif
                    <!-- AddOn -->
                    @if(\App\CentralLogics\Helpers::employee_module_permission_check('addon'))
                    <li class="navbar-vertical-aside-has-menu {{Request::is('restaurant-panel/addon*')?'active':''}}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link"
                            href="{{route('vendor.addon.add-new')}}" title="{{translate('messages.addons')}}"
                        >
                            <i class="tio-add-circle-outlined nav-icon"></i>
                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                {{translate('messages.addons')}}
                            </span>
                        </a>
                    </li>
                    @endif

                    <!-- DeliveryMan -->
                    @if(\App\CentralLogics\Helpers::employee_module_permission_check('deliveryman'))
                        <li class="nav-item">
                            <small class="nav-subtitle"
                                   title="{{translate('messages.deliveryman')}} {{translate('messages.section')}}">{{translate('messages.deliveryman')}} {{translate('messages.management')}}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>
                        <li class="navbar-vertical-aside-has-menu {{Request::is('restaurant-panel/delivery-man/add')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               href="{{route('vendor.delivery-man.add')}}"
                               title="{{translate('messages.add_delivery_man')}}"
                            >
                                <i class="tio-running nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{translate('messages.add_delivery_man')}}
                                </span>
                            </a>
                        </li>

                        <li class="navbar-vertical-aside-has-menu {{Request::is('restaurant-panel/delivery-man/list')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               href="{{route('vendor.delivery-man.list')}}"
                               title="{{translate('messages.deliveryman')}} {{translate('messages.list')}}"
                            >
                                <i class="tio-filter-list nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{translate('messages.deliverymen')}} {{translate('messages.list')}}
                                </span>
                            </a>
                        </li>

                        {{--<li class="navbar-vertical-aside-has-menu {{Request::is('restaurant-panel/delivery-man/reviews/list')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               href="{{route('vendor.delivery-man.reviews.list')}}" title="{{translate('messages.reviews')}}"
                            >
                                <i class="tio-star-outlined nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{translate('messages.reviews')}}
                                </span>
                            </a>
                        </li>--}}
                    @endif
                <!-- End DeliveryMan -->


                    <!-- Business Section-->
                    <li class="nav-item">
                        <small class="nav-subtitle"
                                title="{{translate('messages.business')}} {{translate('messages.section')}}">{{translate('messages.business')}} {{translate('messages.management')}}</small>
                        <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                    </li>

                    @if(\App\CentralLogics\Helpers::employee_module_permission_check('restaurant_setup'))
                    <li class="navbar-vertical-aside-has-menu {{Request::is('restaurant-panel/business-settings/restaurant-setup')?'active':''}}">
                        <a class="nav-link " href="{{route('vendor.business-settings.restaurant-setup')}}" title="{{translate('messages.restaurant')}} {{translate('messages.config')}}"
                        >
                            <span class="tio-settings nav-icon"></span>
                            <span
                                class="text-truncate">{{translate('messages.restaurant')}} {{translate('messages.config')}}</span>
                        </a>
                    </li>
                    @endif

                    @if(\App\CentralLogics\Helpers::employee_module_permission_check('my_shop'))
                    <li class="navbar-vertical-aside-has-menu {{Request::is('restaurant-panel/restaurant/view')?'active':''}}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link"
                            href="{{route('vendor.shop.view')}}"
                            title="{{translate('My Resturant')}}">
                            <i class="tio-home nav-icon"></i>
                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                {{translate('messages.my_shop')}}
                            </span>
                        </a>
                    </li>
                    @if (\App\CentralLogics\Helpers::employee_module_permission_check('subscription') && \App\CentralLogics\Helpers::get_restaurant_data()->restaurant_model != 'commission')
                    <li class="navbar-vertical-aside-has-menu {{Request::is('restaurant-panel/subscription*')?'active':''}}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link"
                            href="{{route('vendor.subscription.subscription')}}"
                            title="{{translate('My Subscriptions')}}">
                            <i class="tio-crown nav-icon"></i>
                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                {{translate('messages.My Subscriptions')}}
                            </span>
                        </a>
                    </li>
                    @endif
                    @endif

                    @if(\App\CentralLogics\Helpers::employee_module_permission_check('bank_info'))
                    <!-- Business Settings -->
                    <li class="navbar-vertical-aside-has-menu {{Request::is('restaurant-panel/profile*')?'active':''}}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link"
                            href="{{route('vendor.profile.bankView')}}"
                            title="{{translate('messages.my')}} {{translate('messages.bank_info')}}">
                            <i class="tio-shop nav-icon"></i>
                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                {{translate('messages.my')}} {{translate('messages.bank_info')}}
                            </span>
                        </a>
                    </li>
                    @endif


                    @if(\App\CentralLogics\Helpers::employee_module_permission_check('wallet'))
                    <!-- RestaurantWallet -->
                    <li class="navbar-vertical-aside-has-menu {{Request::is('restaurant-panel/wallet*')?'active':''}}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link" href="{{route('vendor.wallet.index')}}" title="{{translate('messages.my')}} {{translate('messages.wallet')}}"
                        >
                            <i class="tio-table nav-icon"></i>
                            <span
                                class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('messages.my')}} {{translate('messages.wallet')}}</span>
                        </a>
                    </li>
                    @endif
                    <!-- End RestaurantWallet -->
                    @if(\App\CentralLogics\Helpers::employee_module_permission_check('reviews'))
                    <li class="navbar-vertical-aside-has-menu {{Request::is('restaurant-panel/reviews')?'active':''}}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link"
                            href="{{route('vendor.reviews')}}" title="{{translate('messages.reviews')}}"
                        >
                            <i class="tio-star-outlined nav-icon"></i>
                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                {{translate('messages.reviews')}}
                            </span>
                        </a>
                    </li>
                    @endif
                    <!-- End RestaurantWallet -->
                    @if(\App\CentralLogics\Helpers::employee_module_permission_check('chat'))
                    <li class="navbar-vertical-aside-has-menu {{Request::is('restaurant-panel/message*')?'active':''}}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link"
                            href="{{route('vendor.message.list')}}" title="{{translate('messages.chat')}}"
                        >
                            <i class="tio-chat nav-icon"></i>
                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                {{translate('messages.chat')}}
                            </span>
                        </a>
                    </li>
                    @endif
                    <!-- End Business Settings -->

                    <!-- Employee-->
                    <li class="nav-item">
                        <small class="nav-subtitle" title="{{translate('messages.Report')}} {{translate('messages.section')}}">{{translate('messages.Report')}} {{translate('messages.section')}}</small>
                        <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                    </li>

                    @if(\App\CentralLogics\Helpers::employee_module_permission_check('report'))
                    <li class="navbar-vertical-aside-has-menu {{ Request::is('vendor/report/expense-report') ? 'active' : '' }}">
                        <a class="nav-link " href="{{ route('vendor.report.expense-report') }}" title="{{ translate('messages.expense_report') }}">
                            <span class="tio-money nav-icon"></span>
                            <span class="text-truncate">{{ translate('messages.expense_report') }}</span>
                        </a>
                    </li>
                    @endif
                    <!-- Employee-->
                    <li class="nav-item">
                        <small class="nav-subtitle" title="{{translate('messages.employee')}} {{translate('messages.section')}}">{{translate('messages.employee')}} {{translate('messages.section')}}</small>
                        <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                    </li>

                    @if(\App\CentralLogics\Helpers::employee_module_permission_check('custom_role'))
                    <li class="navbar-vertical-aside-has-menu {{Request::is('restaurant-panel/custom-role*')?'active':''}}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link" href="{{route('vendor.custom-role.create')}}"
                        title="{{translate('messages.employee')}} {{translate('messages.Role')}}">
                            <i class="tio-incognito nav-icon"></i>
                            <span
                                class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('messages.employee')}} {{translate('messages.Role')}}</span>
                        </a>
                    </li>
                    @endif

                    @if(\App\CentralLogics\Helpers::employee_module_permission_check('employee'))
                    <li class="navbar-vertical-aside-has-menu {{Request::is('restaurant-panel/employee*')?'active':''}}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:"
                        title="{{translate('messages.employees')}}">
                            <i class="tio-user nav-icon"></i>
                            <span
                                class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('messages.employees')}}</span>
                        </a>
                        <ul class="js-navbar-vertical-aside-submenu nav nav-sub {{Request::is('restaurant-panel/employee*')?'d-block':'initial-hidden'}}">
                            <li class="nav-item {{Request::is('restaurant-panel/employee/add-new')?'active':''}}">
                                <a class="nav-link " href="{{route('vendor.employee.add-new')}}" title="{{translate('messages.add')}} {{translate('messages.new')}} {{translate('messages.Employee')}}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate">{{translate('messages.add')}} {{translate('messages.new')}} {{translate('messages.employee')}}</span>
                                </a>
                            </li>
                            <li class="nav-item {{Request::is('restaurant-panel/employee/list')?'active':''}}">
                                <a class="nav-link " href="{{route('vendor.employee.list')}}" title="{{translate('messages.Employee')}} {{translate('messages.list')}}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate">{{translate('messages.list')}}</span>
                                </a>
                            </li>

                        </ul>
                    </li>
                    @endif
                    <!-- End Employee -->

                    <li class="nav-item pt-100px">

                    </li>
                </ul>
            </div>
            <!-- End Content -->
        </div>
    </aside>
</div>

<div id="sidebarCompact" class="d-none">

</div>


{{--<script>
    $(document).ready(function () {
        $('.navbar-vertical-content').animate({
            scrollTop: $('#scroll-here').offset().top
        }, 'slow');
    });
</script>--}}

@push('script_2')
<script>
    $(window).on('load' , function() {
        if($(".navbar-vertical-content li.active").length) {
            $('.navbar-vertical-content').animate({
                scrollTop: $(".navbar-vertical-content li.active").offset().top - 150
            }, 100);
        }
        });
</script>
@endpush
