@extends('layouts.admin.app')

@section('title', translate('DB_clean'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush
@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title mb-2 text-capitalize">
                <div class="card-header-icon d-inline-flex mr-2 img">
                    <img src="{{asset('/public/assets/admin/img/clean-database.png')}}" alt="public">
                </div>
                <span>
                    {{ translate('Clean database') }}
                </span>
            </h1>
        </div>
        <!-- End Page Header -->
        <div class="alert alert--danger alert-danger mb-2" role="alert">
            <span class="alert--icon"><i class="tio-info"></i></span> <strong>{{ translate('messages.note') }}: </strong>{{translate('This_page_contains_sensitive_information.Please_make_sure_before_click_the_button.')}}
        </div>
        <div class="card">
            <div class="card-body pt-2">
                <form action="{{ route('admin.business-settings.clean-db') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="check--item-wrapper clean--database-checkgroup">
                        @foreach ($tables as $key => $table)
                            <div class="check-item">
                                <div class="form-group form-check form--check">
                                    <input type="checkbox" name="tables[]" value="{{ $table }}"
                                    class="form-check-input" id="{{ $table }}">
                                    <label class="form-check-label text-dark pl-2 flex-grow-1"
                                    for="{{ $table }}">{{ Str::limit($table, 20) }} <span class="badge-pill badge-secondary mx-2">{{ $rows[$key] }}</span></label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="text-right mt-3">
                        <button type="{{ env('APP_MODE') != 'demo' ? 'submit' : 'button' }}"
                        onclick="{{ env('APP_MODE') != 'demo' ? '' : 'call_demo()' }}"
                        class="btn btn--primary" id="submitForm">{{ translate('Clear') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script_2')
<script>
    var restaurant_dependent = ['restaurants','restaurant_schedule', 'discounts'];
    var order_dependent = ['order_delivery_histories','d_m_reviews', 'delivery_histories', 'track_deliverymen', 'order_details', 'reviews'];
    var zone_dependent = ['restaurants','vendors', 'orders'];
    var user_info_dependent = ['conversations', 'messages'];
    $(document).ready(function () {
        $('.form-check-input').on('change', function(event){
            if($(this).is(':checked')){
                if(event.target.id == 'zones' || event.target.id == 'restaurants' || event.target.id == 'vendors') {
                    checked_restaurants(true);
                }

                if(event.target.id == 'zones' || event.target.id == 'orders') {
                    checked_orders(true);
                }

                if(event.target.id == 'user_infos'){
                    checked_conversations(true);
                }
            } else {
                if(restaurant_dependent.includes(event.target.id)) {
                    if(check_restaurant() || check_zone()){
                        $(this).prop('checked', true);
                    }
                } else if(order_dependent.includes(event.target.id)) {
                    if(check_orders() || check_zone()){
                        $(this).prop('checked', true);
                    }
                } else if(zone_dependent.includes(event.target.id)) {
                    if(check_zone()){
                        $(this).prop('checked', true);
                    }
                } else if(event.target.id == 'user_infos') {
                    if(check_conversations() || check_messages()){
                        $(this).prop('checked', true);
                    }
                } else if(event.target.id == 'conversations') {
                    if( check_messages()){
                        $(this).prop('checked', true);
                    }
                }
            }

        });

        $("#purchase_code_div").click(function () {
            var type = $('#purchase_code').get(0).type;
            if (type === 'password') {
                $('#purchase_code').get(0).type = 'text';
            } else if (type === 'text') {
                $('#purchase_code').get(0).type = 'password';
            }
        });
    })

    function checked_restaurants(status) {
        restaurant_dependent.forEach(function(value){
            $('#'+value).prop('checked', status);
        });
        $('#vendors').prop('checked', status);

    }

    function checked_orders(status) {
        order_dependent.forEach(function(value){
            $('#'+value).prop('checked', status);
        });
        $('#orders').prop('checked', status);
    }

    function checked_conversations(status) {
        user_info_dependent.forEach(function(value){
            $('#'+value).prop('checked', status);
        });
        $('#user_infos').prop('checked', status);
    }



    function check_zone() {
        if($('#zones').is(':checked')) {
            toastr.warning("{{translate('messages.table_unchecked_warning',['table'=>'zones'])}}");
            return true;
        }
        return false;
    }

    function check_orders() {
        if($('#orders').is(':checked')) {
            toastr.warning("{{translate('messages.table_unchecked_warning',['table'=>'orders'])}}");
            return true;
        }
        return false;
    }

    function check_restaurant() {
        if($('#restaurants').is(':checked') || $('#vendors').is(':checked')) {
            toastr.warning("{{translate('messages.table_unchecked_warning',['table'=>'restaurants/vendors'])}}");
            return true;
        }
        return false;
    }

    function check_conversations() {
        if($('#conversations').is(':checked')) {
            toastr.warning("{{translate('messages.table_unchecked_warning',['table'=>'conversations'])}}");
            return true;
        }
        return false;
    }

    function check_messages() {
        if($('#messages').is(':checked')) {
            toastr.warning("{{translate('messages.table_unchecked_warning',['table'=>'messages'])}}");
            return true;
        }
        return false;
    }
    </script>
    <script>
        $("form").on('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: '{{ translate('Are you sure?') }}',
                text: "{{ translate('Sensitive_data! Make_sure_before_changing.') }}",
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#FC6A57',
                cancelButtonText: '{{ translate('no') }}',
                confirmButtonText: '{{ translate('yes') }}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    this.submit();
                } else {
                    e.preventDefault();
                    toastr.success("{{ translate('Cancelled') }}");
                    location.reload();
                }
            })
        });
    </script>
@endpush
