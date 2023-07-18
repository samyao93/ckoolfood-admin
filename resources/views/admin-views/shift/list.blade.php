@extends('layouts.admin.app')

@section('title',translate('messages.Shift_setup'))

@push('css_or_js')

@endpush

@section('content')

<div class="content container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm mb-2 mb-sm-0">
                <h1 class="page-header-title"><i class="tio-calendar"></i> {{translate('messages.Shift_setup')}} <span class="badge badge-soft-dark ml-2" id="itemCount">{{$shifts->total()}}</span></h1>
            </div>

            <div class="col-sm-auto">
                <a class="btn btn--primary" href="#" data-toggle="modal" data-target="#addSystemModal">
                    <i class="tio-add"></i> {{translate('messages.Add_Shift')}}
                </a>
            </div>
        </div>
    </div>
    <!-- End Page Header -->
    <div class="row gx-2 gx-lg-3">
        <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
            <!-- Card -->
            <div class="card">
                <div class="card-header py-2 border-0">
                    <div class="search--button-wrapper">
                        <h5 class="card-title"></h5>
                        <form>
                            <!-- Search -->
                            <div class="input--group input-group input-group-merge input-group-flush">
                                <input id="datatableSearch" type="search" name="search" class="form-control" placeholder="{{ translate('Ex: Search by name...') }}" aria-label="Search here">
                                <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>
                            </div>
                            <!-- End Search -->
                        </form>
                    </div>
                </div>
                <!-- Table -->
                <div class="table-responsive datatable-custom">
                    <table id="columnSearchDatatable"
                            class="font-size-sm table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                            data-hs-datatables-options='{
                                "order": [],
                                "orderCellsTop": true,
                                "paging":false
                            }'>
                        <thead class="thead-light">
                        <tr>
                            <th>{{translate('messages.sl')}}</th>
                            <th >{{translate('messages.name')}} </th>
                            <th >{{translate('messages.Start_time')}}</th>
                            <th >{{translate('messages.End_time') }}</th>
                            <th >{{translate('messages.status')}}</th>
                            <th class="text-center">{{translate('messages.action')}}</th>
                        </tr>
                        </thead>

                        <tbody id="set-rows">
                            @include('admin-views.shift.partials._table',['shifts' => $shifts])
                        </tbody>
                    </table>
                    @if(count($shifts) === 0)
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
                                {!! $shifts->links() !!}
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Table -->
            </div>
            <!-- End Card -->
        </div>
    </div>
</div>

    <!-- Modal -->
    <div class="modal fade" id="addSystemModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">

                    <h5 class="modal-title" id="exampleModalLabel">{{translate('messages.Shift_Setup')}}  </h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body p-30">

                    <form  action="javascript:" id="system-form"   method="post">
                        @csrf
                        @method('post')

                        @csrf
                        @php($language=\App\Models\BusinessSetting::where('key','language')->first())
                        @php($language = $language->value ?? null)
                        @php($default_lang = str_replace('_', '-', app()->getLocale()))
                        @if($language)
                        <ul class="nav nav-tabs nav--tabs mb-3 border-0">
                            <li class="nav-item">
                                <a class="nav-link lang_link1 active"
                                href="#"
                                id="default-link1">{{ translate('Default') }}</a>
                            </li>
                            @foreach (json_decode($language) as $lang)
                                <li class="nav-item">
                                    <a class="nav-link lang_link1"
                                        href="#"
                                        id="{{ $lang }}-link1">{{ \App\CentralLogics\Helpers::get_language_name($lang) . '(' . strtoupper($lang) . ')' }}</a>
                                </li>
                            @endforeach
                        </ul>
                        @endif


                        <div class="form-group lang_form1 default-form1 ">
                            <input type="hidden" name="lang[]" value="default">
                            <label for="name" class="mb-2">{{ translate('messages.name') }} ({{translate('messages.default')}})</label>
                            <input type="text" name="name[]"   id="name" class="form-control" placeholder="{{ translate('messages.Ex: Morning') }}">
                        </div>


                        @if ($language)
                            @foreach(json_decode($language) as $lang)
                                <div class="form-group d-none lang_form1" id="{{$lang}}-form1">
                                    <label for="name" class="mb-2">{{ translate('messages.name') }}  ({{strtoupper($lang)}})</label>
                                    <input type="text" name="name[]" class="form-control" placeholder="{{ translate('messages.Ex: Morning') }}">
                                </div>
                                <input type="hidden" name="lang[]" value="{{$lang}}">
                            @endforeach
                        @endif


                        <br>
                        <div class="form-group">
                            <label for="start_time" class="mb-2">{{ translate('messages.Start_Time') }}</label>
                            <input type="time" required  name="start_time" id="start_time" class="form-control">
                        </div>
                        <br>
                        <div class="form-group">
                            <label for="end_time" class="mb-2">{{ translate('messages.End_Time') }}</label>
                            <input type="time" required name="end_time" id="end_time" class="form-control" >
                        </div>
                        <br>

                    <div class="modal-footer">
                        <button id="reset_btn" type="reset" data-dismiss="modal" class="btn btn-secondary" >{{ translate('messages.Reset') }} </button>
                        <button class="btn btn-primary" type="submit">{{ translate('messages.Submit') }}</button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>




