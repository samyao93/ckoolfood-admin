@extends('layouts.admin.app')

@section('title', translate('Settings'))


@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex flex-wrap justify-content-between align-items-start">
                <h1 class="page-header-title mr-3">
                    <span class="page-header-icon">
                        <img src="{{ asset('public/assets/admin/img/business.png') }}" class="w--20" alt="">
                    </span>
                    <span>
                        {{ translate('messages.business') }} {{ translate('messages.setup') }}
                    </span>
                </h1>
              <div class="d-flex flex-wrap justify-content-end align-items-center flex-grow-1">
                <div class="blinkings active">
                    <i class="tio-info-outined"></i>
                    <div class="business-notes">
                        <h6><img src="{{asset('/public/assets/admin/img/notes.png')}}" alt=""> {{translate('Note')}}</h6>
                        <div>
                            {{translate('Don’t_forget_to_click_the_‘Save_Information’_button_below_to_save_changes.')}}
                        </div>
                    </div>
                </div>
            </div>
            </div>
            @include('admin-views.business-settings.partials.nav-menu')
        </div>
        <!-- End Page Header -->
        <div class="tab-content">
            <div class="tab-pane fade show active" id="business-setup">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="maintainance-mode-toggle-bar d-flex flex-wrap justify-content-between border blue-border rounded align-items-center">
                            @php($config = \App\CentralLogics\Helpers::get_business_settings('maintenance_mode'))
                            <h5 class="card-title text-capitalize mr-3 m-0 text--info">
                                <span class="card-header-icon">
                                    <i class="tio-settings-outlined"></i>
                                </span>
                                <span>
                                    {{ translate('messages.maintenance_mode') }}
                                </span>
                            </h5>
                            <form action="{{ route('admin.maintenance-mode') }}" method="get" id="maintenance_mode_form">
                            </form>
                            <label class="toggle-switch toggle-switch-sm">
                                <input type="checkbox" class="toggle-switch-input"
                                id="maintenance_mode"
                                @if (env('APP_MODE') == 'demo')
                                    onclick="maintenance_mode()"
                                @endif
                                onclick="toogleStatusModal(event,'maintenance_mode','mail-warning.png','mail-success.png','{{translate('Want_to_enable_the_Maintenance_Mode!')}}','{{translate('Want_to_disable_the_Maintenance_Mode!')}}',`<p>{{translate('If_enabled,_all_your_apps_and_customer_website_will_be_temporarily_off.')}}</p>`,`<p>{{translate('If_disabled,_all_your_apps_and_customer_website_will_be_functional.')}}</p>`)"
                                    {{ isset($config) && $config ? 'checked' : '' }}>
                                <span class="toggle-switch-label text">
                                    <span class="toggle-switch-indicator"></span>
                                </span>
                            </label>
                        </div>
                        <p class="mt-2 mb-0">
                            {{ translate('*By_turning_the_‘Maintenance_Mode’_ON,_all_your_apps_and_customer_website_will_be_disabled_temporarily._Only_the_Admin_Panel,_Admin_Landing_Page_&_Restaurant_Panel_will_be_functional.') }}
                        </p>
                    </div>
                </div>
                <form action="{{ route('admin.business-settings.update-setup') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <h4 class="card-title mb-3 mt-1">
                        <img src="{{asset('/public/assets/admin/img/company.png')}}" class="card-header-icon mr-2" alt="">
                        <span>{{ translate('Company Information') }}</span>
                    </h4>
                    <div class="card mb-3">
                        <div class="card-body">
                            <!-- Name Email and Phone -->
                            <div class="row">
                                <div class="col-md-3 col-sm-6">
                                    @php($name = \App\Models\BusinessSetting::where('key', 'business_name')->first())
                                    <div class="form-group">
                                        <label class="form-label" for="exampleFormControlInput1">{{ translate('messages.company') }}
                                            {{ translate('messages.name') }}
                                            &nbsp;
                                        <span class="form-label-secondary text-danger d-flex"
                                        data-toggle="tooltip" data-placement="right"
                                        data-original-title="{{ translate('Write_the_Company_Name.') }}"><img
                                            src="{{ asset('/public/assets/admin/img/info-circle.svg') }}">
                                    </span>
                                        </label>
                                        <input type="text" name="restaurant_name" maxlength="191" value="{{ $name->value ?? '' }}" class="form-control"
                                            placeholder="{{ translate('messages.Ex :') }} ABC Company" required>
                                    </div>
                                </div>
                                @php($email = \App\Models\BusinessSetting::where('key', 'email_address')->first())
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ translate('messages.email') }}</label>
                                        <input type="email" value="{{ $email->value ?? '' }}" name="email"
                                            class="form-control" placeholder="{{ translate('messages.Ex :') }} contact@company.com" required>
                                    </div>
                                </div>
                                @php($phone = \App\Models\BusinessSetting::where('key', 'phone')->first())
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ translate('messages.phone') }}</label>
                                        <input type="text" value="{{ $phone->value ?? '' }}" name="phone"
                                            class="form-control" placeholder="{{ translate('messages.Ex :') }} +9XXX-XXX-XXXX" required>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <label class="input-label text-capitalize d-flex alig-items-center"
                                            for="country">{{ translate('messages.country') }} &nbsp;
                                            <span class="form-label-secondary text-danger d-flex"
                                            data-toggle="tooltip" data-placement="right"
                                            data-original-title="{{ translate('Choose_your_country_from_the_drop-down_menu.') }}"><img
                                                src="{{ asset('/public/assets/admin/img/info-circle.svg') }}">
                                        </span>

                                        </label>
                                        <select id="country" name="country" class="form-control  js-select2-custom">
                                            <option value="AF">Afghanistan</option>
                                            <option value="AX">Åland Islands</option>
                                            <option value="AL">Albania</option>
                                            <option value="DZ">Algeria</option>
                                            <option value="AS">American Samoa</option>
                                            <option value="AD">Andorra</option>
                                            <option value="AO">Angola</option>
                                            <option value="AI">Anguilla</option>
                                            <option value="AQ">Antarctica</option>
                                            <option value="AG">Antigua and Barbuda</option>
                                            <option value="AR">Argentina</option>
                                            <option value="AM">Armenia</option>
                                            <option value="AW">Aruba</option>
                                            <option value="AU">Australia</option>
                                            <option value="AT">Austria</option>
                                            <option value="AZ">Azerbaijan</option>
                                            <option value="BS">Bahamas</option>
                                            <option value="BH">Bahrain</option>
                                            <option value="BD">Bangladesh</option>
                                            <option value="BB">Barbados</option>
                                            <option value="BY">Belarus</option>
                                            <option value="BE">Belgium</option>
                                            <option value="BZ">Belize</option>
                                            <option value="BJ">Benin</option>
                                            <option value="BM">Bermuda</option>
                                            <option value="BT">Bhutan</option>
                                            <option value="BO">Bolivia, Plurinational State of</option>
                                            <option value="BQ">Bonaire, Sint Eustatius and Saba</option>
                                            <option value="BA">Bosnia and Herzegovina</option>
                                            <option value="BW">Botswana</option>
                                            <option value="BV">Bouvet Island</option>
                                            <option value="BR">Brazil</option>
                                            <option value="IO">British Indian Ocean Territory</option>
                                            <option value="BN">Brunei Darussalam</option>
                                            <option value="BG">Bulgaria</option>
                                            <option value="BF">Burkina Faso</option>
                                            <option value="BI">Burundi</option>
                                            <option value="KH">Cambodia</option>
                                            <option value="CM">Cameroon</option>
                                            <option value="CA">Canada</option>
                                            <option value="CV">Cape Verde</option>
                                            <option value="KY">Cayman Islands</option>
                                            <option value="CF">Central African Republic</option>
                                            <option value="TD">Chad</option>
                                            <option value="CL">Chile</option>
                                            <option value="CN">China</option>
                                            <option value="CX">Christmas Island</option>
                                            <option value="CC">Cocos (Keeling) Islands</option>
                                            <option value="CO">Colombia</option>
                                            <option value="KM">Comoros</option>
                                            <option value="CG">Congo</option>
                                            <option value="CD">Congo, the Democratic Republic of the</option>
                                            <option value="CK">Cook Islands</option>
                                            <option value="CR">Costa Rica</option>
                                            <option value="CI">Côte d'Ivoire</option>
                                            <option value="HR">Croatia</option>
                                            <option value="CU">Cuba</option>
                                            <option value="CW">Curaçao</option>
                                            <option value="CY">Cyprus</option>
                                            <option value="CZ">Czech Republic</option>
                                            <option value="DK">Denmark</option>
                                            <option value="DJ">Djibouti</option>
                                            <option value="DM">Dominica</option>
                                            <option value="DO">Dominican Republic</option>
                                            <option value="EC">Ecuador</option>
                                            <option value="EG">Egypt</option>
                                            <option value="SV">El Salvador</option>
                                            <option value="GQ">Equatorial Guinea</option>
                                            <option value="ER">Eritrea</option>
                                            <option value="EE">Estonia</option>
                                            <option value="ET">Ethiopia</option>
                                            <option value="FK">Falkland Islands (Malvinas)</option>
                                            <option value="FO">Faroe Islands</option>
                                            <option value="FJ">Fiji</option>
                                            <option value="FI">Finland</option>
                                            <option value="FR">France</option>
                                            <option value="GF">French Guiana</option>
                                            <option value="PF">French Polynesia</option>
                                            <option value="TF">French Southern Territories</option>
                                            <option value="GA">Gabon</option>
                                            <option value="GM">Gambia</option>
                                            <option value="GE">Georgia</option>
                                            <option value="DE">Germany</option>
                                            <option value="GH">Ghana</option>
                                            <option value="GI">Gibraltar</option>
                                            <option value="GR">Greece</option>
                                            <option value="GL">Greenland</option>
                                            <option value="GD">Grenada</option>
                                            <option value="GP">Guadeloupe</option>
                                            <option value="GU">Guam</option>
                                            <option value="GT">Guatemala</option>
                                            <option value="GG">Guernsey</option>
                                            <option value="GN">Guinea</option>
                                            <option value="GW">Guinea-Bissau</option>
                                            <option value="GY">Guyana</option>
                                            <option value="HT">Haiti</option>
                                            <option value="HM">Heard Island and McDonald Islands</option>
                                            <option value="VA">Holy See (Vatican City State)</option>
                                            <option value="HN">Honduras</option>
                                            <option value="HK">Hong Kong</option>
                                            <option value="HU">Hungary</option>
                                            <option value="IS">Iceland</option>
                                            <option value="IN">India</option>
                                            <option value="ID">Indonesia</option>
                                            <option value="IR">Iran, Islamic Republic of</option>
                                            <option value="IQ">Iraq</option>
                                            <option value="IE">Ireland</option>
                                            <option value="IM">Isle of Man</option>
                                            <option value="IL">Israel</option>
                                            <option value="IT">Italy</option>
                                            <option value="JM">Jamaica</option>
                                            <option value="JP">Japan</option>
                                            <option value="JE">Jersey</option>
                                            <option value="JO">Jordan</option>
                                            <option value="KZ">Kazakhstan</option>
                                            <option value="KE">Kenya</option>
                                            <option value="KI">Kiribati</option>
                                            <option value="KP">Korea, Democratic People's Republic of</option>
                                            <option value="KR">Korea, Republic of</option>
                                            <option value="KW">Kuwait</option>
                                            <option value="KG">Kyrgyzstan</option>
                                            <option value="LA">Lao People's Democratic Republic</option>
                                            <option value="LV">Latvia</option>
                                            <option value="LB">Lebanon</option>
                                            <option value="LS">Lesotho</option>
                                            <option value="LR">Liberia</option>
                                            <option value="LY">Libya</option>
                                            <option value="LI">Liechtenstein</option>
                                            <option value="LT">Lithuania</option>
                                            <option value="LU">Luxembourg</option>
                                            <option value="MO">Macao</option>
                                            <option value="MK">Macedonia, the former Yugoslav Republic of</option>
                                            <option value="MG">Madagascar</option>
                                            <option value="MW">Malawi</option>
                                            <option value="MY">Malaysia</option>
                                            <option value="MV">Maldives</option>
                                            <option value="ML">Mali</option>
                                            <option value="MT">Malta</option>
                                            <option value="MH">Marshall Islands</option>
                                            <option value="MQ">Martinique</option>
                                            <option value="MR">Mauritania</option>
                                            <option value="MU">Mauritius</option>
                                            <option value="YT">Mayotte</option>
                                            <option value="MX">Mexico</option>
                                            <option value="FM">Micronesia, Federated States of</option>
                                            <option value="MD">Moldova, Republic of</option>
                                            <option value="MC">Monaco</option>
                                            <option value="MN">Mongolia</option>
                                            <option value="ME">Montenegro</option>
                                            <option value="MS">Montserrat</option>
                                            <option value="MA">Morocco</option>
                                            <option value="MZ">Mozambique</option>
                                            <option value="MM">Myanmar</option>
                                            <option value="NA">Namibia</option>
                                            <option value="NR">Nauru</option>
                                            <option value="NP">Nepal</option>
                                            <option value="NL">Netherlands</option>
                                            <option value="NC">New Caledonia</option>
                                            <option value="NZ">New Zealand</option>
                                            <option value="NI">Nicaragua</option>
                                            <option value="NE">Niger</option>
                                            <option value="NG">Nigeria</option>
                                            <option value="NU">Niue</option>
                                            <option value="NF">Norfolk Island</option>
                                            <option value="MP">Northern Mariana Islands</option>
                                            <option value="NO">Norway</option>
                                            <option value="OM">Oman</option>
                                            <option value="PK">Pakistan</option>
                                            <option value="PW">Palau</option>
                                            <option value="PS">Palestinian Territory, Occupied</option>
                                            <option value="PA">Panama</option>
                                            <option value="PG">Papua New Guinea</option>
                                            <option value="PY">Paraguay</option>
                                            <option value="PE">Peru</option>
                                            <option value="PH">Philippines</option>
                                            <option value="PN">Pitcairn</option>
                                            <option value="PL">Poland</option>
                                            <option value="PT">Portugal</option>
                                            <option value="PR">Puerto Rico</option>
                                            <option value="QA">Qatar</option>
                                            <option value="RE">Réunion</option>
                                            <option value="RO">Romania</option>
                                            <option value="RU">Russian Federation</option>
                                            <option value="RW">Rwanda</option>
                                            <option value="BL">Saint Barthélemy</option>
                                            <option value="SH">Saint Helena, Ascension and Tristan da Cunha</option>
                                            <option value="KN">Saint Kitts and Nevis</option>
                                            <option value="LC">Saint Lucia</option>
                                            <option value="MF">Saint Martin (French part)</option>
                                            <option value="PM">Saint Pierre and Miquelon</option>
                                            <option value="VC">Saint Vincent and the Grenadines</option>
                                            <option value="WS">Samoa</option>
                                            <option value="SM">San Marino</option>
                                            <option value="ST">Sao Tome and Principe</option>
                                            <option value="SA">Saudi Arabia</option>
                                            <option value="SN">Senegal</option>
                                            <option value="RS">Serbia</option>
                                            <option value="SC">Seychelles</option>
                                            <option value="SL">Sierra Leone</option>
                                            <option value="SG">Singapore</option>
                                            <option value="SX">Sint Maarten (Dutch part)</option>
                                            <option value="SK">Slovakia</option>
                                            <option value="SI">Slovenia</option>
                                            <option value="SB">Solomon Islands</option>
                                            <option value="SO">Somalia</option>
                                            <option value="ZA">South Africa</option>
                                            <option value="GS">South Georgia and the South Sandwich Islands</option>
                                            <option value="SS">South Sudan</option>
                                            <option value="ES">Spain</option>
                                            <option value="LK">Sri Lanka</option>
                                            <option value="SD">Sudan</option>
                                            <option value="SR">Suriname</option>
                                            <option value="SJ">Svalbard and Jan Mayen</option>
                                            <option value="SZ">Swaziland</option>
                                            <option value="SE">Sweden</option>
                                            <option value="CH">Switzerland</option>
                                            <option value="SY">Syrian Arab Republic</option>
                                            <option value="TW">Taiwan, Province of China</option>
                                            <option value="TJ">Tajikistan</option>
                                            <option value="TZ">Tanzania, United Republic of</option>
                                            <option value="TH">Thailand</option>
                                            <option value="TL">Timor-Leste</option>
                                            <option value="TG">Togo</option>
                                            <option value="TK">Tokelau</option>
                                            <option value="TO">Tonga</option>
                                            <option value="TT">Trinidad and Tobago</option>
                                            <option value="TN">Tunisia</option>
                                            <option value="TR">Turkey</option>
                                            <option value="TM">Turkmenistan</option>
                                            <option value="TC">Turks and Caicos Islands</option>
                                            <option value="TV">Tuvalu</option>
                                            <option value="UG">Uganda</option>
                                            <option value="UA">Ukraine</option>
                                            <option value="AE">United Arab Emirates</option>
                                            <option value="GB">United Kingdom</option>
                                            <option value="US">United States</option>
                                            <option value="UM">United States Minor Outlying Islands</option>
                                            <option value="UY">Uruguay</option>
                                            <option value="UZ">Uzbekistan</option>
                                            <option value="VU">Vanuatu</option>
                                            <option value="VE">Venezuela, Bolivarian Republic of</option>
                                            <option value="VN">Viet Nam</option>
                                            <option value="VG">Virgin Islands, British</option>
                                            <option value="VI">Virgin Islands, U.S.</option>
                                            <option value="WF">Wallis and Futuna</option>
                                            <option value="EH">Western Sahara</option>
                                            <option value="YE">Yemen</option>
                                            <option value="ZM">Zambia</option>
                                            <option value="ZW">Zimbabwe</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!-- Map and Address -->
                            <div class="row gy-3">
                                <div class="col-lg-6">
                                    @php($address = \App\Models\BusinessSetting::where('key', 'address')->first())
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ translate('messages.address') }}</label>
                                        <textarea type="text" id="address" name="address" class="form-control h--90px" placeholder="{{ translate('messages.Ex :') }} House#94, Road#8, Abc City" rows="1"
                                            required>{{ $address->value ?? '' }}</textarea>
                                    </div>
                                    @php($default_location = \App\Models\BusinessSetting::where('key', 'default_location')->first())
                                    @php($default_location = $default_location->value ? json_decode($default_location->value, true) : 0)
                                    <div class="row g-4">
                                        <div class="col-sm-6">
                                            <div class="form-group mb-0">
                                                <label class="input-label text-capitalize d-flex alig-items-center"
                                                    for="latitude">{{ translate('messages.latitude') }}<span class="input-label-secondary"
                                                            data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('messages.Click_on_the_map_to_see_your_location’s_latitude') }}"><img
                                                            src="{{ asset('/public/assets/admin/img/info-circle.svg') }}"
                                                            alt="{{ translate('messages.Click_on_the_map_to_see_your_location’s_latitude') }}"></span></label>
                                                <input type="text" id="latitude" name="latitude" class="form-control d-inline"
                                                    placeholder="{{ translate('messages.Ex :') }} -94.22213"
                                                    value="{{ $default_location ? $default_location['lat'] : 0 }}" required readonly>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group mb-0">
                                                <label class="input-label text-capitalize d-flex alig-items-center"
                                                    for="longitude">{{ translate('messages.longitude') }}<span class="input-label-secondary"
                                                            data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('messages.Click_on_the_map_to_see_your_location’s_longitude') }}"><img
                                                            src="{{ asset('/public/assets/admin/img/info-circle.svg') }}"
                                                            alt="{{ translate('messages.Click_on_the_map_to_see_your_location’s_longitude') }}"></span></label>
                                                <input type="text" name="longitude" class="form-control" placeholder="{{ translate('messages.Ex :') }} 103.344322"
                                                    id="longitude" value="{{ $default_location ? $default_location['lng'] : 0 }}"
                                                    required readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex __gap-12px mt-4">
                                        <label class="__custom-upload-img mr-lg-5">
                                            @php($logo = \App\Models\BusinessSetting::where('key', 'logo')->first())
                                            @php($logo = $logo->value ?? '')
                                            <label class="form-label">
                                                {{ translate('logo') }} <span class="text--primary">({{ translate('3:1') }})</span>
                                            </label>
                                            <center>
                                                <img class="img--vertical" id="viewer"
                                                    onerror="this.src='{{ asset('public/assets/admin/img/upload-img.png') }}'"
                                                    src="{{ asset('storage/app/public/business/' . $logo) }}"
                                                    alt="logo image" />
                                            </center>
                                            <input type="file" name="logo" id="customFileEg1" class="custom-file-input"
                                                accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                        </label>

                                        <label class="__custom-upload-img">
                                            @php($icon = \App\Models\BusinessSetting::where('key', 'icon')->first())
                                            @php($icon = $icon->value ?? '')
                                            <label class="form-label">
                                                {{ translate('Favicon') }}  <span class="text--primary">({{ translate('1:1') }})</span>
                                            </label>
                                            <center>
                                                <img class="img--110" id="iconViewer"
                                                    onerror="this.src='{{ asset('public/assets/admin/img/upload-img.png') }}'"
                                                    src="{{ asset('storage/app/public/business/' . $icon) }}"
                                                    alt="Fav icon" />
                                            </center>
                                            <input type="file" name="icon" id="favIconUpload" class="custom-file-input"
                                                accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                        </label>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mt-4">
                                        <input id="pac-input" class="controls rounded overflow-hidden initial-9"
                                            title="{{ translate('messages.search_your_location_here') }}" type="text"
                                            placeholder="{{ translate('messages.search_here') }}" />
                                        <div id="location_map_canvas" class="overflow-hidden rounded height-192px"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h4 class="card-title mb-3 pt-2">
                        <span class="card-header-icon mr-2">
                            <i class="tio-settings-outlined"></i>
                        </span>
                        <span>{{ translate('General Settings') }}</span>
                    </h4>

                    <div class="card mb-3">
                        <div class="card-body pb-0">
                            <div class="row">
                                <div class="col-sm-6 col-md-3">
                                    @php($tz = \App\Models\BusinessSetting::where('key', 'timezone')->first())
                                    @php($tz = $tz ? $tz->value : 0)
                                    <div class="form-group">
                                        <label
                                            class="input-label text-capitalize d-flex alig-items-center">{{ translate('messages.time_zone') }}</label>
                                        <select name="timezone" class="form-control js-select2-custom">
                                            <option value="UTC" {{ $tz ? ($tz == '' ? 'selected' : '') : '' }}>UTC</option>
                                            <option value="Etc/GMT+12" {{ $tz ? ($tz == 'Etc/GMT+12' ? 'selected' : '') : '' }}>(GMT-12:00) International Date Line West</option>
                                            <option value="Pacific/Midway" {{ $tz ? ($tz == 'Pacific/Midway' ? 'selected' : '') : '' }}> (GMT-11:00) Midway Island, Samoa</option>
                                            <option value="Pacific/Honolulu" {{ $tz ? ($tz == 'Pacific/Honolulu' ? 'selected' : '') : '' }}> (GMT-10:00) Hawaii</option>
                                            <option value="US/Alaska" {{ $tz ? ($tz == 'US/Alaska' ? 'selected' : '') : '' }}> (GMT-09:00) Alaska</option>
                                            <option value="America/Los_Angeles" {{ $tz ? ($tz == 'America/Los_Angeles' ? 'selected' : '') : '' }}>(GMT-08:00) Pacific Time (US & Canada)</option>
                                            <option value="America/Tijuana" {{ $tz ? ($tz == 'America/Tijuana' ? 'selected' : '') : '' }}> (GMT-08:00) Tijuana, Baja California</option>
                                            <option value="US/Arizona" {{ $tz ? ($tz == 'US/Arizona' ? 'selected' : '') : '' }}> (GMT-07:00) Arizona</option>
                                            <option value="America/Chihuahua" {{ $tz ? ($tz == 'America/Chihuahua' ? 'selected' : '') : '' }}>(GMT-07:00) Chihuahua, La Paz, Mazatlan</option>
                                            <option value="US/Mountain" {{ $tz ? ($tz == 'US/Mountain' ? 'selected' : '') : '' }}>(GMT-07:00) Mountain Time (US & Canada)</option>
                                            <option value="America/Managua" {{ $tz ? ($tz == 'America/Managua' ? 'selected' : '') : '' }}> (GMT-06:00) Central America</option>
                                            <option value="US/Central" {{ $tz ? ($tz == 'US/Central' ? 'selected' : '') : '' }}> (GMT-06:00) Central Time (US & Canada)</option>
                                            <option value="America/Mexico_City" {{ $tz ? ($tz == 'America/Mexico_City' ? 'selected' : '') : '' }}>(GMT-06:00) Guadalajara, Mexico City, Monterrey</option>
                                            <option value="Canada/Saskatchewan" {{ $tz ? ($tz == 'Canada/Saskatchewan' ? 'selected' : '') : '' }}>(GMT-06:00) Saskatchewan
                                            </option>
                                            <option value="America/Bogota" {{ $tz ? ($tz == 'America/Bogota' ? 'selected' : '') : '' }}> (GMT-05:00) Bogota, Lima, Quito, Rio Branco</option>
                                            <option value="US/Eastern" {{ $tz ? ($tz == 'US/Eastern' ? 'selected' : '') : '' }}> (GMT-05:00) Eastern Time (US & Canada)</option>
                                            <option value="US/East-Indiana" {{ $tz ? ($tz == 'US/East-Indiana' ? 'selected' : '') : '' }}> (GMT-05:00) Indiana (East)</option>
                                            <option value="Canada/Atlantic" {{ $tz ? ($tz == 'Canada/Atlantic' ? 'selected' : '') : '' }}> (GMT-04:00) Atlantic Time (Canada)</option>
                                            <option value="America/Caracas" {{ $tz ? ($tz == 'America/Caracas' ? 'selected' : '') : '' }}> (GMT-04:00) Caracas, La Paz</option>
                                            <option value="America/Manaus" {{ $tz ? ($tz == 'America/Manaus' ? 'selected' : '') : '' }}> (GMT-04:00) Manaus</option>
                                            <option value="America/Santiago" {{ $tz ? ($tz == 'America/Santiago' ? 'selected' : '') : '' }}> (GMT-04:00) Santiago</option>
                                            <option value="Canada/Newfoundland" {{ $tz ? ($tz == 'Canada/Newfoundland' ? 'selected' : '') : '' }}>(GMT-03:30) Newfoundland
                                            </option>
                                            <option value="America/Sao_Paulo" {{ $tz ? ($tz == 'America/Sao_Paulo' ? 'selected' : '') : '' }}>(GMT-03:00) Brasilia</option>
                                            <option value="America/Argentina/Buenos_Aires" {{ $tz ? ($tz == 'America/Argentina/Buenos_Aires' ? 'selected' : '') : '' }}> (GMT-03:00) Buenos Aires, Georgetown</option>
                                            <option value="America/Godthab" {{ $tz ? ($tz == 'America/Godthab' ? 'selected' : '') : '' }}> (GMT-03:00) Greenland</option>
                                            <option value="America/Montevideo" {{ $tz ? ($tz == 'America/Montevideo' ? 'selected' : '') : '' }}>(GMT-03:00) Montevideo
                                            </option>
                                            <option value="America/Noronha" {{ $tz ? ($tz == 'America/Noronha' ? 'selected' : '') : '' }}> (GMT-02:00) Mid-Atlantic</option>
                                            <option value="Atlantic/Cape_Verde" {{ $tz ? ($tz == 'Atlantic/Cape_Verde' ? 'selected' : '') : '' }}>(GMT-01:00) Cape Verde Is.
                                            </option>
                                            <option value="Atlantic/Azores" {{ $tz ? ($tz == 'Atlantic/Azores' ? 'selected' : '') : '' }}> (GMT-01:00) Azores</option>
                                            <option value="Africa/Casablanca" {{ $tz ? ($tz == 'Africa/Casablanca' ? 'selected' : '') : '' }}>(GMT+00:00) Casablanca, Monrovia, Reykjavik</option>
                                            <option value="Etc/Greenwich" {{ $tz ? ($tz == 'Etc/Greenwich' ? 'selected' : '') : '' }}> (GMT+00:00) Greenwich Mean Time : Dublin, Edinburgh, Lisbon, London</option>
                                            <option value="Europe/Amsterdam" {{ $tz ? ($tz == 'Europe/Amsterdam' ? 'selected' : '') : '' }}> (GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna</option>
                                            <option value="Europe/Belgrade" {{ $tz ? ($tz == 'Europe/Belgrade' ? 'selected' : '') : '' }}> (GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague</option>
                                            <option value="Europe/Brussels" {{ $tz ? ($tz == 'Europe/Brussels' ? 'selected' : '') : '' }}> (GMT+01:00) Brussels, Copenhagen, Madrid, Paris</option>
                                            <option value="Europe/Sarajevo" {{ $tz ? ($tz == 'Europe/Sarajevo' ? 'selected' : '') : '' }}> (GMT+01:00) Sarajevo, Skopje, Warsaw, Zagreb</option>
                                            <option value="Africa/Lagos" {{ $tz ? ($tz == 'Africa/Lagos' ? 'selected' : '') : '' }}> (GMT+01:00) West Central Africa</option>
                                            <option value="Asia/Amman" {{ $tz ? ($tz == 'Asia/Amman' ? 'selected' : '') : '' }}> (GMT+02:00) Amman</option>
                                            <option value="Europe/Athens" {{ $tz ? ($tz == 'Europe/Athens' ? 'selected' : '') : '' }}> (GMT+02:00) Athens, Bucharest, Istanbul</option>
                                            <option value="Asia/Beirut" {{ $tz ? ($tz == 'Asia/Beirut' ? 'selected' : '') : '' }}>(GMT+02:00) Beirut</option>
                                            <option value="Africa/Cairo" {{ $tz ? ($tz == 'Africa/Cairo' ? 'selected' : '') : '' }}> (GMT+02:00) Cairo</option>
                                            <option value="Africa/Harare" {{ $tz ? ($tz == 'Africa/Harare' ? 'selected' : '') : '' }}> (GMT+02:00) Harare, Pretoria</option>
                                            <option value="Europe/Helsinki" {{ $tz ? ($tz == 'Europe/Helsinki' ? 'selected' : '') : '' }}> (GMT+02:00) Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius</option>
                                            <option value="Asia/Jerusalem" {{ $tz ? ($tz == 'Asia/Jerusalem' ? 'selected' : '') : '' }}> (GMT+02:00) Jerusalem</option>
                                            <option value="Europe/Minsk" {{ $tz ? ($tz == 'Europe/Minsk' ? 'selected' : '') : '' }}> (GMT+02:00) Minsk</option>
                                            <option value="Africa/Windhoek" {{ $tz ? ($tz == 'Africa/Windhoek' ? 'selected' : '') : '' }}> (GMT+02:00) Windhoek</option>
                                            <option value="Asia/Kuwait" {{ $tz ? ($tz == 'Asia/Kuwait' ? 'selected' : '') : '' }}>(GMT+03:00) Kuwait, Riyadh, Baghdad</option>
                                            <option value="Europe/Moscow" {{ $tz ? ($tz == 'Europe/Moscow' ? 'selected' : '') : '' }}> (GMT+03:00) Moscow, St. Petersburg, Volgograd</option>
                                            <option value="Africa/Nairobi" {{ $tz ? ($tz == 'Africa/Nairobi' ? 'selected' : '') : '' }}> (GMT+03:00) Nairobi</option>
                                            <option value="Asia/Tbilisi" {{ $tz ? ($tz == 'Asia/Tbilisi' ? 'selected' : '') : '' }}> (GMT+03:00) Tbilisi</option>
                                            <option value="Asia/Tehran" {{ $tz ? ($tz == 'Asia/Tehran' ? 'selected' : '') : '' }}>(GMT+03:30) Tehran</option>
                                            <option value="Asia/Muscat" {{ $tz ? ($tz == 'Asia/Muscat' ? 'selected' : '') : '' }}>(GMT+04:00) Abu Dhabi, Muscat</option>
                                            <option value="Asia/Baku" {{ $tz ? ($tz == 'Asia/Baku' ? 'selected' : '') : '' }}> (GMT+04:00) Baku</option>
                                            <option value="Asia/Yerevan" {{ $tz ? ($tz == 'Asia/Yerevan' ? 'selected' : '') : '' }}> (GMT+04:00) Yerevan</option>
                                            <option value="Asia/Kabul" {{ $tz ? ($tz == 'Asia/Kabul' ? 'selected' : '') : '' }}> (GMT+04:30) Kabul</option>
                                            <option value="Asia/Yekaterinburg" {{ $tz ? ($tz == 'Asia/Yekaterinburg' ? 'selected' : '') : '' }}>(GMT+05:00) Yekaterinburg
                                            </option>
                                            <option value="Asia/Karachi" {{ $tz ? ($tz == 'Asia/Karachi' ? 'selected' : '') : '' }}> (GMT+05:00) Islamabad, Karachi, Tashkent</option>
                                            <option value="Asia/Calcutta" {{ $tz ? ($tz == 'Asia/Calcutta' ? 'selected' : '') : '' }}> (GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi</option>
                                            <option value="Asia/Calcutta"  {{ $tz ? ($tz == 'Asia/Calcutta' ? 'selected' : '') : '' }}>(GMT+05:30) Sri Jayawardenapura</option>
                                            <option value="Asia/Katmandu" {{ $tz ? ($tz == 'Asia/Katmandu' ? 'selected' : '') : '' }}> (GMT+05:45) Kathmandu</option>
                                            <option value="Asia/Almaty" {{ $tz ? ($tz == 'Asia/Almaty' ? 'selected' : '') : '' }}>(GMT+06:00) Almaty, Novosibirsk</option>
                                            <option value="Asia/Dhaka" {{ $tz ? ($tz == 'Asia/Dhaka' ? 'selected' : '') : '' }}> (GMT+06:00) Astana, Dhaka</option>
                                            <option value="Asia/Rangoon" {{ $tz ? ($tz == 'Asia/Rangoon' ? 'selected' : '') : '' }}> (GMT+06:30) Yangon (Rangoon)</option>
                                            <option value="Asia/Bangkok" {{ $tz ? ($tz == 'Asia/Bangkok' ? 'selected' : '') : '' }}> (GMT+07:00) Bangkok, Hanoi, Jakarta</option>
                                            <option value="Asia/Krasnoyarsk" {{ $tz ? ($tz == 'Asia/Krasnoyarsk' ? 'selected' : '') : '' }}> (GMT+07:00) Krasnoyarsk</option>
                                            <option value="Asia/Hong_Kong" {{ $tz ? ($tz == 'Asia/Hong_Kong' ? 'selected' : '') : '' }}> (GMT+08:00) Beijing, Chongqing, Hong Kong, Urumqi</option>
                                            <option value="Asia/Kuala_Lumpur" {{ $tz ? ($tz == 'Asia/Kuala_Lumpur' ? 'selected' : '') : '' }}>(GMT+08:00) Kuala Lumpur, Singapore</option>
                                            <option value="Asia/Irkutsk" {{ $tz ? ($tz == 'Asia/Irkutsk' ? 'selected' : '') : '' }}> (GMT+08:00) Irkutsk, Ulaan Bataar</option>
                                            <option value="Australia/Perth" {{ $tz ? ($tz == 'Australia/Perth' ? 'selected' : '') : '' }}> (GMT+08:00) Perth</option>
                                            <option value="Asia/Taipei" {{ $tz ? ($tz == 'Asia/Taipei' ? 'selected' : '') : '' }}>(GMT+08:00) Taipei</option>
                                            <option value="Asia/Tokyo" {{ $tz ? ($tz == 'Asia/Tokyo' ? 'selected' : '') : '' }}> (GMT+09:00) Osaka, Sapporo, Tokyo</option>
                                            <option value="Asia/Seoul" {{ $tz ? ($tz == 'Asia/Seoul' ? 'selected' : '') : '' }}> (GMT+09:00) Seoul</option>
                                            <option value="Asia/Yakutsk" {{ $tz ? ($tz == 'Asia/Yakutsk' ? 'selected' : '') : '' }}> (GMT+09:00) Yakutsk</option>
                                            <option value="Australia/Adelaide" {{ $tz ? ($tz == 'Australia/Adelaide' ? 'selected' : '') : '' }}>(GMT+09:30) Adelaide
                                            </option>
                                            <option value="Australia/Darwin" {{ $tz ? ($tz == 'Australia/Darwin' ? 'selected' : '') : '' }}> (GMT+09:30) Darwin</option>
                                            <option value="Australia/Brisbane" {{ $tz ? ($tz == 'Australia/Brisbane' ? 'selected' : '') : '' }}>(GMT+10:00) Brisbane
                                            </option>
                                            <option value="Australia/Canberra" {{ $tz ? ($tz == 'Australia/Canberra' ? 'selected' : '') : '' }}>(GMT+10:00) Canberra, Melbourne, Sydney</option>
                                            <option value="Australia/Hobart" {{ $tz ? ($tz == 'Australia/Hobart' ? 'selected' : '') : '' }}> (GMT+10:00) Hobart</option>
                                            <option value="Pacific/Guam" {{ $tz ? ($tz == 'Pacific/Guam' ? 'selected' : '') : '' }}> (GMT+10:00) Guam, Port Moresby</option>
                                            <option value="Asia/Vladivostok" {{ $tz ? ($tz == 'Asia/Vladivostok' ? 'selected' : '') : '' }}> (GMT+10:00) Vladivostok</option>
                                            <option value="Asia/Magadan" {{ $tz ? ($tz == 'Asia/Magadan' ? 'selected' : '') : '' }}> (GMT+11:00) Magadan, Solomon Is., New Caledonia</option>
                                            <option value="Pacific/Auckland" {{ $tz ? ($tz == 'Pacific/Auckland' ? 'selected' : '') : '' }}> (GMT+12:00) Auckland, Wellington</option>
                                            <option value="Pacific/Fiji" {{ $tz ? ($tz == 'Pacific/Fiji' ? 'selected' : '') : '' }}> (GMT+12:00) Fiji, Kamchatka, Marshall Is.</option>
                                            <option value="Pacific/Tongatapu" {{ $tz ? ($tz == 'Pacific/Tongatapu' ? 'selected' : '') : '' }}>(GMT+13:00) Nuku'alofa
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    @php($tf = \App\Models\BusinessSetting::where('key', 'timeformat')->first())
                                    @php($tf = $tf ? $tf->value : '24')
                                    <div class="form-group">
                                        <label
                                            class="input-label text-capitalize d-flex alig-items-center">{{ translate('messages.time_format') }}</label>
                                        <select name="time_format" class="form-control">
                                            <option value="12" {{ $tf == '12' ? 'selected' : '' }}>
                                                {{ translate('messages.12_hour') }}
                                            </option>
                                            <option value="24" {{ $tf == '24' ? 'selected' : '' }}>
                                                {{ translate('messages.24_hour') }}
                                            </option>
                                        </select>
                                    </div>

                                </div>
                                <div class="col-sm-6 col-md-3">
                                    @php($currency_code = \App\Models\BusinessSetting::where('key', 'currency')->first())
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ translate('messages.currency') }} ({{ \App\CentralLogics\Helpers::currency_symbol() }})</label>
                                        <select name="currency" class="form-control js-select2-custom">
                                            @foreach (\App\Models\Currency::orderBy('currency_code')->get() as $currency)
                                                <option value="{{ $currency['currency_code'] }}"
                                                    {{ $currency_code ? ($currency_code->value == $currency['currency_code'] ? 'selected' : '') : '' }}>
                                                    {{ $currency['currency_code'] }} ( {{ $currency['currency_symbol'] }} )
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    @php($currency_symbol_position = \App\Models\BusinessSetting::where('key', 'currency_symbol_position')->first())
                                    <div class="form-group">
                                        <label class="input-label text-capitalize d-flex alig-items-center"
                                            for="currency_symbol_position">{{ translate('messages.currency_symbol_positon') }}</label>
                                        <select name="currency_symbol_position" class="form-control js-select2-custom"
                                            id="currency_symbol_position">
                                            <option value="left"
                                                {{ $currency_symbol_position ? ($currency_symbol_position->value == 'left' ? 'selected' : '') : '' }}>
                                                {{ translate('messages.left') }}
                                                ({{ \App\CentralLogics\Helpers::currency_symbol() }}123)
                                            </option>
                                            <option value="right"
                                                {{ $currency_symbol_position ? ($currency_symbol_position->value == 'right' ? 'selected' : '') : '' }}>
                                                {{ translate('messages.right') }}
                                                (123{{ \App\CentralLogics\Helpers::currency_symbol() }})
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    @php($digit_after_decimal_point = \App\Models\BusinessSetting::where('key', 'digit_after_decimal_point')->first())
                                    <div class="form-group">
                                        <label class="input-label text-capitalize d-flex alig-items-center"
                                            for="digit_after_decimal_point">{{ translate('messages.Digit after decimal point') }}</label>
                                        <input type="number" name="digit_after_decimal_point" class="form-control"
                                            id="digit_after_decimal_point"
                                            value="{{ $digit_after_decimal_point ? $digit_after_decimal_point->value : 0 }}"
                                            min="0" max="4" required>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    @php($footer_text = \App\Models\BusinessSetting::where('key', 'footer_text')->first())
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('copy right text') }} <img src="{{asset('/public/assets/admin/img/info-circle.svg')}}" title="{{ translate('make_visitors_aware_of_your_business‘s_rights_&_legal_information') }}" data-toggle="tooltip" alt=""> </label>
                                        <textarea type="text" value="" name="footer_text" class="form-control" placeholder="" rows="1"
                                            required>{{ $footer_text->value ?? '' }}</textarea>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    @php($cookies_text = \App\Models\BusinessSetting::where('key', 'cookies_text')->first())
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('Cookies_Text') }} <img src="{{asset('/public/assets/admin/img/info-circle.svg')}}" data-original-title="{{ translate('messages.make_visitors_aware_of_your_business‘s_rights_&_legal_information.') }}" data-toggle="tooltip" alt=""> </label>
                                        <textarea type="text" value="" name="cookies_text" class="form-control"
                                            placeholder="{{ translate('messages.Ex_:_Cookies_Text') }} " rows="1" required>{{ $cookies_text->value ?? '' }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h4 class="card-title mb-3 pt-2">
                        <span class="card-header-icon mr-2">
                            <i class="tio-settings-outlined"></i>
                        </span>
                        <span>{{translate('messages.business')}} {{translate('messages.Rule')}} {{translate('messages.setting')}}</span>
                    </h4>

                    <div class="card">
                        <div class="card-body">
                            <!-- This is Latest Desgin of Business Setting -->
                            <div class="row">
                                <div class="col-xl-4 col-lg-4 col-sm-6">
                                    @php($vnv = \App\Models\BusinessSetting::where('key', 'toggle_veg_non_veg')->first())
                                    @php($vnv = $vnv ? $vnv->value : 0)
                                    <div class="form-group">
                                        <label class="toggle-switch toggle-switch-sm d-flex justify-content-between border rounded px-3 px-xl-4 form-control" >
                                        <span class="pr-2 d-flex align-items-center"><span class="line--limit-1">{{ translate('Veg / Non Veg Option') }}</span><span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{translate('If_enabled,_customers_can_filter_food_according_to_their_preference_from_the_Customer_App_or_Website.')}}">
                                            <i class="tio-info-outined"></i>
                                            </span></span>
                                            <input type="checkbox" id="veg_nonveg" class="toggle-switch-input" value="1" name="vnv"
                                            onclick="toogleModal(event,'veg_nonveg','veg-on.png','veg-off.png','{{translate('Want_to_enable_the')}} <strong>{{translate('Veg/Non_Veg')}}</strong> {{translate('Option')}} ?','{{translate('Want_to_disable_the')}} <strong>{{translate('Veg/Non_Veg')}} {{translate('Option')}}</strong> ?',`<p>{{translate('If_enabled,_customers_can_filter_food_items_by_choosing_food_from_the_Veg/Non-Veg_option')}}</p>`,`<p>{{translate('If_disabled,_the_Veg/Non-Veg_option_will_be_hidden_in_both_the_Restaurant_panel_&_App_and_Website')}}</p>`)"
                                            {{ $vnv == 1 ? 'checked' : '' }}>
                                            <span class="toggle-switch-label text">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-sm-6">
                                    @php($business_model = \App\Models\BusinessSetting::where('key', 'business_model')->first())
                                    @php($business_model = $business_model->value ? json_decode($business_model->value, true) : 0)
                                    <div class="form-group">
                                        <label class="toggle-switch toggle-switch-sm d-flex justify-content-between border rounded px-3 px-xl-4 form-control">
                                        <span class="pr-2 d-flex align-items-center"><span class="line--limit-1">{{ translate('Commission Base Business Model') }}</span><span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right"
                                        data-original-title="{{translate('If_enabled,_the_commission_based_business_model_option_will_be_available_for_restaurants.')}}">
                                        <i class="tio-info-outined"></i>
                                            </span></span>
                                            <input type="checkbox" class="toggle-switch-input" value="1" name="commission" id="commission_id"

                                            onclick="toogleModal(event,'commission_id','mail-success.png','mail-warning.png','{{translate('Want_to_enable_the')}} <strong>{{translate('Commission_Base')}}</strong> {{translate('Business_Model')}} ?','{{translate('Want_to_disable_the')}} <strong>{{translate('Commission_Base')}} {{translate('Business_Model')}}</strong> ?' ,`<p>{{translate('If_enabled,_the_commission_based_restaurant_business_model_option_will_be_available_for_this_restaurant')}}</p>`,`<p>{{translate('If_disabled,_the_commission_based_restaurant_business_model_option_will_be_hidden_from_this_restaurant_panel._And_it_can_only_use_the_subscription_based_business_model')}}</p>`)"

                                            {{  $business_model['commission'] == 1 ? 'checked' : '' }}>
                                            <span class="toggle-switch-label text">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-sm-6">
                                    <div class="form-group">
                                        <label class="toggle-switch toggle-switch-sm d-flex justify-content-between border rounded px-3 px-xl-4 form-control">
                                        <span class="pr-2 d-flex align-items-center"><span class="line--limit-1">{{ translate('Subscription Base Business Model ') }}</span><span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right"
                                        data-original-title="{{translate('If_enabled,_the_package_based_subscription_business_model_option_will_be_available_for_restaurants')}}">
                                        <i class="tio-info-outined"></i>
                                            </span></span>
                                            <input type="checkbox" class="toggle-switch-input" value="1" name="subscription" id='subscription_id'

                                            onclick="toogleModal(event,'subscription_id','mail-success.png','mail-warning.png','{{translate('Want_to_enable_the')}} <strong>{{translate('Subscription_Base')}}</strong> {{translate('Business_Model')}} ?','{{translate('Want_to_disable_the')}} <strong>{{translate('Subscription_Base')}} {{translate('Business_Model')}}</strong> ?',`<p>{{translate('If_enabled,_the_subscription_based_restaurant_business_model_option_will_be_available_in_this_restaurant')}}</p>`,`<p>{{translate('If_disabled,_the_subscription_based_restaurant_business_model_option_will_be_hidden_from_this_restaurant_panel._The_existing_subscribed_restaurants’_subscriptions_will_be_valid_till_the_packages_expire')}}</p>`)"
                                            {{  $business_model['subscription'] == 1 ? 'checked' : '' }}>
                                            <span class="toggle-switch-label text">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-sm-6">
                                    @php($tax_included = \App\Models\BusinessSetting::where('key', 'tax_included')->first())
                                    @php($tax_included = $tax_included ? $tax_included->value : 0)
                                    <div class="form-group">
                                        <label class="form-label d-none d-sm-block">&nbsp;</label>
                                        <label class="toggle-switch toggle-switch-sm d-flex justify-content-between border rounded px-3 px-xl-4 form-control" >
                                        <span class="pr-2 d-flex align-items-center"><span class="line--limit-1">{{ translate('messages.Include_TAX_Amount') }}</span><span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{translate('messages.If_enabled,_the_customer_will_see_the_total_food_price,_including_VAT/TAX._If_it’s_disabled,_the_VAT/TAX_will_be_calculated_separately_from_the_total_cost_of_the_food,_and_he_can_see_a_separate_VAT/TAX_amount_on_the_invoice')}}">
                                            <i class="tio-info-outined"></i>
                                            </span></span>
                                            <input type="checkbox" class="toggle-switch-input" value="1" id="tax_included"
                                            onclick="toogleModal(event,'tax_included','tax-on.png','tax-off.png','{{translate('Want_to_disable')}} <strong>{{translate('Tax_Amount')}}</strong> ?','{{translate('Want_to_Enable')}} <strong>{{translate('Tax_Amount')}} </strong> ?',`<p>{{translate('If_enabled,_customers_will_see_the_food_Price_added_with_Tax')}}</p>`,`<p>{{translate('If_disabled,_customers_will_see_the_food_price_without_Tax')}}</p>`)"
                                            name="tax_included"
                                            {{ $tax_included == 1 ? 'checked' : '' }}>
                                            <span class="toggle-switch-label text">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-xl-4 col-lg-4 col-sm-6">
                                    @php($free_delivery_over = \App\Models\BusinessSetting::where('key', 'free_delivery_over')->first())
                                    <div class="form-group">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <label class="input-label text-capitalize d-inline-flex alig-items-center">
                                                <span class="line--limit-1">{{ translate('messages.free_delivery_over') }} ({{ \App\CentralLogics\Helpers::currency_symbol() }})</span>
                                                <span data-toggle="tooltip" data-placement="right" data-original-title="{{translate('If the order amount exceeds this amount the delivery fee will be free and the delivery fee will be deducted from the admin’s commission.')}}" class="input-label-secondary"><img src="{{ asset('/public/assets/admin/img/info-circle.svg') }}" alt="{{ translate('messages.Set_a_minimum_order_value_for_free_delivery._If_someone’s_order_value_reaches_this_point,_he_will_get_a_free_delivery,_and_the_delivery_fee_will_be_deducted_from_Admin’s_commission_and_added_to_the_Admin’s_expense') }}"></span>
                                            </label>
                                            <label class="toggle-switch toggle-switch-sm" data-toggle="modal" data-target="#free-delivery-modal">
                                                <input type="checkbox" class="toggle-switch-input" name="free_delivery_over_status"
                                                    id="free_delivery_over_status"

                                                    onclick="toogleModal(event,'free_delivery_over_status','tax-on.png','tax-off.png','{{translate('Want_to_enable')}} <strong>{{translate('Free_Delivery')}}</strong> {{translate('on_Minimum_Orders')}} ?','{{translate('Want_to_disable')}} <strong>{{translate('Free_Delivery')}} {{translate('on_Minimum_Orders')}}</strong> ?',`<p>{{translate('If_enabled,_customers_can_get_FREE_Delivery_by_ordering_above_the_minimum_order_requirement')}}</p>`,`<p>{{translate('If_disabled,_the_FREE_Delivery_option_will_be_hidden_from_the_Customer_App_or_Website.')}}</p>`)"
                                                    value="1"

                                                    {{ isset($free_delivery_over->value) ? 'checked' : '' }}>
                                                <span class="toggle-switch-label text">
                                                    <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                        </div>
                                        <input type="number" name="free_delivery_over" class="form-control"
                                            id="free_delivery_over"
                                            value="{{ $free_delivery_over ? $free_delivery_over->value : 0 }}" min="0"
                                            step=".01" placeholder="{{ translate('messages.Ex :') }} 100" required {{ isset($free_delivery_over->value) ? '' : 'readonly' }}>
                                    </div>
                                </div>


                                <div class="col-xl-4 col-lg-4 col-sm-6">
                                    @php($admin_order_notification = \App\Models\BusinessSetting::where('key', 'admin_order_notification')->first())
                                    @php($admin_order_notification = $admin_order_notification ? $admin_order_notification?->value : 0)
                                    <div class="form-group">
                                        <label class="form-label d-none d-sm-block">&nbsp;</label>
                                        <label class="toggle-switch toggle-switch-sm d-flex justify-content-between border rounded px-3 px-xl-4 form-control">
                                        <span class="pr-2 d-flex align-items-center">
                                            <span class="line--limit-1">{{translate('Order_Notification_for_Admin')}}</span><span class="input-label-secondary text--title" data-original-title="{{ translate('Admin_will_get_a_pop-up_notification_with_sounds_for_every_order_placed_by_customers') }}" data-toggle="tooltip" data-placement="right">
                                            <i class="tio-info-outined"></i>
                                            </span>
                                        </span>
                                            <input type="checkbox" class="toggle-switch-input" id="admin_notification"
                                            value="1"
                                            name="admin_order_notification"
                                            {{ $admin_order_notification == 1 ? 'checked' : '' }}
                                            onclick="toogleModal(event,'admin_notification','order-notification-on.png','order-notification-off.png','{{translate('Want_to_enable')}} <strong>{{translate('Order_Notification_for_Admin')}}</strong> ?','{{translate('Want_to_disable')}} <strong>{{translate('Order_Notification_for_Admin')}}</strong> ?',`<p>{{translate('If_enabled,_the_Admin_will_receive_a_Notification_for_every_order_placed_by_customers')}}</p>`,`<p>{{translate('If_disabled,_the_Admin_will_NOT_receive_any_Notification_for_any_order_placed_by_customers')}}</p>`)"
                                            >
                                            <span class="toggle-switch-label text">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    </div>
                                </div>

                            </div>

                            <div class="btn--container justify-content-end">
                                <button type="reset" id="reset_btn" class="btn btn--reset">{{ translate('messages.Reset') }} </button>
                                <button type="{{ env('APP_MODE') != 'demo' ? 'submit' : 'button' }}"
                                onclick="{{ env('APP_MODE') != 'demo' ? '' : 'call_demo()' }}"
                                class="btn btn--primary mb-2"><i class="tio-save-outlined mr-2"></i>{{ translate('messages.save') }} {{translate('messages.info')}}</button>
                            </div>
                        </div>
                    </div>

                </form>


            </div>
        </div>
    </div>


    {{-- <!-- How it Works -->
    <div class="modal fade" id="how-it-works">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h3>{{translate('Subscription Packages')}}</h3>
                    <p>
                        {{translate('Here you can view all the data placements in a package card in the subscription UI in the user app and website')}}
                    </p>
                    <img src="{{asset('/public/assets/admin/img/modal/subscription.png')}}" class="mw-100" alt="">
                </div>
            </div>
        </div>
    </div> --}}
