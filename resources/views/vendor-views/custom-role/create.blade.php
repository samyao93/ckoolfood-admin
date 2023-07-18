@extends('layouts.vendor.app')
@section('title',translate('Create Role'))
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
                        <img src="{{asset('/public/assets/admin/img/resturant-panel/page-title/employee-role.png')}}" alt="public">
                    </div>
                    <span>
                        {{ translate('Employee Role') }}
                    </span>
                </h2>
            </div>
        </div>
    </div>
    <!-- End Page Header -->

    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title my-1">
                        <span class="card-header-icon">
                            <i class="tio-document-text-outlined"></i>
                        </span>
                        <span>
                            {{translate('messages.role_form')}}
                        </span>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="px-xl-2">
                        <form action="{{route('vendor.custom-role.create')}}" method="post">
                            @csrf


                            @php($language=\App\Models\BusinessSetting::where('key','language')->first())
                            @php($language = $language->value ?? null)
                            @php($default_lang = str_replace('_', '-', app()->getLocale()))
                            @if ($language)
                            <ul class="nav nav-tabs mb-4">
                                <li class="nav-item">
                                    <a class="nav-link lang_link active"
                                    href="#"
                                    id="default-link">{{translate('messages.default')}}</a>
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


                            <input type="hidden" name="lang[]" value="default">

                            <div class="form-group lang_form" id="default-form">
                                <label class="form-label input-label qcont" for="name">{{ translate('messages.role_name') }} ({{ translate('messages.default') }})</label>
                                <input type="text" name="name[]" class="form-control" placeholder="{{translate('role_name_example')}}" maxlength="191"  oninvalid="document.getElementById('en-link').click()">
                            </div>



                            @if ($language)
                            @foreach(json_decode($language) as $lang)
                                <div class="form-group d-none lang_form" id="{{$lang}}-form">
                                    <label class="input-label" for="exampleFormControlInput1">{{translate('messages.role_name')}} ({{strtoupper($lang)}})</label>
                                    <input type="text" name="name[]" class="form-control" placeholder="{{translate('role_name_example')}}" maxlength="191" oninvalid="document.getElementById('en-link').click()">
                                </div>
                                <input type="hidden" name="lang[]" value="{{$lang}}">
                            @endforeach
                            @endif


                            <h5 class="form-label">{{translate('messages.module_permission')}} : </h5>
                            <div class="check--item-wrapper mx-0">
                                <div class="check-item">
                                    <div class="form-group form-check form--check">
                                        <input type="checkbox" name="modules[]" value="food" class="form-check-input"
                                            id="food">
                                        <label class="form-check-label input-label qcont" for="food">{{translate('messages.food')}}</label>
                                    </div>
                                </div>
                                <div class="check-item">
                                    <div class="form-group form-check form--check">
                                        <input type="checkbox" name="modules[]" value="order" class="form-check-input"
                                            id="order">
                                        <label class="form-check-label input-label qcont" for="order">{{translate('messages.order')}}</label>
                                    </div>
                                </div>
                                <div class="check-item">
                                    <div class="form-group form-check form--check">
                                        <input type="checkbox" name="modules[]" value="restaurant_setup" class="form-check-input"
                                            id="restaurant_setup">
                                        <label class="form-check-label input-label qcont" for="restaurant_setup">{{translate('messages.restaurant')}} {{translate('messages.setup')}}</label>
                                    </div>
                                </div>
                                <div class="check-item">
                                    <div class="form-group form-check form--check">
                                        <input type="checkbox" name="modules[]" value="addon" class="form-check-input"
                                            id="addon">
                                        <label class="form-check-label input-label qcont" for="addon">{{translate('messages.addon')}}</label>
                                    </div>
                                </div>
                                <div class="check-item">
                                    <div class="form-group form-check form--check">
                                        <input type="checkbox" name="modules[]" value="wallet" class="form-check-input"
                                            id="wallet">
                                        <label class="form-check-label input-label qcont" for="wallet">{{translate('messages.wallet')}}</label>
                                    </div>
                                </div>
                                <div class="check-item">
                                    <div class="form-group form-check form--check">
                                        <input type="checkbox" name="modules[]" value="bank_info" class="form-check-input"
                                            id="bank_info">
                                        <label class="form-check-label input-label qcont" for="bank_info">{{translate('messages.bank_info')}}</label>
                                    </div>
                                </div>
                                <div class="check-item">
                                    <div class="form-group form-check form--check">
                                        <input type="checkbox" name="modules[]" value="employee" class="form-check-input"
                                            id="employee">
                                        <label class="form-check-label input-label qcont" for="employee">{{translate('messages.Employee')}}</label>
                                    </div>
                                </div>
                                <div class="check-item">
                                    <div class="form-group form-check form--check">
                                        <input type="checkbox" name="modules[]" value="my_shop" class="form-check-input"
                                            id="my_shop">
                                        <label class="form-check-label input-label qcont" for="my_shop">{{translate('messages.my_shop')}}</label>
                                    </div>
                                </div>
                                <div class="check-item">
                                    <div class="form-group form-check form--check">
                                        <input type="checkbox" name="modules[]" value="chat" class="form-check-input"
                                            id="chat">
                                        <label class="form-check-label input-label qcont" for="chat">{{ translate('messages.chat')}}</label>
                                    </div>
                                </div>
                                {{-- <div class="check-item">
                                    <div class="form-group form-check form--check">
                                        <input type="checkbox" name="modules[]" value="custom_role" class="form-check-input"
                                            id="custom_role">
                                        <label class="form-check-label input-label qcont" for="custom_role">{{translate('messages.custom_role')}}</label>
                                    </div>
                                </div> --}}
                                <div class="check-item">
                                    <div class="form-group form-check form--check">
                                        <input type="checkbox" name="modules[]" value="campaign" class="form-check-input"
                                            id="campaign">
                                        <label class="form-check-label input-label qcont" for="campaign">{{translate('messages.campaign')}}</label>
                                    </div>
                                </div>
                                <div class="check-item">
                                    <div class="form-group form-check form--check">
                                        <input type="checkbox" name="modules[]" value="reviews" class="form-check-input"
                                            id="reviews">
                                        <label class="form-check-label input-label qcont" for="reviews">{{translate('messages.reviews')}}</label>
                                    </div>
                                </div>
                                <div class="check-item">
                                    <div class="form-group form-check form--check">
                                        <input type="checkbox" name="modules[]" value="pos" class="form-check-input"
                                            id="pos">
                                        <label class="form-check-label input-label qcont" for="pos">{{translate('messages.pos')}}</label>
                                    </div>
                                </div>
                                @php($restaurant_data = \App\CentralLogics\Helpers::get_restaurant_data())
                                @if ($restaurant_data->restaurant_model != 'commission')
                                <div class="check-item">
                                    <div class="form-group form-check form--check">
                                        <input type="checkbox" name="modules[]" value="subscription" class="form-check-input"
                                        id="subscription">
                                        <label class="form-check-label input-label qcont" for="subscription">{{translate('messages.subscription')}}</label>
                                    </div>
                                </div>
                                @endif

                                <div class="check-item">
                                    <div class="form-group form-check form--check">
                                        <input type="checkbox" name="modules[]" value="coupon" class="form-check-input"
                                            id="coupon">
                                        <label class="form-check-label input-label qcont" for="coupon">{{translate('messages.coupon')}}</label>
                                    </div>
                                </div>
                                <div class="check-item">
                                    <div class="form-group form-check form--check">
                                        <input type="checkbox" name="modules[]" value="report" class="form-check-input"
                                            id="report">
                                        <label class="form-check-label input-label qcont" for="report">{{translate('messages.report')}}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="btn--container mt-4 justify-content-end">
                                <button type="reset" class="btn btn--reset">{{translate('messages.reset')}}</button>
                                <button type="submit" class="btn btn--primary">{{translate('messages.submit')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header border-0 py-2">
                    <div class="search--button-wrapper">
                        <h5 class="card-title">{{translate('messages.roles_table')}}<span class="badge badge-soft-dark ml-2" id="itemCount">{{$rl->total()}}</span></h5>
                        <form action="javascript:" id="search-form">
                            @csrf
                            <!-- Search -->
                            <div class="input-group input--group">
                                <input id="datatableSearch_" type="search" name="search" class="form-control" placeholder="{{ translate('messages.Ex :') }}  {{ translate('Search by Role Name') }}" aria-label="{{translate('messages.search')}}">
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
                               class="table table-borderless table-thead-bordered table-align-middle card-table"
                               data-hs-datatables-options='{
                                 "order": [],
                                 "orderCellsTop": true,
                                 "paging":false
                               }'>
                            <thead class="thead-light">
                                <tr>
                                    <th class="w-70px">{{ translate('messages.sl') }}</th>
                                    <th class="w-100px">{{translate('messages.role_name')}}</th>
                                    <th class="w-200px">{{translate('messages.modules')}}</th>
                                    <th class="w-80px">{{translate('messages.created_at')}}</th>
                                    {{--<th class="w-80px">{{translate('messages.status')}}</th>--}}
                                    <th scope="col" class="w-80px text-center">{{translate('messages.action')}}</th>
                                </tr>
                            </thead>
                            <tbody  id="set-rows">
                            @foreach($rl as $k=>$r)
                                <tr>
                                    <td scope="row">{{$k+$rl->firstItem()}}</td>
                                    <td>{{Str::limit($r['name'],20,'...')}}</td>
                                    <td class="text-capitalize">
                                        @if($r['modules']!=null)
                                            @foreach((array)json_decode($r['modules']) as $key=>$m)
                                               {{translate(str_replace('_',' ',$m))}},
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>
                                    {{  Carbon\Carbon::parse($r['created_at'])->locale(app()->getLocale())->translatedFormat('d M Y') }}
                                        {{-- {{date('d-M-y',strtotime($r['created_at']))}} --}}
                                    </td>
                                    {{--<td>
                                        {{$r->status?'Active':'Inactive'}}
                                    </td>--}}
                                    <td>
                                        <div class="btn--container justify-content-center">
                                            <a class="btn action-btn btn--primary btn-outline-primary"
                                                href="{{route('vendor.custom-role.edit',[$r['id']])}}" title="{{translate('messages.edit')}} {{translate('messages.role')}}"><i class="tio-edit"></i>
                                            </a>
                                            <a class="btn action-btn btn--danger btn-outline-danger" href="javascript:"
                                                onclick="form_alert('role-{{$r['id']}}','{{translate('messages.Want_to_delete_this_role')}}')" title="{{translate('messages.delete')}} {{translate('messages.role')}}"><i class="tio-delete-outlined"></i>
                                            </a>
                                            <form action="{{route('vendor.custom-role.delete',[$r['id']])}}"
                                                    method="post" id="role-{{$r['id']}}">
                                                @csrf @method('delete')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        @if(count($rl) === 0)
                        <div class="empty--data">
                            <img src="{{asset('/public/assets/admin/img/empty.png')}}" alt="public">
                            <h5>
                                {{translate('no_data_found')}}
                            </h5>
                        </div>
                        @endif
                        <div class="page-area">
                            <table>
                                <tfoot>
                                {!! $rl->links() !!}
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script_2')
    <script>
        $('#search-form').on('submit', function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('vendor.custom-role.search')}}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    $('#set-rows').html(data.view);
                    $('.page-area').hide();
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        });
        $(document).ready(function() {
            var datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'));
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
@endpush
