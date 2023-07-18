@extends('layouts.landing.app')
@section('title', translate('messages.restaurant_registration'))
@push('css_or_js')
    <link rel="stylesheet" href="{{ asset('public/assets/landing') }}/css/style.css" />
@endpush
@section('content')
        <!-- Page Header Gap -->
        <div class="h-148px"></div>
        <!-- Page Header Gap -->

    <section class="m-0 landing-inline-1 section-gap">
        <div class="container">
            <!-- Page Header -->
            <div class="step__header">
                <h4 class="title"> {{ translate('messages.Restaurant_registration_application') }}</h4>
                <div class="step__wrapper">
                    <div class="step__item current">
                        <span class="shapes"></span>
                        {{translate('General Information')}}
                    </div>
                    <div class="step__item">
                        <span class="shapes"></span>
                        {{translate('Business Plan')}}
                    </div>
                    <div class="step__item">
                        <span class="shapes"></span>
                        {{translate('Complete')}}
                    </div>
                </div>
            </div>
            <!-- End Page Header -->
            @php($language=\App\Models\BusinessSetting::where('key','language')->first())
            @php($language = $language->value ?? null)
            @php($default_lang = str_replace('_', '-', app()->getLocale()))

            <div class="card __card">
                <div class="card-header py-3 bg-transparent">
                    <h5 class="card-title my-1 text--primary">
                        <span class="card-header-icon">
                            <i class="fa-solid fa-store"></i>
                        </span>
                        {{ translate('messages.restaurant') }}
                        {{ translate('messages.info') }}</h5>
                </div>
                <form class="card-body" action="{{ route('restaurant.store') }}" method="post" enctype="multipart/form-data"
                    class="js-validate">
                    @csrf
                    <div class="row g-4">
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
                        <div class="col-md-6 col-lg-6 col-sm-12">
                            @if ($language)
                            <div class="form-group mb-0 lang_form" id="default-form">
                                <label class="form-label" for="exampleFormControlInput1">{{ translate('messages.restaurant') }}
                                    {{ translate('messages.name') }} ({{translate('messages.default')}})</label>
                                <input type="text" name="name[]" class="form-control"  placeholder="{{ translate('messages.Ex :') }} {{ translate('ABC Company') }}" maxlength="191"  oninvalid="document.getElementById('en-link').click()">
                            </div>
                            <input type="hidden" name="lang[]" value="default">
                                @foreach(json_decode($language) as $lang)
                                    <div class="form-group  mb-0 d-none lang_form" id="{{$lang}}-form">
                                        <label class="form-label" for="exampleFormControlInput1">{{ translate('messages.restaurant') }}
                                    {{ translate('messages.name') }} ({{strtoupper($lang)}})</label>
                                        <input type="text" name="name[]" class="form-control"  placeholder="{{ translate('messages.Ex :') }} {{ translate('ABC Company') }}" maxlength="191" oninvalid="document.getElementById('en-link').click()">
                                    </div>
                                    <input type="hidden" name="lang[]" value="{{$lang}}">
                                @endforeach
                            @else
                                <div class="form-group mb-0">
                                    <label class="form-label" for="exampleFormControlInput1">{{ translate('messages.restaurant') }}
                                    {{ translate('messages.name') }}</label>
                                    <input type="text" name="name[]" class="form-control"  placeholder="{{ translate('messages.Ex :') }} {{ translate('ABC Company') }}"  maxlength="191">
                                </div>
                                <input type="hidden" name="lang[]" value="default">
                            @endif
                        </div>

                        <div class="col-md-6 col-lg-6 col-sm-12">
                            <div class="form-group mb-0">
                                <label class="form-label" for="tax">{{ translate('messages.vat/tax') }} (%)</label>
                                <input type="number" name="tax" class="form-control"
                                    placeholder="{{ translate('messages.vat/tax') }}" min="0" step=".01" required
                                    value="{{ old('tax') }}">
                            </div>
                        </div>



                        <div class="col-md-6 col-lg-6 col-sm-12">
                            <div class="lang_form default-form" >
                                <div class="form-group mb-0">
                                    <label class="form-label" for="address">{{ translate('messages.restaurant') }}
                                        {{ translate('messages.address') }} ({{translate('messages.default')}})</label>
                                    <textarea type="text" name="address[]" class="form-control h--77px"
                                        placeholder="{{ translate('messages.restaurant') }} {{ translate('messages.address') }}"
                                        ></textarea>
                                </div>
                            </div>
                        {{-- </div> --}}


                        @if ($language)

                        @foreach(json_decode($language) as $lang)
                        <div class="d-none lang_form" id="{{$lang}}-form1">
                            <div class="form-group mb-0">
                                <label class="form-label" for="address">{{ translate('messages.restaurant') }}
                                    {{ translate('messages.address') }} ({{strtoupper($lang)}})</label>
                                <textarea type="text" name="address[]" class="form-control h--77px"
                                    placeholder="{{ translate('messages.restaurant') }} {{ translate('messages.address') }}"></textarea>
                            </div>
                        </div>

                        @endforeach
                        @endif
                    </div>
                        <div class="col-sm-3 col-md-2 col-lg-2">
                            <div class="form-group mb-0">
                                <label class="form-label"
                                    for="minimum_delivery_time">{{ translate('messages.min_delivery_time') }}</label>
                                <input type="number" name="minimum_delivery_time" class="form-control" placeholder="30"
                                    pattern="^[0-9]{2}$" required value="{{ old('minimum_delivery_time') }}">
                            </div>
                        </div>
                        <div class="col-sm-3 col-md-2 col-lg-2">
                            <div class="form-group mb-0">
                                <label class="form-label"
                                    for="maximum_delivery_time">{{ translate('messages.max_delivery_time') }}</label>
                                <input type="number" name="maximum_delivery_time" class="form-control" placeholder="40"
                                    pattern="[0-9]{2}" required value="{{ old('maximum_delivery_time') }}">
                            </div>
                        </div>
                        <div class="col-sm-3 col-md-2 col-lg-2">
                            <div class="form-group mb-0">
                            <label class="form-label"
                            for="maximum_delivery_time"></label>
                            <select name="delivery_time_type" required id="delivery_time_type" class="form-control js-select2-custom select2-container--default">
                                <option selected value="min">{{ translate('messages.Minutes') }}</option>
                                <option value="hours" >{{ translate('messages.Hours') }}</option>
                            </select>
                        </div>
                        </div>
                    </div>
                    <div class="mt-29px">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <center>
                                        <img class="landing-initial-1" id="coverImageViewer" src="{{ asset('/public/assets/landing/img/restaurant-cover.png') }}" alt="Product thumbnail" />
                                    </center>
                                    <div class="landing-input-file-grp">
                                        <label for="name" class="form-label pt-3">{{ translate('messages.restaurant') }} {{ translate('messages.cover') }}
                                            {{ translate('messages.photo') }} <span
                                                class="text-danger">({{ translate('messages.ratio') }}
                                                2:1)</span></label>
                                        <label class="custom-file">
                                            <input type="file" name="cover_photo" id="coverImageUpload" class="form-control"
                                                accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <center>
                                        <img class="landing-initial-1" id="logoImageViewer" src="{{ asset('/public/assets/landing/img/restaurant-logo.png') }}" alt="Product thumbnail" />
                                    </center>
                                    <div class="landing-input-file-grp">
                                        <label class="form-label pt-3">{{ translate('messages.restaurant') }}
                                            {{ translate('messages.logo') }}<small class="text-danger"> (
                                                {{ translate('messages.ratio') }}
                                                1:1
                                                )</small></label>
                                        <label class="custom-file">
                                            <input type="file" name="logo" id="customFileEg1" class="form-control"
                                                accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" required>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label mb-2 pb-1" for="cuisine">{{ translate('messages.cuisine') }}
                                </label>
                                <select name="cuisine_ids[]" id="cuisine"  class="form-control js-select2-custom select2-container--default"
                                multiple="multiple"  data-placeholder="{{ translate('messages.select') }} {{ translate('messages.Cuisine') }}" >
                                    <option value="" disabled>{{ translate('messages.select') }}
                                        {{ translate('messages.Cuisine') }}</option>
                                    @foreach (\App\Models\Cuisine::where('status',1 )->get(['id','name']) as $cu)
                                            <option value="{{ $cu->id }}">{{ $cu->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label mb-2 pb-1" for="choice_zones">{{ translate('messages.zone') }}
                                    <span class="input-label-secondary ps-1" title="{{ translate('messages.select_zone_for_map') }}"><img
                                            src="{{ asset('/public/assets/admin/img/info-circle.svg') }}"
                                            alt="{{ translate('messages.select_zone_for_map') }}"></span>
                                        </label>
                                <select name="zone_id" id="choice_zones" required class="form-control js-select2-custom select2-container--default"
                                    data-placeholder="{{ translate('messages.select') }} {{ translate('messages.zone') }}">
                                    <option value="" selected disabled>{{ translate('messages.select') }}
                                        {{ translate('messages.zone') }}</option>
                                    @foreach (\App\Models\Zone::active()->get(['id','name']) as $zone)
                                        @if (isset(auth('admin')->user()->zone_id))
                                            @if (auth('admin')->user()->zone_id == $zone->id)
                                                <option value="{{ $zone->id }}" selected>{{ $zone->name }}</option>
                                            @endif
                                        @else
                                            <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label mb-2 pb-1" for="latitude">{{ translate('messages.latitude') }}<span
                                        class="input-label-secondary ps-1"
                                        title="{{ translate('messages.restaurant_lat_lng_warning') }}"><img
                                            src="{{ asset('/public/assets/admin/img/info-circle.svg') }}"
                                            alt="{{ translate('messages.restaurant_lat_lng_warning') }}"></span></label>
                                <input type="text" id="latitude" name="latitude" class="form-control"
                                    placeholder="{{ translate('messages.Ex :') }} -94.22213" value="{{ old('latitude') }}" required readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label mb-2 pb-1" for="longitude">{{ translate('messages.longitude') }}<span
                                        class="input-label-secondary ps-1"
                                        title="{{ translate('messages.restaurant_lat_lng_warning') }}"><img
                                            src="{{ asset('/public/assets/admin/img/info-circle.svg') }}"
                                            alt="{{ translate('messages.restaurant_lat_lng_warning') }}"></span></label>
                                <input type="text" name="longitude" class="form-control" placeholder="{{ translate('messages.Ex :') }} 103.344322"
                                    id="longitude" value="{{ old('longitude') }}" required readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-12 col-sm-12 mt-4">
                        <input id="pac-input" class="controls rounded landing-initial-2" title="{{translate('messages.search_your_location_here')}}" type="text" placeholder="{{translate('messages.search_here')}}"/>
                        <div id="map"></div>
                    </div>
                    <h5 class="card-title mb-3 text--primary text-capitalize mt-4 pt-1">
                        {{ translate('messages.owner') }} {{ translate('messages.info') }}
                    </h5>
                    <div class="row">
                        <div class="col-md-4 col-lg-4 col-sm-12">
                            <div class="form-group">
                                <label class="form-label" for="f_name">{{ translate('messages.first') }}
                                    {{ translate('messages.name') }}</label>
                                <input type="text" name="f_name" class="form-control"
                                    placeholder="{{ translate('messages.first') }} {{ translate('messages.name') }}"
                                    value="{{ old('f_name') }}" required>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-12">
                            <div class="form-group">
                                <label class="form-label" for="l_name">{{ translate('messages.last') }}
                                    {{ translate('messages.name') }}</label>
                                <input type="text" name="l_name" class="form-control"
                                    placeholder="{{ translate('messages.last') }} {{ translate('messages.name') }}"
                                    value="{{ old('l_name') }}" required>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-12">
                            <div class="form-group">
                                <label class="form-label" for="phone">{{ translate('messages.phone') }}</label>
                                <input type="text" name="phone" class="form-control" placeholder="{{ translate('messages.Ex :') }} 017********"
                                    value="{{ old('phone') }}" required>
                            </div>


                        </div>
                    </div>

                    <h5 class="card-title my-1 text--primary text-capitalize mt-4 pt-1">
                        {{ translate('messages.login') }} {{ translate('messages.info') }}
                    </h5>
                    <div class="row mt-3">
                        <div class="col-md-4 col-sm-12 col-lg-4">
                            <div class="form-group">
                                <label class="form-label" for="email">{{ translate('messages.email') }}</label>
                                <input type="email" name="email" class="form-control" placeholder="{{ translate('messages.Ex :') }} ex@example.com"
                                    value="{{ old('email') }}" required>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 col-lg-4">
                            <div class="form-group">
                                <label class="form-label"
                                    for="exampleInputPassword">{{ translate('messages.password') }}
                                    <span class="input-label-secondary ps-1" title="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"><img src="{{ asset('/public/assets/admin/img/info-circle.svg') }}" alt="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"></span>
                                </label>
                                <input type="password" name="password"
                                    placeholder="{{ translate('messages.password_length_placeholder', ['length' => '8+']) }}"
                                    class="form-control form-control-user"
                                    pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"
                                    minlength="6" id="exampleInputPassword" required
                                    value="{{ old('password') }}">
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 col-lg-4">
                            <div class="form-group">
                                <label class="form-label"
                                    for="signupSrConfirmPassword">{{ translate('messages.confirm_password') }}</label>
                                <input type="password" name="confirm-password" class="form-control form-control-user"
                                    minlength="6" id="exampleRepeatPassword"
                                    placeholder="{{ translate('messages.password_length_placeholder', ['length' => '8+']) }}"
                                    pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="{{ translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters') }}"
                                    required value="{{ old('confirm-password') }}">
                                <div class="pass invalid-feedback">{{ translate('messages.password_not_matched') }}</div>
                            </div>

                        </div>
                    </div>
                    <div class="btn--container justify-content-end mt-3">
                        <button type="reset" class="btn btn--reset">{{ translate('messages.reset') }}</button>
                        <button type="submit" class="btn btn--primary submitBtn">{{ translate('messages.next') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
        <!-- Page Header Gap -->
        <div class="h-148px"></div>
        <!-- Page Header Gap -->

    @endsection
    @push('script_2')
        {{-- <script src="{{ asset('public/assets/admin') }}/js/toastr.js"></script> --}}
        {{-- {!! Toastr::message() !!} --}}

        @if ($errors->any())
            <script>
                @foreach ($errors->all() as $error)
                    toastr.error('{{ $error }}', Error, {
                    CloseButton: true,
                    ProgressBar: true
                    });
                @endforeach
            </script>
        @endif
        <script>
            $('#exampleInputPassword ,#exampleRepeatPassword').on('keyup', function() {
                var pass = $("#exampleInputPassword").val();
                var passRepeat = $("#exampleRepeatPassword").val();
                if (pass == passRepeat) {
                    $('.pass').hide();
                } else {
                    $('.pass').show();
                }
            });


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
                readURL(this, 'logoImageViewer');
            });

            $("#coverImageUpload").change(function() {
                readURL(this, 'coverImageViewer');
            });
        </script>

        <script src="{{ asset('public/assets/admin/js/spartan-multi-image-picker.js') }}"></script>
        <script type="text/javascript">
            $(function() {
                $("#coba").spartanMultiImagePicker({
                    fieldName: 'identity_image[]',
                    maxCount: 5,
                    rowHeight: '120px',
                    groupClassName: 'col-lg-2 col-md-4 col-sm-4 col-6',
                    maxFileSize: '',
                    placeholderImage: {
                        image: '{{ asset('public/assets/admin/img/400x400/img2.jpg') }}',
                        width: '100%'
                    },
                    dropFileLabel: "Drop Here",
                    onAddRow: function(index, file) {

                    },
                    onRenderedPreview: function(index) {

                    },
                    onRemoveRow: function(index) {

                    },
                    onExtensionErr: function(index, file) {
                        toastr.error('{{ translate('messages.please_only_input_png_or_jpg_type_file') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    },
                    onSizeErr: function(index, file) {
                        toastr.error('{{ translate('messages.file_size_too_big') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }
                });
            });
        </script>
        <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
        <script
                src="https://maps.googleapis.com/maps/api/js?key={{ \App\Models\BusinessSetting::where('key', 'map_api_key')->first()->value }}&libraries=drawing,places&v=3.45.8">
        </script>
        <script>
            @php($default_location = \App\Models\BusinessSetting::where('key', 'default_location')->first())
            @php($default_location = $default_location->value ? json_decode($default_location->value, true) : 0)
            let myLatlng = {
                lat: {{ $default_location ? $default_location['lat'] : '23.757989' }},
                lng: {{ $default_location ? $default_location['lng'] : '90.360587' }}
            };
            let map = new google.maps.Map(document.getElementById("map"), {
                zoom: 13,
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
                infoWindow.open(map);
                //get current location block
                infoWindow = new google.maps.InfoWindow();
                // Try HTML5 geolocation.
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            myLatlng = {
                                lat: position.coords.latitude,
                                lng: position.coords.longitude,
                            };
                            infoWindow.setPosition(myLatlng);
                            infoWindow.setContent("Location found.");
                            infoWindow.open(map);
                            map.setCenter(myLatlng);
                        },
                        () => {
                            handleLocationError(true, infoWindow, map.getCenter());
                        }
                    );
                } else {
                    // Browser doesn't support Geolocation
                    handleLocationError(false, infoWindow, map.getCenter());
                }
                //-----end block------
                // Create the search box and link it to the UI element.
            const input = document.getElementById("pac-input");
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

            function handleLocationError(browserHasGeolocation, infoWindow, pos) {
                infoWindow.setPosition(pos);
                infoWindow.setContent(
                    browserHasGeolocation ?
                    "Error: The Geolocation service failed." :
                    "Error: Your browser doesn't support geolocation."
                );
                infoWindow.open(map);
            }
            $('#choice_zones').on('change', function() {
                var id = $(this).val();
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
    $('select').select2({ width: '100%', placeholder: "Select an Option", allowClear: true });
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
            $("#"+lang+"-form1").removeClass('d-none');
            if(lang == '{{$default_lang}}')
            {
                $(".from_part_2").removeClass('d-none');
            }
            if(lang == 'default')
            {
                $(".default-form").removeClass('d-none');
            }
            else
            {
                $(".from_part_2").addClass('d-none');
            }
        });
            </script>
    @endpush
