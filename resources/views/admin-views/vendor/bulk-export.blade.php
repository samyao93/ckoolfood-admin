@extends('layouts.admin.app')

@section('title',translate('Restaurant Bulk Export'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title mb-2 text-capitalize">
                <div class="card-header-icon d-inline-flex mr-2 img">
                    <img src="{{asset('/public/assets/admin/img/export.png')}}" alt="">
                </div>
                {{translate('Restaurants Bulk Export')}}
            </h1>
        </div>
        <!-- End Page Header -->

        <div class="card mt-2 rest-part">
            <div class="card-body">
                <div class="export-steps">
                    <div class="export-steps-item">
                        <div class="inner">
                            <h5>{{ translate('STEP 1') }}</h5>
                            <p>
                                {{ translate('Select Data Type') }}
                            </p>
                        </div>
                    </div>
                    <div class="export-steps-item">
                        <div class="inner">
                            <h5>{{ translate('STEP 2') }}</h5>
                            <p>
                                {{ translate('Select Data Range by Date and Export') }}
                            </p>
                        </div>
                    </div>
                </div>
                <form class="product-form" action="{{route('admin.restaurant.bulk-export')}}" method="POST"
                        enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label" for="exampleFormControlSelect1">{{ translate('Select Data Type') }}<span
                                        class="input-label-secondary"></span></label>
                                <select name="type" id="type" data-placeholder="{{translate('messages.select')}} {{translate('messages.type')}}" class="form-control" required title="Select Type">
                                    <option value="all">{{translate('messages.all')}} {{translate('messages.data')}}</option>
                                    <option value="date_wise">{{translate('messages.date')}} {{translate('messages.wise')}}</option>
                                    <option value="id_wise">{{translate('messages.id')}} {{translate('messages.wise')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group id_wise">
                                <label class="form-label" for="exampleFormControlSelect1">{{translate('messages.start')}} {{translate('messages.id')}}<span
                                        class="input-label-secondary"></span></label>
                                <input type="number" min="0" name="start_id" class="form-control">
                            </div>
                            <div class="form-group date_wise">
                                <label class="form-label" for="exampleFormControlSelect1">{{translate('messages.from')}} {{translate('messages.date')}}<span
                                        class="input-label-secondary"></span></label>
                                <input type="date" name="from_date" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group id_wise">
                                <label class="form-label" for="exampleFormControlSelect1">{{translate('messages.end')}} {{translate('messages.id')}}<span
                                        class="input-label-secondary"></span></label>
                                <input type="number" min="0" name="end_id" class="form-control">
                            </div>
                            <div class="form-group date_wise">
                                <label class="form-label text-capitalize" for="exampleFormControlSelect1">{{translate('messages.to')}} {{translate('messages.date')}}<span
                                        class="input-label-secondary"></span></label>
                                <input type="date" name="to_date" class="form-control">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="btn--container justify-content-end">
                                <button id="reset_btn" type="reset" class="btn btn--reset">{{ translate('Clear') }}</button>
                                <button class="btn btn--primary" type="submit">{{translate('messages.export')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script_2')
<script>
    $(document).on('ready', function (){
        $('.id_wise').hide();
        $('.date_wise').hide();
        $('#type').on('change', function()
        {
            $('.id_wise').hide();
            $('.date_wise').hide();
            $('.'+$(this).val()).show();
        })

        $('#reset_btn').click(function(){
            $('#type').val('all').trigger('change');
        })
    });
</script>
@endpush
