@extends('layouts.admin.app')

@section('title',translate('messages.Add new category'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h2 class="page-header-title text-capitalize">
                        <div class="card-header-icon d-inline-flex mr-2 img">
                            <img src="{{asset('public/assets/admin/img/category.png')}}" alt="">
                        </div>
                        <span>
                            {{translate('Category')}}
                        </span>
                    </h2>
                </div>
                @if(isset($category))
                <a href="{{route('admin.category.add')}}" class="btn btn--primary pull-right"><i class="tio-add-circle"></i> {{translate('messages.add')}} {{translate('messages.new')}} {{translate('messages.category')}}</a>
                @endif
            </div>
        </div>
        <!-- End Page Header -->

        <div class="card resturant--cate-form">
            <div class="card-body">
                <form action="{{isset($category)?route('admin.category.update',[$category['id']]):route('admin.category.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    @php($language=\App\Models\BusinessSetting::where('key','language')->first())
                    @php($language = $language->value ?? null)
                    @php($default_lang = str_replace('_', '-', app()->getLocale()))
                    @if($language)
                        <ul class="nav nav-tabs mb-4">
                            <li class="nav-item">
                                <a class="nav-link lang_link  active" href="#" id="default-link">{{ translate('Default')}}</a>
                            </li>
                            @foreach(json_decode($language) as $lang)
                                <li class="nav-item">
                                    <a class="nav-link lang_link " href="#" id="{{$lang}}-link">{{\App\CentralLogics\Helpers::get_language_name($lang).'('.strtoupper($lang).')'}}</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                    @if ($language)
                    <div class="form-group lang_form" id="default-form">
                            <label class="input-label" for="exampleFormControlInput1">{{translate('messages.name')}}</label>
                            <input type="text" name="name[]" class="form-control" placeholder="{{ translate('Ex: Category Name') }}"   maxlength="191">
                        <input type="hidden" name="lang[]" value="default">
                    </div>
                        @foreach(json_decode($language) as $lang)
                            <div class="form-group d-none lang_form" id="{{$lang}}-form">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('messages.name')}} ({{strtoupper($lang)}})</label>
                                <input id="name" type="text" name="name[]" class="form-control" placeholder="{{ translate('Ex: Category Name') }}" maxlength="191" oninvalid="document.getElementById('en-link').click()">
                                <input type="hidden" name="lang[]" value="{{$lang}}">
                            </div>
                        @endforeach
                    @else
                        <div class="form-group">
                            <label class="input-label" for="exampleFormControlInput1">{{translate('messages.name')}}</label>
                            <input type="text" name="name[]" class="form-control" placeholder="{{ translate('Ex: Category Name') }}"   maxlength="191">
                        </div>
                        <input type="hidden" name="lang[]" value="default">
                    @endif

                    <input name="position" value="0" type="hidden">
                    <div class="row">
                        <div class="col-md-6 col-lg-4">
                            <div class="form-group mb-0">
                                <center>
                                    <img class="initial-18" id="viewer"
                                        @if(isset($category))
                                        src="{{asset('storage/app/public/category')}}/{{$category['image']}}"
                                        @else
                                        src="{{asset('public/assets/admin/img/100x100/food-default-image.png')}}"
                                        @endif
                                        alt="image"/>
                                </center>
                            </div>
                            <div class="form-group mt-2">
                                <label>{{translate('messages.image')}}</label><small class="text-danger">* ( {{translate('messages.ratio')}} 1:1)</small>
                                <div class="custom-file">
                                    <input type="file" name="image" id="customFileEg1" class="custom-file-input"
                                        accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" required>
                                    <label class="custom-file-label" for="customFileEg1">{{translate('messages.choose')}} {{translate('messages.file')}}</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group pt-2 mb-0">
                            <div class="btn--container justify-content-end">
                                <!-- Static Button -->
                                <button id="reset_btn" type="reset" class="btn btn--reset">{{translate('messages.reset')}}</button>
                                <!-- Static Button -->
                                <button type="submit" class="btn btn--primary">{{isset($category)?translate('messages.update'):translate('messages.submit')}}</button>
                            </div>
                        </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header py-2">
                <div class="search--button-wrapper">
                    <h5 class="card-title"><span class="card-header-icon">
                        <i class="tio-category-outlined"></i>
                    </span> {{translate('messages.category')}} {{translate('messages.list')}}<span class="badge badge-soft-dark ml-2" id="itemCount">{{$categories->total()}}</span></h5>
                    <form id="dataSearch">
                        @csrf
                        <!-- Search -->
                        <div class="input--group input-group input-group-merge input-group-flush">
                            <input type="search" name="search" class="form-control" placeholder="{{ translate('Ex : Categories') }}" aria-label="{{translate('messages.search_categories')}}">
                            <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>
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
                            {{--<span class="dropdown-header">{{translate('messages.options')}}</span>
                            <a id="export-copy" class="dropdown-item" href="javascript:;">
                                <img class="avatar avatar-xss avatar-4by3 mr-2"
                                        src="{{asset('public/assets/admin')}}/svg/illustrations/copy.svg"
                                        alt="Image Description">
                                {{translate('messages.copy')}}
                            </a>
                            <a id="export-print" class="dropdown-item" href="javascript:;">
                                <img class="avatar avatar-xss avatar-4by3 mr-2"
                                        src="{{asset('public/assets/admin')}}/svg/illustrations/print.svg"
                                        alt="Image Description">
                                {{translate('messages.print')}}
                            </a>
                            <div class="dropdown-divider"></div>--}}
                            <span class="dropdown-header">{{translate('messages.download')}} {{translate('messages.options')}}</span>
                            <a target="__blank" id="export-excel" class="dropdown-item" href="{{route('admin.category.export-categories', ['type'=>'excel'])}}">
                                <img class="avatar avatar-xss avatar-4by3 mr-2"
                                        src="{{asset('public/assets/admin')}}/svg/components/excel.svg"
                                        alt="Image Description">
                                {{translate('messages.excel')}}
                            </a>
                            <a target="__blank" id="export-csv" class="dropdown-item" href="{{route('admin.category.export-categories', ['type'=>'csv'])}}">
                                <img class="avatar avatar-xss avatar-4by3 mr-2"
                                        src="{{asset('public/assets/admin')}}/svg/components/placeholder-csv-format.svg"
                                        alt="Image Description">
                                .{{translate('messages.csv')}}
                            </a>
                            {{--<a id="export-pdf" class="dropdown-item" href="javascript:;">
                                <img class="avatar avatar-xss avatar-4by3 mr-2"
                                        src="{{asset('public/assets/admin')}}/svg/components/pdf.svg"
                                        alt="Image Description">
                                {{translate('messages.pdf')}}
                            </a>--}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive datatable-custom">
                <table id="columnSearchDatatable"
                    class="table table-borderless table-thead-bordered table-align-middle"
                    data-hs-datatables-options='{
                        "isResponsive": false,
                        "isShowPaging": false,
                        "paging":false,
                    }'>
                    <thead class="thead-light">
                        <tr>
                            <th>{{ translate('messages.sl') }}</th>
                            <th>{{translate('messages.id')}}</th>
                            <th>{{translate('messages.name')}}</th>
                            <th>
                                <div class="ml-3">
                                    {{translate('messages.priority')}}
                                </div>
                            </th>
                            <th>{{translate('messages.status')}}</th>
                            <th class="text-cetner w-130px">{{translate('messages.action')}}</th>
                        </tr>
                    </thead>

                    <tbody id="table-div">
                    @foreach($categories as $key=>$category)
                        <tr>
                            <td>
                                <div class="pl-3">
                                    {{$key+$categories->firstItem()}}
                                </div>
                            </td>
                            <td>
                                <div class="pl-2">
                                    {{$category->id}}
                                </div>
                            </td>
                            <td>
                            <span class="d-block font-size-sm text-body">
                                {{Str::limit($category['name'], 20,'...')}}
                            </span>
                            </td>
                            <td>
                                <form action="{{route('admin.category.priority',$category->id)}}">
                                <select name="priority" id="priority" class=" form-control form--control-select {{$category->priority == 0 ? 'text--title':''}} {{$category->priority == 1 ? 'text--info':''}} {{$category->priority == 2 ? 'text--success':''}} " onchange="this.form.submit()">
                                    <option class="text--title" value="0" {{$category->priority == 0?'selected':''}}>{{translate('messages.normal')}}</option>
                                    <option class="text--info" value="1" {{$category->priority == 1?'selected':''}}>{{translate('messages.medium')}}</option>
                                    <option class="text--success" value="2" {{$category->priority == 2?'selected':''}}>{{translate('messages.high')}}</option>
                                </select>
                                </form>
                            </td>
                            <td>
                                <label class="toggle-switch toggle-switch-sm ml-2" for="stocksCheckbox{{$category->id}}">
                                <input type="checkbox" onclick="location.href='{{route('admin.category.status',[$category['id'],$category->status?0:1])}}'"class="toggle-switch-input" id="stocksCheckbox{{$category->id}}" {{$category->status?'checked':''}}>
                                    <span class="toggle-switch-label">
                                        <span class="toggle-switch-indicator"></span>
                                    </span>
                                </label>
                            </td>
                            <td>
                                <div class="btn--container">
                                    <a class="btn btn-sm btn--primary btn-outline-primary action-btn"
                                        href="{{route('admin.category.edit',[$category['id']])}}" title="{{translate('messages.edit')}} {{translate('messages.category')}}"><i class="tio-edit"></i>
                                    </a>
                                    <a class="btn btn-sm btn--danger btn-outline-danger action-btn" href="javascript:"
                                    onclick="form_alert('category-{{$category['id']}}','{{ translate('Want to delete this category') }}')" title="{{translate('messages.delete')}} {{translate('messages.category')}}"><i class="tio-delete-outlined"></i>
                                    </a>
                                </div>

                                <form action="{{route('admin.category.delete',[$category['id']])}}" method="post" id="category-{{$category['id']}}">
                                    @csrf @method('delete')
                                </form>
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
            <div class="card-footer pt-0 border-0">
                <div class="page-area px-4 pb-3">
                    <div class="d-flex align-items-center justify-content-end">
                        <div>
                            {!! $categories->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script_2')
    <script>
        $(document).on('ready', function () {
            // INITIALIZATION OF DATATABLES
            // =======================================================


            $('#dataSearch').on('submit', function (e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.post({
                    url: '{{route('admin.category.search')}}',
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
    <script>
        $('#reset_btn').click(function(){
            $('#name').val(null);
            $('#viewer').attr('src', "{{asset('public/assets/admin/img/100x100/food-default-image.png')}}");
            $('#customFileEg1').val(null);

        })
    </script>
@endpush
