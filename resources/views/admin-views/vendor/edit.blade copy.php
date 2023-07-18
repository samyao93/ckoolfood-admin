@extends('layouts.admin.app')

@section('title', translate('Update restaurant info'))

@section('content')
    <div class="content container-fluid initial-57">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h2 class="page-header-title"><div class="card-header-icon"><i class="tio-edit"></i></div> {{ translate('messages.update') }}
                        {{ translate('messages.restaurant') }}</h2>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        @php($language=\App\Models\BusinessSetting::where('key','language')->first())
        @php($language = $language->value ?? null)
        @php($default_lang = str_replace('_', '-', app()->getLocale()))

        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div id="vendor_form" class="form-container">
                    <form action="{{ route('admin.restaurant.update', [$restaurant['id']]) }}" method="post"
                        class="js-validate" enctype="multipart/form-data">
                        @csrf
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title">
                                <div class="card-header-icon">
                                    <i class="tio-museum"></i> {{ translate('messages.restaurant') }}
                                                {{ translate('messages.info') }}
                                </div>
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($language)
                            <ul class="nav nav-tabs mb-4">
                                <li class="nav-item">
                                    <a class="nav-link lang_link active"
                                    href="#"
                                    id="default-link">{{ translate('Default') }}</a>
                                </li>
                                @foreach (json_decode($language) as $lang)
                                    <li class="nav-item">
                                        <a class="nav-link lang_link"
                                            href="#"
                                            id="{{ $lang }}-link">{{ \App\CentralLogics\Helpers::get_language_name($lang) . '(' . strtoupper($lang) . ')' }}</a>
                                    </li>
                                @endforeach
                            </ul>
                            @endif
                            <div class="row">
                                <div class="col-md-6 col-lg-4 col-12">
                                    <div class="form-group lang_form" id="default-form">
                                        <label class="input-label" for="exampleFormControlInput1">{{ translate('messages.restaurant') }}
                                            {{ translate('messages.name') }} ({{translate('messages.default')}})</label>
                                            <input type="text" name="name[]" class="form-control" placeholder="{{ translate('messages.restaurant') }} {{ translate('messages.name') }}" maxlength="191" value="{{$restaurant['name']}}" required oninvalid="document.getElementById('en-link').click()">
                                    </div>
                                    <input type="hidden" name="lang[]" value="default">
                                    @if ($language)
                                        @foreach(json_decode($language) as $lang)
                                            <?php
                                                if(count($restaurant['translations'])){
                                                    $translate = [];
                                                    foreach($restaurant['translations'] as $t)
                                                    {
                                                        if($t->locale == $lang && $t->key=="name"){
                                                            $translate[$lang]['name'] = $t->value;
                                                        }
                                                    }
                                                }
                                            ?>
                                            <div class="form-group d-none lang_form" id="{{$lang}}-form">
                                                <label class="input-label" for="exampleFormControlInput1">{{ translate('messages.restaurant') }}
                                            {{ translate('messages.name') }} ({{strtoupper($lang)}})</label>
                                                <input type="text" name="name[]" class="form-control" placeholder="{{ translate('messages.restaurant') }} {{ translate('messages.name') }}" maxlength="191" value="{{$translate[$lang]['name']??''}}" oninvalid="document.getElementById('en-link').click()">
                                            </div>
                                            <input type="hidden" name="lang[]" value="{{$lang}}">
                                        @endforeach
                                    
                                    @endif
                                </div>
                                <div class="col-md-6 col-lg-4 col-12">
                                    <div class="form-group">
                                        <label class="input-label" for="address">{{ translate('messages.vat/tax') }} (%)</label>
                                        <input id="tax" type="number" name="tax" class="form-control h--45px"
                                            placeholder="{{ translate('messages.vat/tax') }}" min="0" step=".01" required
                                            value="{{ $restaurant->tax }}">
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <label class="input-label" for="address">{{ translate('messages.restaurant') }}
                                            {{ translate('messages.address') }}</label>
                                        <input id="address" type="text"  name="address" class="form-control" placeholder="{{ translate('messages.restaurant') }} {{ translate('messages.address') }}" value="{{ $restaurant->address }}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="minimum_delivery_time">{{ translate('messages.minimum_delivery_time') }}</label>
                                        <input id="minimum_delivery_time" type="number" name="minimum_delivery_time" class="form-control h--45px" placeholder="30"
                                            pattern="^[0-9]{2}$" required
                                            value="{{ explode('-', $restaurant->delivery_time)[0] }}">
                                    </div>

                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="maximum_delivery_time">{{ translate('messages.maximum_delivery_time') }}</label>
                                        <input id="maximum_delivery_time" type="number" name="maximum_delivery_time" class="form-control h--45px" placeholder="40"
                                            pattern="[0-9]{2}" required
                                            value="{{ explode('-', $restaurant->delivery_time)[1] }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4 pt-lg-3">
                                <div class="col-md-6 col-lg-4 col-12">
                                    <center>
                                        <img class="initial-57-1" id="viewer" onerror="this.src='{{ asset('public/assets/admin/img/100x100/restaurant-default-image.png') }}'" src="{{ asset('storage/app/public/restaurant/' . $restaurant->logo) }}" alt="Product thumbnail" />
                                    </center>

                                    <div class="form-group">
                                        <label class="input-label">{{ translate('messages.restaurant') }}
                                            {{ translate('messages.logo') }}<small class="text-danger"> (
                                                {{ translate('messages.ratio') }} {{translate('messages.1:1')}}
                                                )</small></label>
                                        <div class="custom-file">
                                            <input type="file" name="logo" id="customFileEg1" class="custom-file-input"
                                                accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                            <label class="custom-file-label" for="customFileEg1">{{ translate('messages.choose') }}
                                                {{ translate('messages.file') }}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4 col-12">
                                    <div class="cover-photo">
                                        <center>
                                            <img class="initial-57-1" id="coverImageViewer" onerror="this.src='{{ asset('public/assets/admin/img/300x100/restaurant-default-image.png') }}'" src="{{ asset('storage/app/public/restaurant/cover/' . $restaurant->cover_photo) }}"
                                                alt="Product thumbnail" />
                                        </center>

                                        <div class="form-group">
                                            <label for="name">{{ translate('messages.upload') }} {{ translate('messages.cover') }}
                                                {{ translate('messages.photo') }} <span
                                                    class="text-danger">({{ translate('messages.ratio') }}
                                                    {{translate('messages.2:1')}})</span></label>
                                            <div class="custom-file">
                                                <input type="file" name="cover_photo" id="coverImageUpload"
                                                    class="custom-file-input"
                                                    accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                                <label class="custom-file-label"
                                                    for="customFileUpload">{{ translate('messages.choose') }}
                                                    {{ translate('messages.file') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="input-label" for="cuisine">{{ translate('messages.cuisine') }}
                                                </label>
                                        <select name="cuisine_ids[]" id="cuisine"  multiple="multiple"
                                            data-placeholder="{{ translate('messages.select') }} {{ translate('messages.Cuisine') }}"
                                            class="form-control h--45px min--45 js-select2-custom">
                                            {{ translate('messages.Cuisine') }}</option>

                                            @php($cuisine_array = \App\Models\Cuisine::where('status',1 )->get()->toArray())
                                            @php($selected_cuisine =isset($restaurant->cuisine) ? $restaurant->cuisine->pluck('id')->toArray() : [])
                                            @foreach ($cuisine_array as $cu)
                                                <option value="{{ $cu['id'] }}"
                                                    {{ in_array($cu['id'], $selected_cuisine) ? 'selected' : '' }}>
                                                    {{ $cu['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="input-label" for="choice_zones">{{ translate('messages.zone') }}<span
                                                class="input-label-secondary"
                                                title="{{ translate('messages.select_zone_for_map') }}"><img
                                                    src="{{ asset('/public/assets/admin/img/info-circle.svg') }}"
                                                    alt="{{ translate('messages.select_zone_for_map') }}"></span></label>
                                        <select name="zone_id" id="choice_zones" onchange="get_zone_data(this.value)"
                                            data-placeholder="{{ translate('messages.select') }} {{ translate('messages.zone') }}"
                                            class="form-control h--45px js-select2-custom">
                                            @foreach (\App\Models\Zone::where('status',1 )->get(['id','name']) as $zone)
                                                @if (isset(auth('admin')->user()->zone_id))
                                                    @if (auth('admin')->user()->zone_id == $zone->id)
                                                        <option value="{{ $zone->id }}"
                                                            {{ $restaurant->zone_id == $zone->id ? 'selected' : '' }}>
                                                            {{ $zone->name }}</option>
                                                    @endif
                                                @else
                                                    <option value="{{ $zone->id }}"
                                                        {{ $restaurant->zone_id == $zone->id ? 'selected' : '' }}>
                                                        {{ $zone->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ translate('messages.latitude') }}<span
                                                class="input-label-secondary"
                                                title="{{ translate('messages.restaurant_lat_lng_warning') }}"><img
                                                    src="{{ asset('/public/assets/admin/img/info-circle.svg') }}"
                                                    alt="{{ translate('messages.restaurant_lat_lng_warning') }}"></span></label>
                                        <input type="text" name="latitude" class="form-control h--45px" id="latitude"
                                            placeholder="{{ translate('messages.Ex :') }} -94.22213" value="{{ $restaurant->latitude }}" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ translate('messages.longitude') }}<span
                                                class="input-label-secondary"
                                                title="{{ translate('messages.restaurant_lat_lng_warning') }}"><img
                                                    src="{{ asset('/public/assets/admin/img/info-circle.svg') }}"
                                                    alt="{{ translate('messages.restaurant_lat_lng_warning') }}"></span></label>
                                        <input type="text" name="longitude" class="form-control h--45px" id="longitude"
                                            placeholder="{{ translate('messages.Ex :') }} 103.344322" value="{{ $restaurant->longitude }}" readonly>
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <input id="pac-input" class="controls rounded initial-8" title="{{translate('messages.search_your_location_here')}}" type="text" placeholder="{{translate('messages.search_here')}}"/>
                                    <div style="height: 370px !important" id="map"></div>
                                </div>

                                {{-- <div class="col-md-4 col-12">
                                <div class="form-group">
                                    <label class="input-label" for="">
                                        <i class="tio-info-outined"
                                        data-toggle="tooltip"
                                        data-placement="top"
                                        title="This value is the radius from your restaurant location, and customer can order food inside  the circle calculated by this radius."></i>
                                        {{translate('messages.coverage')}} ( {{translate('messages.km')}} )
                                    </label>
                                    <input type="number" value=""
                                        name="coverage" class="form-control h--45px" placeholder="{{ translate('messages.Ex :') }} 3">
                                </div>
                            </div> --}}
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title">
                                <div class="card-header-icon">
                                    <i class="tio-user"></i> {{ translate('messages.vendor') }}
                            {{ translate('messages.info') }}
                                </div>
                            </h5>
                        </div>
                        <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="exampleFormControlInput1">{{ translate('messages.first') }}
                                                {{ translate('messages.name') }}</label>
                                            <input id="f_name" type="text" name="f_name" class="form-control h--45px"
                                                placeholder="{{ translate('messages.first') }} {{ translate('messages.name') }}"
                                                value="{{ $restaurant->vendor->f_name }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="exampleFormControlInput1">{{ translate('messages.last') }}
                                                {{ translate('messages.name') }}</label>
                                            <input id="l_name" type="text" name="l_name" class="form-control h--45px"
                                                placeholder="{{ translate('messages.last') }} {{ translate('messages.name') }}"
                                                value="{{ $restaurant->vendor->l_name }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="exampleFormControlInput1">{{ translate('messages.phone') }}</label>
                                            <input id="phone" type="tel" name="phone" class="form-control h--45px" placeholder="{{ translate('messages.Ex :') }} 017********"
                                                value="{{ $restaurant->phone }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title">
                                <div class="card-header-icon">
                                    <i class="tio-user"></i> {{ translate('messages.login') }}
                            {{ translate('messages.info') }}
                                </div>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ translate('messages.email') }}</label>
                                        <input id="email" type="email" name="email" class="form-control h--45px"
                                            placeholder="{{ translate('messages.Ex :') }} ex@example.com" value="{{ $restaurant->email }}" required>
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="js-form-message form-group">
                                        <label class="input-label" for="signupSrPassword">{{translate('messages.password')}}
                                            <span class="input-label-secondary ps-1" title="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"><img src="{{ asset('/public/assets/admin/img/info-circle.svg') }}" alt="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"></span>

                                        </label>

                                        <div class="input-group input-group-merge">
                                            <input type="password" class="js-toggle-password form-control h--45px" name="password"
                                                id="signupSrPassword"
                                                pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"

                                                placeholder="{{ translate('messages.password_length_placeholder', ['length' => '8+']) }}"
                                                aria-label="8+ characters required"
                                                data-msg="Your password is invalid. Please try again."
                                                data-hs-toggle-password-options='{
                                                                "target": [".js-toggle-password-target-1", ".js-toggle-password-target-2"],
                                                                "defaultClass": "tio-hidden-outlined",
                                                                "showClass": "tio-visible-outlined",
                                                                "classChangeTarget": ".js-toggle-passowrd-show-icon-1"
                                                                }'>
                                            <div class="js-toggle-password-target-1 input-group-append">
                                                <a class="input-group-text" href="javascript:;">
                                                    <i class="js-toggle-passowrd-show-icon-1 tio-visible-outlined"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="js-form-message form-group">
                                        <label class="input-label" for="signupSrConfirmPassword">{{translate('messages.confirm_password')}}</label>

                                        <div class="input-group input-group-merge">
                                            <input type="password" class="js-toggle-password form-control h--45px"
                                                name="confirmPassword" id="signupSrConfirmPassword"
                                                pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"

                                                placeholder="{{ translate('messages.password_length_placeholder', ['length' => '8+']) }}"
                                                aria-label="8+ characters required"
                                                data-msg="Password does not match the confirm password."
                                                data-hs-toggle-password-options='{
                                                                    "target": [".js-toggle-password-target-1", ".js-toggle-password-target-2"],
                                                                    "defaultClass": "tio-hidden-outlined",
                                                                    "showClass": "tio-visible-outlined",
                                                                    "classChangeTarget": ".js-toggle-passowrd-show-icon-2"
                                                                    }'>
                                            <div class="js-toggle-password-target-2 input-group-append">
                                                <a class="input-group-text" href="javascript:;">
                                                    <i class="js-toggle-passowrd-show-icon-2 tio-visible-outlined"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                        <div class="btn--container justify-content-end">
                            <button id="reset_btn" type="button" class="btn btn--reset">{{translate('messages.reset')}}</button>
                            <button type="submit" class="btn btn--primary"><i class="tio-save-outlined"></i> {{ translate('messages.save') }} {{ translate('messages.info') }}</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script_2')
    <script>
        function readURL(input, viewer) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#' + viewer).attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#customFileEg1").change(function() {
            readURL(this, 'viewer');
        });

        $("#coverImageUpload").change(function() {
            readURL(this, 'coverImageViewer');
        });
    </script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ \App\Models\BusinessSetting::where('key', 'map_api_key')->first()->value }}&callback=initMap&libraries=drawing,places&v=3.45.8">
    </script>
    <script>
        let myLatlng = {
            lat: {{ $restaurant->latitude }},
            lng: {{ $restaurant->longitude }}
        };
        const map = new google.maps.Map(document.getElementById("map"), {
            zoom: 10,
            center: myLatlng,
        });
        var zonePolygon = null;
        let infoWindow = new google.maps.InfoWindow({
            content: "Click the map to get Lat/Lng!",
            position: myLatlng,
        });
        var bounds = new google.maps.LatLngBounds();

        function initMap() {
            // Create the initial InfoWindow.
            new google.maps.Marker({
                position: {
                    lat: {{ $restaurant->latitude }},
                    lng: {{ $restaurant->longitude }}
                },
                map,
                title: "{{ $restaurant->name }}",
            });
            infoWindow.open(map);
            // Create the search box and link it to the UI element.
            const input = document.getElementById("pac-input");
            //console.log(input);
            const searchBox = new google.maps.places.SearchBox(input);
            map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);
            let markers = [];
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
        initMap();

        function get_zone_data(id) {
            $.get({
                url: '{{ url('/') }}/admin/zone/get-coordinates/' + id,
                dataType: 'json',
                success: function(data) {
                    if (zonePolygon) {
                        zonePolygon.setMap(null);
                    }
                    zonePolygon = new google.maps.Polygon({
                        paths: data.coordinates,
                        strokeColor: "#FF0000",
                        strokeOpacity: 0.8,
                        strokeWeight: 2,
                        fillColor: 'white',
                        fillOpacity: 0,
                    });
                    zonePolygon.setMap(map);
                    map.setCenter(data.center);
                    google.maps.event.addListener(zonePolygon, 'click', function(mapsMouseEvent) {
                        infoWindow.close();
                        // Create a new InfoWindow.
                        infoWindow = new google.maps.InfoWindow({
                            position: mapsMouseEvent.latLng,
                            content: JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2),
                        });
                        var coordinates = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2);
                        var coordinates = JSON.parse(coordinates);

                        document.getElementById('latitude').value = coordinates['lat'];
                        document.getElementById('longitude').value = coordinates['lng'];
                        infoWindow.open(map);
                    });
                },
            });
        }
        $(document).on('ready', function() {
            var id = $('#choice_zones').val();
            $.get({
                url: '{{ url('/') }}/admin/zone/get-coordinates/' + id,
                dataType: 'json',
                success: function(data) {
                    if (zonePolygon) {
                        zonePolygon.setMap(null);
                    }
                    zonePolygon = new google.maps.Polygon({
                        paths: data.coordinates,
                        strokeColor: "#FF0000",
                        strokeOpacity: 0.8,
                        strokeWeight: 2,
                        fillColor: 'white',
                        fillOpacity: 0,
                    });
                    zonePolygon.setMap(map);
                    zonePolygon.getPaths().forEach(function(path) {
                        path.forEach(function(latlng) {
                            bounds.extend(latlng);
                            map.fitBounds(bounds);
                        });
                    });
                    map.setCenter(data.center);
                    google.maps.event.addListener(zonePolygon, 'click', function(mapsMouseEvent) {
                        infoWindow.close();
                        // Create a new InfoWindow.
                        infoWindow = new google.maps.InfoWindow({
                            position: mapsMouseEvent.latLng,
                            content: JSON.stringify(mapsMouseEvent.latLng.toJSON(),
                                null, 2),
                        });
                        var coordinates = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null,
                            2);
                        var coordinates = JSON.parse(coordinates);

                        document.getElementById('latitude').value = coordinates['lat'];
                        document.getElementById('longitude').value = coordinates['lng'];
                        infoWindow.open(map);
                    });
                },
            });
        });
    </script>
    <script>
        $(document).on('ready', function() {
            // INITIALIZATION OF SHOW PASSWORD
            // =======================================================
            $('.js-toggle-password').each(function() {
                new HSTogglePassword(this).init()
            });


            // INITIALIZATION OF FORM VALIDATION
            // =======================================================
            $('.js-validate').each(function() {
                $.HSCore.components.HSValidation.init($(this), {
                    rules: {
                        confirmPassword: {
                            equalTo: '#signupSrPassword'
                        }
                    }
                });
            });

            get_zone_data({{ $restaurant->zone_id }});
        });
		$("#vendor_form").on('keydown', function(e){
            if (e.keyCode === 13) {
                e.preventDefault();
            }
        })

    </script>
    <script>
        $('#reset_btn').click(function(){
            $('#name').val("{{ $restaurant->name }}");
            $('#tax').val("{{ $restaurant->tax }}");
            $('#address').val("{{ $restaurant->address }}");
            $('#minimum_delivery_time').val("{{ explode('-', $restaurant->delivery_time)[0] }}");
            $('#maximum_delivery_time').val("{{ explode('-', $restaurant->delivery_time)[1] }}");
            $('#viewer').attr('src', "{{ asset('storage/app/public/restaurant/' . $restaurant->logo) }}");
            $('#customFileEg1').val(null);
            $('#coverImageViewer').attr('src', "{{ asset('storage/app/public/restaurant/cover/' . $restaurant->cover_photo) }}");
            $('#coverImageUpload').val(null);
            $('#choice_zones').val({{$restaurant->zone_id}}).trigger('change');
            $('#f_name').val("{{ $restaurant->vendor->f_name }}");
            $('#l_name').val("{{ $restaurant->vendor->l_name }}");
            $('#phone').val("{{ $restaurant->phone }}");
            $('#email').val("{{ $restaurant->email }}");


        })
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
    </script>
@endpush
