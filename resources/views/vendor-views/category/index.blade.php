@extends('layouts.vendor.app')

@section('title',translate('messages.Category'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <div class="card border-0">
            <div class="card-header border-0 py-2">
                <div class="search--button-wrapper justify-content-end">
                    <h2 class="page-header-title card-title text-capitalize">
                        <div class="card-header-icon d-inline-flex mr-2 img">
                            <img src="{{asset('/public/assets/admin/img/resturant-panel/page-title/category.png')}}" alt="public">
                        </div>
                        <span>
                            {{translate('messages.category')}} {{translate('messages.list')}}
                            <span class="badge badge-soft-dark ml-2" id="itemCount">{{$categories->total()}}</span>
                        </span>
                    </h2>
                    <form  action="javascript:"  id="dataSearch" class="search-form ml-auto">
                        @csrf
                        <!-- Search -->
                        <div class="input-group input--group">
                            <input type="search" name="search" class="form-control" placeholder="{{ translate('Ex : Search by category name...') }}" aria-label="{{translate('messages.search_categories')}}">
                            <button type="submit" class="btn btn--secondary">
                                <i class="tio-search"></i>
                            </button>
                        </div>
                        <!-- End Search -->
                    </form>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive datatable-custom">
                    <table id="columnSearchDatatable"
                        class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                        data-hs-datatables-options='{
                            "search": "#datatableSearch",
                            "entries": "#datatableEntries",
                            "isResponsive": false,
                            "isShowPaging": false,
                            "paging":false,
                        }'>
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center w-33p">{{ translate('messages.sl') }}</th>
                                <th class="text-center w-33p">{{ translate('messages.Category') }} {{translate('messages.id')}}</th>
                                <th class="text-center w-33p">{{ translate('messages.Category') }} {{translate('messages.name')}}</th>
                            </tr>
                        </thead>

                        <tbody id="table-div">
                        @foreach($categories as $key=>$category)
                            <tr>
                                <td class="text-center">{{$key+$categories->firstItem()}}</td>
                                <td class="text-center">{{$category->id}}</td>
                                <td class="text-center">
                                <span class="d-block font-size-sm text-body">
                                    {{Str::limit($category['name'],20,'...')}}
                                </span>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @if(count($categories) === 0)
                    <div class="empty--data">
                        <img src="{{asset('/public/assets/admin/img/empty.png')}}" alt="public">
                        <h5>
                            {{translate('no_data_found')}}
                        </h5>
                    </div>
                    @endif
                </div>
            </div>
            <div class="card-footer page-area">
                <!-- Pagination -->
                <div class="row justify-content-center justify-content-sm-between align-items-sm-center">
                    <div class="col-sm-auto">
                        <div class="d-flex justify-content-center justify-content-sm-end">
                            <!-- Pagination -->
                            {!! $categories->links() !!}
                        </div>
                    </div>
                </div>
                <!-- End Pagination -->
            </div>
        </div>
    </div>

@endsection

@push('script_2')
    <script>
        $(document).on('ready', function () {
            // INITIALIZATION OF DATATABLES
            // =======================================================
            var datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'));



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

        $('#dataSearch').on('submit', function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('vendor.category.search')}}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    $('#table-div').html(data.view);
                    $('#itemCount').html(data.count);
                    $('.page-area').hide();
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        });
    </script>
@endpush
