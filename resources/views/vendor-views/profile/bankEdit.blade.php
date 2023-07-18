@extends('layouts.vendor.app')

@section('title',translate('messages.bank_info'))

@push('css_or_js')
    <!-- Custom styles for this page -->
    <link href="{{asset('public/assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <!-- Custom styles for this page -->
@endpush

@section('content')
    {{--<div class="content container-fluid">
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h1 class="h3 mb-0 ">{{translate('messages.edit_bank_info')}}</h1>
                    </div>
                    <div class="card-body">
                        <form action="{{route('vendor.profile.bank_update')}}" method="post"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="name">{{translate('messages.bank_name')}} <span class="text-danger">*</span></label>
                                        <input type="text" name="bank_name" value="{{$data->bank_name}}"
                                               class="form-control" id="name"
                                               required maxlength="191">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="name">{{translate('messages.branch')}} {{translate('messages.name')}}<span class="text-danger">*</span></label>
                                        <input type="text" name="branch" value="{{$data->branch}}" class="form-control"
                                               id="name"
                                               required maxlength="191">
                                    </div>
                                </div>

                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="account_no">{{translate('messages.holder_name')}} <span class="text-danger">*</span></label>
                                        <input type="text" name="holder_name" value="{{$data->holder_name}}"
                                               class="form-control" id="account_no"
                                               required maxlength="191">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="account_no">{{translate('messages.account_no')}} <span class="text-danger">*</span></label>
                                        <input type="text" name="account_no" value="{{$data->account_no}}"
                                               class="form-control" id="account_no"
                                               required maxlength="191">
                                    </div>

                                </div>

                            </div>

                            <button type="submit" class="btn btn-primary" id="btn_update">{{translate('messages.update')}}</button>
                            <a class="btn btn-danger" href="{{route('vendor.profile.bankView')}}">{{translate('messages.cancel')}}</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>--}}
@endsection

@push('script')
    <!-- Page level plugins -->
    <script src="{{asset('public/assets/back-end/js/croppie.js')}}"></script>
@endpush