@endsection

@push('script_2')
    <script>

        function maintenance_mode() {
            toastr.warning('Sorry! You can not enable maintainance mode in demo!');
        };

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

        $("#favIconUpload").change(function() {
            readURL(this, 'iconViewer');
        });
    </script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ \App\Models\BusinessSetting::where('key', 'map_api_key')->first()->value }}&libraries=places&v=3.45.8">
    </script>
    <script>
        function initAutocomplete() {
            var myLatLng = {
                lat: {{ $default_location ? $default_location['lat'] : '-33.8688' }},
                lng: {{ $default_location ? $default_location['lng'] : '151.2195' }}
            };
            const map = new google.maps.Map(document.getElementById("location_map_canvas"), {
                center: {
                    lat: {{ $default_location ? $default_location['lat'] : '-33.8688' }},
                    lng: {{ $default_location ? $default_location['lng'] : '151.2195' }}
                },
                zoom: 13,
                mapTypeId: "roadmap",
            });

            var marker = new google.maps.Marker({
                position: myLatLng,
                map: map,
            });

            marker.setMap(map);
            var geocoder = geocoder = new google.maps.Geocoder();
            google.maps.event.addListener(map, 'click', function(mapsMouseEvent) {
                var coordinates = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2);
                var coordinates = JSON.parse(coordinates);
                var latlng = new google.maps.LatLng(coordinates['lat'], coordinates['lng']);
                marker.setPosition(latlng);
                map.panTo(latlng);

                document.getElementById('latitude').value = coordinates['lat'];
                document.getElementById('longitude').value = coordinates['lng'];


                geocoder.geocode({
                    'latLng': latlng
                }, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[1]) {
                            document.getElementById('address').innerHtml = results[1].formatted_address;
                        }
                    }
                });
            });
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
                    var mrkr = new google.maps.Marker({
                        map,
                        title: place.name,
                        position: place.geometry.location,
                    });
                    google.maps.event.addListener(mrkr, "click", function(event) {
                        document.getElementById('latitude').value = this.position.lat();
                        document.getElementById('longitude').value = this.position.lng();
                    });

                    markers.push(mrkr);

                    if (place.geometry.viewport) {
                        // Only geocodes have viewport.
                        bounds.union(place.geometry.viewport);
                    } else {
                        bounds.extend(place.geometry.location);
                    }
                });
                map.fitBounds(bounds);
            });
        };
        $(document).on('ready', function() {
            initAutocomplete();
            @php($country = \App\Models\BusinessSetting::where('key', 'country')->first())

            @if ($country)
                $("#country option[value='{{ $country->value }}']").attr('selected', 'selected').change();
            @endif



            $("#free_delivery_over_status").on('change', function() {
                if ($("#free_delivery_over_status").is(':checked')) {
                    $('#free_delivery_over').removeAttr('readonly');
                } else {
                    $('#free_delivery_over').attr('readonly', true);
                    $('#free_delivery_over').val('Ex : 0');
                }
            });

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

        $(document).on("keydown", "input", function(e) {
            if (e.which == 13) e.preventDefault();
        });

        $('#reset_btn').click(function(){
            location.reload(true);
        })
    </script>
@endpush
