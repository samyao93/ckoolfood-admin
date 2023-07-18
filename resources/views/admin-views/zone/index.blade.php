@extends('layouts.admin.app')

@section('title',translate('Add new zone'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title">
                        <img src="{{asset('/public/assets/admin/img/zone.png')}}" alt="" class="mr-2"> {{translate('messages.Add_New_Business_Zone')}}
                    </h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <form action="javascript:" method="post" id="zone_form" class="shadow--card">
                    @csrf
                    <div class="row justify-content-between">
                        <div class="col-md-5">
                            <div class="zone-setup-instructions">
                                <div class="zone-setup-top">
                                    <h6 class="subtitle">{{translate('messages.instructions')}}</h6>
                                    <p>
                                        {{translate('messages.Create_&_connect_dots_in_a_specific_area_on_the_map_to_add_a_new_business_zone.')}}
                                    </p>
                                </div>
                                <div class="zone-setup-item">
                                    <div class="zone-setup-icon">
                                        <i class="tio-hand-draw"></i>
                                    </div>
                                    <div class="info">
                                        {{translate('messages.Use_this_‘Hand_Tool’_to_find_your_target_zone.')}}
                                    </div>
                                </div>
                                <div class="zone-setup-item">
                                    <div class="zone-setup-icon">
                                        <i class="tio-free-transform"></i>
                                    </div>
                                    <div class="info">
                                        {{translate('messages.Use this ‘Shape Tool’ to point out the areas and connect the dots. A minimum of 3 points/dots is required.')}}
                                    </div>
                                </div>
                                <div class="instructions-image mt-4">
                                    <img src={{asset('public/assets/admin/img/instructions.gif')}} alt="instructions">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-7 zone-setup">
                            <div class="pl-xl-5 pl-xxl-0">
                                {{-- <div class="d-flex"> --}}
                                    @php($language=\App\Models\BusinessSetting::where('key','language')->first())
                                    @php($language = $language?->value )
                                    @php($default_lang = str_replace('_', '-', app()->getLocale()))
                                    {{-- @if($language) --}}
                                        <ul class="nav nav-tabs mb-4">
                                            <li class="nav-item">
                                                <a class="nav-link lang_link active"
                                                href="#"
                                                id="default-link">{{translate('messages.default')}}</a>
                                            </li>
                                            @if ($language)
                                            @forelse (json_decode($language) as $lang)
                                                <li class="nav-item">
                                                    <a class="nav-link lang_link"
                                                        href="#"
                                                        id="{{ $lang }}-link">{{ \App\CentralLogics\Helpers::get_language_name($lang) . '(' . strtoupper($lang) . ')' }}</a>
                                                </li>
                                                @empty
                                            @endforelse
                                            @endif
                                            <span class="form-label-secondary text-danger mt-2"
                                            data-toggle="tooltip" data-placement="right"
                                            data-original-title="{{ translate('Choose_your_preferred_language_&_set_your_zone_name.') }}"><img
                                            src="{{ asset('/public/assets/admin/img/info-circle.svg') }}"
                                            alt="{{ translate('messages.veg_non_veg') }}"></span>
                                        </ul>
                                {{-- </div> --}}
                                <div class="tab-content">

                                    <div class="form-group mb-3 lang_form" id="default-form">
                                        <label class="input-label" for="exampleFormControlInput1">{{ translate('messages.business_Zone') }} {{translate('messages.name')}} ({{ translate('messages.default') }})</label>
                                        <input type="text" name="name[]"  class="form-control" placeholder="{{translate('messages.Type_new_zone_name_here')}}" maxlength="191" id="default-form-input"  oninvalid="document.getElementById('default-form-input').click()">
                                    </div>
                                    <input type="hidden" name="lang[]" value="default">
                                    @if ($language)
                                    @forelse (json_decode($language) as $lang)
                                        <div class="form-group mb-3 d-none lang_form" id="{{$lang}}-form">
                                            <label class="input-label"
                                                for="exampleFormControlInput1">{{ translate('messages.business_Zone') }} {{translate('messages.name')}} ({{strtoupper($lang)}})</label>
                                            <input id="name" type="text" name="name[]" class="form-control h--45px" placeholder="{{translate('messages.Type_new_zone_name_here')}}" >
                                        </div>
                                        <input type="hidden" name="lang[]" value="{{$lang}}">
                                    @empty
                                    @endforelse
                                    @endif
                                </div>


                                <div class="form-group mb-3 d-none">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('Coordinates') }}<span class="form-label-secondary" data-toggle="tooltip" data-placement="right"
                                        data-original-title="{{translate('messages.draw_your_zone_on_the_map')}}">{{translate('messages.draw_your_zone_on_the_map')}}</span></label>
                                        <textarea type="text" rows="8" name="coordinates"  id="coordinates" class="form-control" readonly></textarea>
                                </div>

                                <div class="map-warper overflow-hidden rounded">
                                    <input id="pac-input" class="controls rounded initial-8" title="{{translate('messages.search_your_location_here')}}" type="text" placeholder="{{translate('messages.search_here')}}"/>
                                    <div id="map-canvas" class="h-100 m-0 p-0"></div>
                                </div>
                                <div class="btn--container mt-3 justify-content-end">
                                    <button id="reset_btn" type="button" class="btn btn--reset">{{translate('messages.reset')}}</button>
                                    <button type="submit" class="btn btn--primary">{{translate('messages.submit')}}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-sm-12 col-lg-12 mb-3 my-lg-2">
                <div class="card">
                    <div class="card-header py-2 flex-wrap border-0 align-items-center">
                        <div class="search--button-wrapper">
                            <h5 class="card-title">{{translate('messages.zone')}} {{translate('messages.list')}}<span class="badge badge-soft-dark ml-2" id="itemCount">{{$zones->total()}}</span></h5>
                            <form class="my-2 mr-sm-2 mr-xl-4 ml-sm-auto flex-grow-1 flex-grow-sm-0">
                                            <!-- Search -->

                                <div class="input--group input-group input-group-merge input-group-flush">
                                    <input id="datatableSearch_" type="search" name="search" class="form-control"
                                            placeholder="{{ translate('messages.Search_by_name') }}" aria-label="{{translate('messages.search')}}">
                                    <button type="submit" class="btn btn--secondary">
                                        <i class="tio-search"></i>
                                    </button>
                                </div>
                                <!-- End Search -->
                            </form>
                            <!-- Unfold -->
                            <div class="hs-unfold ml-3">
                                    <a class="js-hs-unfold-invoker btn btn-sm btn-white dropdown-toggle btn export-btn btn-outline-primary btn--primary font--sm" href="javascript:;"
                                    data-hs-unfold-options='{
                                        "target": "#usersExportDropdown",
                                        "type": "css-animation"
                                    }'>
                                    <i class="tio-download-to mr-1"></i> {{translate('messages.export')}}
                                </a>

                                <div id="usersExportDropdown"
                                        class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-sm-right">
                                    <span class="dropdown-header">{{translate('messages.download')}} {{translate('messages.options')}}</span>
                                    <a target="__blank" id="export-excel" class="dropdown-item" href="{{route('admin.zone.export-zones', ['type'=>'excel'])}}">
                                        <img class="avatar avatar-xss avatar-4by3 mr-2"
                                        src="{{asset('public/assets/admin')}}/svg/components/excel.svg"
                                        alt="Image Description">
                                        {{translate('messages.excel')}}
                                    </a>
                                    <a target="__blank" id="export-csv" class="dropdown-item" href="{{route('admin.zone.export-zones', ['type'=>'csv'])}}">
                                        <img class="avatar avatar-xss avatar-4by3 mr-2"
                                                src="{{asset('public/assets/admin')}}/svg/components/placeholder-csv-format.svg"
                                                alt="Image Description">
                                        .{{translate('messages.csv')}}
                                    </a>
                                </div>
                            </div>
                            <!-- End Unfold -->
                        </div>
                    </div>
                    <!-- Table -->
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
                                <th>{{translate('messages.sl')}}</th>
                                <th class="text-center">{{translate('messages.zone')}} {{translate('messages.id')}}</th>
                                <th class="pl-5">{{translate('messages.name')}}</th>
                                <th class="text-center">{{translate('messages.restaurants')}}</th>
                                <th class="text-center">{{translate('messages.deliverymen')}}</th>
                                <th >{{translate('messages.status')}}</th>
                                <th class="w-40px text-center">{{translate('messages.action')}}</th>
                            </tr>
                            </thead>

                            <tbody id="set-rows">
                                @php($non_mod = 0)
                            @foreach($zones as $key=>$zone)
                            @php($non_mod = ( ($zone?->minimum_shipping_charge && $zone?->per_km_shipping_charge ) && $non_mod == 0) ? $non_mod:$non_mod+1 )

                            {{-- @php(? $non_mod:$non_mod = 1 ) --}}
                                <tr>
                                    <td>{{$key+$zones?->firstItem()}}</td>
                                    <td class="text-center">
                                        <span class="move-left">
                                            {{$zone->id}}
                                        </span>
                                    </td>
                                    <td class="pl-5">
                                        <span class="d-block font-size-sm text-body">
                                            {{$zone['name']}}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="move-left">
                                            {{$zone->restaurants_count}}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="move-left">
                                            {{$zone->deliverymen_count}}
                                        </span>
                                    </td>
                                    <td>
                                        <label class="toggle-switch toggle-switch-sm">
                                            <input type="checkbox" class="toggle-switch-input" id="status-{{$zone['id']}}" {{$zone->status?'checked':''}}
                                            onclick="toogleStatusModal(event,'status-{{$zone['id']}}','zone-status-on.png','zone-status-off.png','{{translate('Want_to_activate_this_Zone?')}}','{{translate('Want_to_deactivate_this_Zone?')}}',`<p>{{translate('If_you_activate_this_zone,_Customers_can_see_all_restaurants_&_products_available_under_this_Zone_from_the_Customer_App_&_Website.')}}</p>`,`<p>{{translate('If_you_deactivate_this_zone,_Customers_Will_NOT_see_all_restaurants_&_products_available_under_this_Zone_from_the_Customer_App_&_Website.')}}</p>`)"
                                            >
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                        <form action="{{route('admin.zone.status',[$zone['id'],$zone->status?0:1])}}" method="get" id="status-{{$zone['id']}}_form">
                                        </form>
                                    </td>
                                    <td>
                                        <div class="btn--container justify-content-center">
                                            <a class="btn btn-sm btn--primary btn-outline-primary action-btn"
                                                href="{{route('admin.zone.edit',[$zone['id']])}}" title="{{translate('messages.edit')}} {{translate('messages.zone')}}"><i class="tio-edit"></i>
                                            </a>
                                            <!-- <div class="popover-wrapper active"> add active class to show -->
                                            <div class="popover-wrapper hide_data {{ $non_mod == 1 ? 'active':'' }} ">
                                                <a class="btn active action-btn btn--warning btn-outline-warning" href="{{route('admin.zone.settings',['id'=>$zone['id']])}}" title="{{translate('messages.zone_settings')}}">
                                                    <i class="tio-settings"></i>
                                                </a>
                                                <div class="popover __popover  {{ $non_mod == 1  ? '':'d-none' }}">
                                                    <div class="arrow"></div>
                                                    <h3 class="popover-header d-flex justify-content-between">
                                                        <span>{{ translate('messages.Important!') }}</span>
                                                        <span onclick="hide_data()" class="tio-clear"></span>
                                                    </h3>
                                                    <div class="popover-body">
                                                        {{ translate('The_Business_Zone_will_NOT_work_if_you_don’t_add_the_minimum_delivery_charge_&_per_km_delivery_charge.') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        @if(count($zones) === 0)
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
                                {!! $zones->withQueryString()->links() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Table -->
        </div>
    </div>


    <div class="modal fade" id="status-warning-modal">
        <div class="modal-dialog status-warning-modal">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true" class="tio-clear"></span>
                    </button>
                </div>
                <div class="modal-body pt-0">
                    <div class="text-center mb-20">
                        <img src="{{asset('/public/assets/admin/img/zone-status-on.png')}}" alt="" class="mb-20">
                        <h5 class="modal-title">{{translate('By switching the status to “ON”,  this zone and under all the functionality of this zone will be turned on')}}</h5>
                        <p class="txt">
                            {{translate("In the user app & website all stores & products  already assigned under this zone will show to the customers")}}
                        </p>
                    {{--
                        <img src="{{asset('/public/assets/admin/img/zone-status-off.png')}}" alt="" class="mb-20">
                        <h5 class="modal-title">{{translate('By switching the status off, this zone and under all the functionality of this zone will be turned off')}}</h5>
                        <p class="txt">
                            {{translate("In the user app & website no stores & products  already assigned under this zon this zone will not show to the customers")}}
                        </p>
                    --}}
                    </div>
                    <div class="btn--container justify-content-center">
                        <button type="submit" class="btn btn--primary min-w-120" data-dismiss="modal">{{translate('Ok')}}</button>
                        <button id="reset_btn" type="reset" class="btn btn--cancel min-w-120" data-dismiss="modal">{{translate("Cancel")}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="warning-modal">
        <div class="modal-dialog modal-lg warning-modal">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <h3 class="modal-title mb-3">{{translate('messages.New_Business_Zone_Created_Successfully!')}}</h3>
                        <p class="txt">
                            {{translate('messages.NEXT_IMPORTANT_STEP:_You_need_to_add_‘Delivery_Charge’_with_other_details_from_the_Zone_Settings._If_you_don’t_add_a_delivery_charge,_the_Zone_you_created_won’t_function_properly.')}}
                        </p>
                    </div>
                    <img src="{{asset('/public/assets/admin/img/zone-instruction.png')}}" alt="admin/img" class="w-100">
                    <div class="mt-3 d-flex flex-wrap align-items-center justify-content-between">
                        <label class="form-check form--check m-0">
                            {{-- <input type="checkbox" class="form-check-input rounded"> --}}
                            {{-- <span class="form-check-label">{{translate("Don't show this anymore")}}</span> --}}
                        </label>
                        <div class="btn--container justify-content-end">
                            <button id="reset_btn" type="reset" class="btn btn--reset" data-dismiss="modal">{{translate('messages.I_Will_Do_It_Later')}}</button>
                            <button type="submit" class="btn btn--primary" data-dismiss="modal">{{translate('messages.Go_to_Zone_Settings')}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script_2')

<script>
    function hide_data(){
        // alert('ok');
        $(".hide_data").removeClass('active');
    }
        // $('#warning-modal').modal('show');
        function status_form_alert(id, message, e) {
            e.preventDefault();
            Swal.fire({
                title: "{{translate('messages.are_you_sure')}}",
                text: message,
                type: 'warning',
                showCloseButton: true,
                showCancelButton: true,
                cancelButtonColor: 'var(--secondary-clr)',
                confirmButtonColor: 'var(--primary-clr)',
                cancelButtonText: '{{ translate('Cancel') }}',
                confirmButtonText: '{{ translate('yes') }}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $('#'+id).submit()
                }
            })
        }
    auto_grow();
    function auto_grow() {
        let element = document.getElementById("coordinates");
        element.style.height = "5px";
        element.style.height = (element.scrollHeight)+"px";
    }

    </script>
    <script>
        $(document).on('ready', function () {
            // $('#zone_wise_delivery_fee').on('change', function(){
            //     if($("#zone_wise_delivery_fee").is(':checked')){
            //         $('.shipping_input').removeAttr('readonly');
            //         $('.shipping_input').attr('required', 'required');
            //         $('.shipping_input_group').show();
            //     } else {
            //         $('.shipping_input').attr('readonly', true);
            //         $('.shipping_input').removeAttr('required');
            //         $('.shipping_input').val('');
            //         $('.shipping_input_group').hide();
            //     }
            // })
            // INITIALIZATION OF DATATABLES
            // =======================================================
            var datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'));

            $('#column1_search').on('keyup', function () {
                datatable
                    .columns(1)
                    .search(this.value)
                    .draw();
            });


            $('#column3_search').on('change', function () {
                datatable
                    .columns(2)
                    .search(this.value)
                    .draw();
            });


            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function () {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });

            $("#zone_form").on('keydown', function(e){
                if (e.keyCode === 13) {
                    e.preventDefault();
                }
            })
        });
    </script>

    <script src="https://maps.googleapis.com/maps/api/js?key={{ \App\Models\BusinessSetting::where('key', 'map_api_key')->first()->value }}&libraries=drawing,places&v=3.45.8"></script>

    <script>
        var map; // Global declaration of the map
        var drawingManager;
        var lastpolygon = null;
        var polygons = [];

        function resetMap(controlDiv) {
            // Set CSS for the control border.
            const controlUI = document.createElement("div");
            controlUI.style.backgroundColor = "#fff";
            controlUI.style.border = "2px solid #fff";
            controlUI.style.borderRadius = "3px";
            controlUI.style.boxShadow = "0 2px 6px rgba(0,0,0,.3)";
            controlUI.style.cursor = "pointer";
            controlUI.style.marginTop = "8px";
            controlUI.style.marginBottom = "22px";
            controlUI.style.textAlign = "center";
            controlUI.title = "Reset map";
            controlDiv.appendChild(controlUI);
            // Set CSS for the control interior.
            const controlText = document.createElement("div");
            controlText.style.color = "rgb(25,25,25)";
            controlText.style.fontFamily = "Roboto,Arial,sans-serif";
            controlText.style.fontSize = "10px";
            controlText.style.lineHeight = "16px";
            controlText.style.paddingLeft = "2px";
            controlText.style.paddingRight = "2px";
            controlText.innerHTML = "X";
            controlUI.appendChild(controlText);
            // Setup the click event listeners: simply set the map to Chicago.
            controlUI.addEventListener("click", () => {
                lastpolygon.setMap(null);
                $('#coordinates').val('');

            });
        }

        function initialize() {
            @php($default_location=\App\Models\BusinessSetting::where('key','default_location')->first())
            @php($default_location=$default_location->value?json_decode($default_location->value, true):0)
            var myLatlng = { lat: {{$default_location?$default_location['lat']:'23.757989'}}, lng: {{$default_location?$default_location['lng']:'90.360587'}} };


            var myOptions = {
                zoom: 13,
                center: myLatlng,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            }
            map = new google.maps.Map(document.getElementById("map-canvas"), myOptions);
            drawingManager = new google.maps.drawing.DrawingManager({
                drawingMode: google.maps.drawing.OverlayType.POLYGON,
                drawingControl: true,
                drawingControlOptions: {
                position: google.maps.ControlPosition.TOP_CENTER,
                drawingModes: [google.maps.drawing.OverlayType.POLYGON]
                },
                polygonOptions: {
                editable: true
                }
            });
            drawingManager.setMap(map);


            //get current location block
            // infoWindow = new google.maps.InfoWindow();
            // Try HTML5 geolocation.
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                    const pos = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude,
                    };
                    map.setCenter(pos);
                });
            }

            google.maps.event.addListener(drawingManager, "overlaycomplete", function(event) {
                if(lastpolygon)
                {
                    lastpolygon.setMap(null);
                }
                $('#coordinates').val(event.overlay.getPath().getArray());
                lastpolygon = event.overlay;
                auto_grow();
            });

            const resetDiv = document.createElement("div");
            resetMap(resetDiv, lastpolygon);
            map.controls[google.maps.ControlPosition.TOP_CENTER].push(resetDiv);

            // Create the search box and link it to the UI element.
            const input = document.getElementById("pac-input");
            const searchBox = new google.maps.places.SearchBox(input);
            map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);
            // Bias the SearchBox results towards current map's viewport.
            map.addListener("bounds_changed", () => {
                searchBox.setBounds(map.getBounds());
            });
            let markers = [];
            // Listen for the event fired when the user selects a prediction and retrieve
            // more details for that place.
            searchBox.addListener("places_changed", () => {
                const places = searchBox.getPlaces();

                if (places.length == 0) {
                return;
                }
                // Clear out the old markers.
                markers.forEach((marker) => {
                marker.setMap(null);
                });
                markers = [];
                // For each place, get the icon, name and location.
                const bounds = new google.maps.LatLngBounds();
                places.forEach((place) => {
                if (!place.geometry || !place.geometry.location) {
                    console.log("Returned place contains no geometry");
                    return;
                }
                const icon = {
                    url: place.icon,
                    size: new google.maps.Size(71, 71),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(17, 34),
                    scaledSize: new google.maps.Size(25, 25),
                };
                // Create a marker for each place.
                markers.push(
                    new google.maps.Marker({
                    map,
                    icon,
                    title: place.name,
                    position: place.geometry.location,
                    })
                );

                if (place.geometry.viewport) {
                    // Only geocodes have viewport.
                    bounds.union(place.geometry.viewport);
                } else {
                    bounds.extend(place.geometry.location);
                }
                });
                map.fitBounds(bounds);
            });
        }

        google.maps.event.addDomListener(window, 'load', initialize);


        function set_all_zones()
        {
            $.get({
                url: '{{route('admin.zone.zoneCoordinates')}}',
                dataType: 'json',
                success: function (data) {

                    console.log(data);
                    for(var i=0; i<data.length;i++)
                    {
                        polygons.push(new google.maps.Polygon({
                            paths: data[i],
                            strokeColor: "#FF0000",
                            strokeOpacity: 0.8,
                            strokeWeight: 2,
                            fillColor: "#FF0000",
                            fillOpacity: 0.1,
                        }));
                        polygons[i].setMap(map);
                    }

                },
            });
        }
        $(document).on('ready', function(){
            set_all_zones();
        });

    </script>
    <script>

    </script>
    <script>
            $('#reset_btn').click(function(){
                $('.tab-content').find('input:text').val('');
                lastpolygon.setMap(null);
                $('#coordinates').val(null);
            })



        $(document).on('ready', function() {

            $("#maximum_shipping_charge_status").on('change', function() {
                if ($("#maximum_shipping_charge_status").is(':checked')) {
                    $('#maximum_shipping_charge').removeAttr('readonly');
                } else {
                    $('#maximum_shipping_charge').attr('readonly', true);
                    $('#maximum_shipping_charge').val('Ex : 0');
                }
            });
            $("#max_cod_order_amount_status").on('change', function() {
                if ($("#max_cod_order_amount_status").is(':checked')) {
                    $('#max_cod_order_amount').removeAttr('readonly');
                } else {
                    $('#max_cod_order_amount').attr('readonly', true);
                    $('#max_cod_order_amount').val('Ex : 0');
                }
            });



        });
    </script>

<script>
    $(".lang_link").click(function(e){
        e.preventDefault();
        $(".lang_link").removeClass('active');
        $(".lang_form").addClass('d-none');
        $(this).addClass('active');

        let form_id = this.id;
        let lang = form_id.substring(0, form_id.length - 5);
        console.log(lang);
        $("#"+lang+"-form").removeClass('d-none');
        if(lang == '{{$default_lang}}')
        {
            $(".from_part_2").removeClass('d-none');
        }
        else
        {
            $(".from_part_2").addClass('d-none');
        }
    });


    $('#zone_form').on('submit', function () {
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('admin.zone.store')}}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    if(data.errors){
                        for (var i = 0; i < data.errors.length; i++) {
                            toastr.error(data.errors[i].message, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        }
                    }
                    else{
                        $('.tab-content').find('input:text').val('');
                        $('input[name="name"]').val(null);
                        lastpolygon.setMap(null);
                        $('#coordinates').val(null);
                        toastr.success("{{ translate('messages.New_Business_Zone_Created_Successfully!') }}", {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        $('#set-rows').html(data.view);
                        $('#itemCount').html(data.total);
                        $("#warning-modal").modal("show");
                    }
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        });
</script>
@endpush