@endsection

@push('script')
<script>
            function status_change_alert(url, message, e) {
            e.preventDefault();
            Swal.fire({
                title: '{{ translate('Are you sure?') }}',
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
                    location.href=url;
                }
            })
        }
</script>



    <script>
        $('#system-form').on('submit', function () {
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('admin.shift.store')}}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#loading').show();
                $('#addSystemModal').modal('toggle')
;

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
                        toastr.success('{{ translate('messages.Shift_added_successfully') }}', {
                                    CloseButton: true,
                                    ProgressBar: true
                                });
                                // console.log(data.token);
                                // $('#System_Token').modal('show');
                                // document.getElementById('token').value=data.token;
                                setTimeout(function() {
                                    location.href =
                                        '{{ route('admin.shift.list') }}';
                                },800);
                    }
                },
                error: function (data) {
                    $.each(data.responseJSON.errors, function(key,value) {
                        toastr.error(value, {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    });
                    },
                complete: function () {
                    $('#loading').hide();
                },
            });
        });
    </script>
    <script>
        $('#system-form-update').on('submit', function () {
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('admin.shift.update')}}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#loading').show();
                    $('#exampleModal').modal('toggle');
                },
                success: function (data) {
                    $('#loading').hide();
                    if(data.errors){
                        for (var i = 0; i < data.errors.length; i++) {
                            toastr.error(data.errors[i].message, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        }
                    } else{
                    toastr.success('{{ translate('messages.Update_successful') }}', {
                                CloseButton: true,
                                ProgressBar: true
                            });
                            // console.log(data.token);
                            // $('#addSystemModal').modal('toggle');
                            // $('#System_Token').modal('show');
                            // document.getElementById('token').value=data.token;
                            setTimeout(function() {
                                location.href =
                                    '{{ route('admin.shift.list') }}';
                            }, 800);
                        }
                },
                error: function (data) {
                    $.each(data.responseJSON.errors, function(key,value) {
                        toastr.error(value, {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    });
                    },
                complete: function () {
                    $('#loading').hide();
                },
            });
        });
    </script>
        <script>
            $('#reset_btn').click(function(){
                $('#name').val(null);
                $('#start_time').val(null);
                $('#end_time').val(null);
            })

            function edit_shift(){
            $(".add_active").addClass('active');
            $(".lang_form").addClass('d-none');
            $(".add_active_2").removeClass('d-none');
        }

    $(".lang_link").click(function(e){
        e.preventDefault();
        $(".lang_link").removeClass('active');
        $(".lang_form").addClass('d-none');
        $(".add_active").removeClass('active');
        $(this).addClass('active');

        let form_id = this.id;
        let lang = form_id.substring(0, form_id.length - 5);

        console.log(lang);

        // $("#"+lang+"-form").removeClass('d-none');

        @foreach ($shifts as $cu )
        $("#"+lang+"-form_{{ $cu->id }}").removeClass('d-none');
        @endforeach
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

    $(".lang_link1").click(function(e){
        e.preventDefault();
        $(".lang_link1").removeClass('active');
        $(".lang_form1").addClass('d-none');
        $(this).addClass('active');
        let form_id = this.id;
        let lang = form_id.substring(0, form_id.length - 6);
        $("#"+lang+"-form1").removeClass('d-none');
            if(lang == 'default')
        {
            $(".default-form1").removeClass('d-none');
        }
    })

        </script>
@endpush

