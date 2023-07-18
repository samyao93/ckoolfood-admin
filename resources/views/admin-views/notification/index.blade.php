@extends('layouts.admin.app')

@section('title',translate('messages.notification'))


@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title text-capitalize">
                        <div class="card-header-icon d-inline-flex mr-2 img">
                            <img src="{{asset('/public/assets/admin/img/bell.png')}}" alt="public">
                        </div>
                        <span>
                            {{translate('messages.notification')}}
                        </span>
                    </h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->

        <div class="card mb-3">
            <div class="card-body">
                <form action="{{route('admin.notification.store')}}" method="post" enctype="multipart/form-data" id="notification">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('messages.title')}}</label>
                                <input id="notification_title" type="text" name="notification_title" class="form-control" placeholder="{{ translate('Ex: Notification Title') }}" required maxlength="191">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('messages.zone')}}</label>
                                <select id="zone" name="zone" class="form-control js-select2-custom" >
                                    <option value="all">{{translate('messages.all')}}</option>
                                    @foreach(\App\Models\Zone::orderBy('name')->get() as $z)
                                        <option value="{{$z['id']}}">{{$z['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="input-label" for="tergat">{{translate('messages.send_to')}}</label>

                                <select name="tergat" class="form-control" id="tergat" data-placeholder="{{ translate('messages.Ex :') }} contact@company.com" required>
                                    <option value="customer">{{translate('messages.customer')}}</option>
                                    <option value="deliveryman">{{translate('messages.deliveryman')}}</option>
                                    <option value="restaurant">{{translate('messages.restaurant')}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">&nbsp;</label>
                                <center class="mb-3">
                                    <img class="initial-30" id="viewer"
                                        src="{{asset('public/assets/admin/img/900x400/img1.png')}}" alt="image"/>
                                </center>

                                <label>{{translate('messages.notification')}} {{translate('messages.banner')}}</label><small class="text-danger">* ( {{translate('messages.ratio')}} 3:1 )</small>
                                <div class="custom-file">
                                    <input type="file" name="image" id="customFileEg1" class="custom-file-input"
                                        accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                    <label class="custom-file-label" for="customFileEg1">{{translate('messages.choose')}} {{translate('messages.file')}}</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('messages.description')}}</label>
                                <textarea id="description" name="description" class="form-control h--md-200px" placeholder="{{ translate('Ex : Notification Descriptions ') }}" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="btn--container justify-content-end mb-0">
                        <button type="button" id="reset_btn" class="btn btn--reset">{{translate('messages.reset')}}</button>
                        <button type="submit" id="submit" class="btn btn--primary">{{translate('messages.send')}} {{translate('messages.notification')}}</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Start Table -->
        <div class="card">
            <div class="card-header py-2 border-0">
                    <div class="search--button-wrapper">
                    <h3 class="card-title">{{translate('notification_list')}}
                        <span class="badge badge-soft-dark ml-2">{{$notifications->total()}}</span>
                    </h3>
                    <form>
                    <!-- Search -->
                        <div class="input--group input-group input-group-merge input-group-flush">
                            <input type="search" id="column1_search" class="form-control"
                                placeholder="{{ translate('Search by title') }}">
                            <button type="submit" class="btn btn--secondary">
                                <i class="tio-search"></i>
                            </button>
                        </div>
                    <!-- End Search -->
                    </form>

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
                        <a target="_blank" id="export-excel" class="dropdown-item" href="{{route('admin.notification.export', ['type'=>'excel'])}}">
                            <img class="avatar avatar-xss avatar-4by3 mr-2"
                                    src="{{asset('public/assets/admin')}}/svg/components/excel.svg"
                                    alt="Image Description">
                            {{translate('messages.excel')}}
                        </a>
                        <a target="_blank" id="export-csv" class="dropdown-item" href="{{route('admin.notification.export', ['type'=>'excel'])}}">
                            <img class="avatar avatar-xss avatar-4by3 mr-2"
                                    src="{{asset('public/assets/admin')}}/svg/components/placeholder-csv-format.svg"
                                    alt="Image Description">
                            .{{translate('messages.csv')}}
                        </a>
                    </div>
                </div>
                </div>
            </div>
            <!-- Table -->
            <div class="table-responsive datatable-custom">
                <table id="columnSearchDatatable"
                       class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                       data-hs-datatables-options='{
                         "order": [],
                         "orderCellsTop": true,
                         "paging": false
                       }'>
                    <thead class="thead-light">
                        <tr>
                            <th>{{translate('sl')}}</th>
                            <th class="w-20p">{{translate('messages.title')}}</th>
                            <th>{{translate('messages.description')}}</th>
                            <th>{{translate('messages.image')}}</th>
                            <th class="w-08p">{{translate('messages.zone')}}</th>
                            <th>{{translate('messages.tergat')}}</th>
                            <th class="w-08p">{{translate('messages.status')}}</th>
                            <th class="text-center w-12p">{{translate('messages.action')}}</th>
                        </tr>
                    </thead>

                    <tbody>
                    @foreach($notifications as $key=>$notification)
                        <tr>
                            <td>{{$key+$notifications->firstItem()}}</td>
                            <td>
                            <span class="d-block font-size-sm text-body">
                                {{substr($notification['title'],0,25)}} {{strlen($notification['title'])>25?'...':''}}
                            </span>
                            </td>
                            <td>
                                {{substr($notification['description'],0,25)}} {{strlen($notification['description'])>25?'...':''}}
                            </td>
                            <td>
                                <img class="initial-31" src="{{asset('storage/app/public/notification')}}/{{$notification['image']}}" onerror="src='{{asset('public/assets/admin/img/900x400/img1.jpg')}}'">
                            </td>
                            <td>
                                {{$notification->zone_id==null?translate('messages.all'):($notification->zone?$notification->zone->name:translate('messages.zone').' '.translate('messages.deleted'))}}
                            </td>
                            @if ($notification->tergat == 'customer')
                            <td class="text-capitalize">
                                {{translate('messages.customer')}}
                            </td>
                            @elseif ($notification->tergat=='deliveryman')
                            <td class="text-capitalize">
                                {{translate('messages.delivery_man')}}
                            </td>
                            @elseif ($notification->tergat=='restaurant')
                            <td class="text-capitalize">
                                {{translate('messages.restaurant')}}
                            </td>
                            @endif
                            <td>
                                <label class="toggle-switch toggle-switch-sm" for="stocksCheckbox{{$notification->id}}">
                                    <input type="checkbox" onclick="location.href='{{route('admin.notification.status',[$notification['id'],$notification->status?0:1])}}'"class="toggle-switch-input" id="stocksCheckbox{{$notification->id}}" {{$notification->status?'checked':''}}>
                                    <span class="toggle-switch-label">
                                        <span class="toggle-switch-indicator"></span>
                                    </span>
                                </label>
                            </td>
                            <td>
                                <div class="btn--container justify-content-center">
                                    <a class="btn btn--primary btn-outline-primary action-btn"
                                        href="{{route('admin.notification.edit',[$notification['id']])}}" title="{{translate('messages.edit')}} {{translate('messages.notification')}}"><i class="tio-edit"></i>
                                    </a>
                                    <a class="btn btn--danger btn-outline-danger action-btn" href="javascript:"
                                        onclick="form_alert('notification-{{$notification['id']}}','{{ translate('Want to delete this notification ?') }}')" title="{{translate('messages.delete')}} {{translate('messages.notification')}}"><i class="tio-delete-outlined"></i>
                                    </a>
                                </div>
                                <form action="{{route('admin.notification.delete',[$notification['id']])}}" method="post" id="notification-{{$notification['id']}}">
                                            @csrf @method('delete')
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @if(count($notifications) === 0)
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
                            {!! $notifications->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Table -->
    </div>

@endsection

@push('script_2')
    <script>
        $(document).on('ready', function () {
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
        });
    </script>

    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function () {
            readURL(this);
        });

        $('#notification').on('submit', function (e) {

            e.preventDefault();
            var formData = new FormData(this);

            Swal.fire({
                title: '{{translate('messages.are_you_sure')}}',
                text: '{{translate('You want to sent notification?')}}',
                type: 'info',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: 'primary',
                cancelButtonText: '{{translate('messages.no')}}',
                confirmButtonText: '{{translate('messages.send')}}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.post({
                        url: '{{route('admin.notification.store')}}',
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function (data) {
                            if (data.errors) {
                                for (var i = 0; i < data.errors.length; i++) {
                                    toastr.error(data.errors[i].message, {
                                        CloseButton: true,
                                        ProgressBar: true
                                    });
                                }
                            } else {
                                toastr.success('{{ translate('Notifiction sent successfully!') }}', {
                                    CloseButton: true,
                                    ProgressBar: true
                                });
                                setTimeout(function () {
                                    location.href = '{{route('admin.notification.add-new')}}';
                                }, 2000);
                            }
                        }
                    });
                }
            })
        })
    </script>
    <script>
        $('#reset_btn').click(function(){
            $('#notification_title').val(null);
            $('#zone').val('all').trigger('change');
            $('#tergat').val('customer').trigger('change');
            $('#description').val(null);
            $('#viewer').attr('src','{{asset('public/assets/admin/img/900x400/img1.png')}}');
            $('#customFileEg1').val(null);
        })
    </script>
@endpush
