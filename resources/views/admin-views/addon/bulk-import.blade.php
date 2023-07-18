@extends('layouts.admin.app')

@section('title',translate('AddOn Bulk Import'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">

        <div class="page-header">
            <h1 class="page-header-title text-capitalize">
                <div class="card-header-icon d-inline-flex mr-2 img">
                    <img src="{{asset('/public/assets/admin/img/export.png')}}" alt="">
                </div>
                {{translate('messages.addons')}} {{translate('messages.bulk_import')}}
            </h1>
        </div>
        <!-- Content Row -->
        <div class="card">
            <div class="card-body p-2">
                <div class="export-steps style-2">
                    <div class="export-steps-item">
                        <div class="inner">
                            <h5>{{ translate('STEP 1') }}</h5>
                            <p>
                                {{ translate('Download Excel File') }}
                            </p>
                        </div>
                    </div>
                    <div class="export-steps-item">
                        <div class="inner">
                            <h5>{{ translate('STEP 2') }}</h5>
                            <p>
                                {{ translate('Match Spread sheet data according to instruction') }}
                            </p>
                        </div>
                    </div>
                    <div class="export-steps-item">
                        <div class="inner">
                            <h5>{{ translate('STEP 3') }}</h5>
                            <p>
                                {{ translate('Validate data and and complete import') }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="jumbotron pt-1 pb-4 mb-0 bg-white">
                    <h2 class="mb-3 text-primary">{{ translate('Instructions') }}</h2>
                    <p> {{ translate('1. Download the format file and fill it with proper data.') }}</p>

                    <p>{{ translate('2. You can download the example file to understand how the data must be filled.') }}</p>

                    <p>{{ translate('3. Once you have downloaded and filled the format file, upload it in the form below and submit.') }}</p>
                </div>
                <div class="text-center pb-4">
                    <h3 class="mb-3 export--template-title">{{ translate('Download Spreadsheet Template') }}</h3>
                    <div class="btn--container justify-content-center export--template-btns">
                        <a href="{{asset('public/assets/addons_bulk_format.xlsx')}}" download=""
                            class="btn btn-dark">{{ translate('Template with Existing Data') }}</a>
                        <a href="{{asset('public/assets/addons_bulk_format_nodata.xlsx')}}" download=""
                            class="btn btn-dark">{{ translate('Template without Data') }}</a>
                    </div>
                </div>
            </div>
        </div>

        <form class="product-form" id="import_form" action="{{route('admin.addon.bulk-import')}}" method="POST"
                enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="button" id="btn_value">
            <div class="card mt-2 rest-part">
                <div class="card-body">
                    <h4 class="mb-3">{{ translate('Import Addons File') }}</h4>
                    <div class="custom-file custom--file">
                        <input type="file" name="products_file" class="form-control" id="bulk__import">
                        <label class="custom-file-label" for="bulk__import">{{ translate('Choose File') }}</label>
                    </div>
                </div>
                <div class="card-footer border-0">
                    <div class="btn--container justify-content-end">
                        <button id="reset_btn" type="reset" class="btn btn--reset">{{translate('messages.reset')}}</button>
                        <button type="submit" name="button" value="update" class="btn btn--warning submit_btn">{{translate('messages.update')}}</button>
                        <button type="submit" name="button" value="import" class="btn btn--primary submit_btn">{{translate('messages.Import')}}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('script_2')
<script>
    $('#reset_btn').click(function(){
        $('#bulk__import').val(null);
    })
</script>
    <script>
$(document).on("click", ".submit_btn", function(e){
    e.preventDefault();
        var data = $(this).val();
        myFunction(data)
});
function myFunction(data) {
    Swal.fire({
    title: '{{ translate('Are you sure?') }}' ,
    text: "{{ translate('You_want_to_') }}" +data,
    type: 'warning',
    showCancelButton: true,
    cancelButtonColor: 'default',
    confirmButtonColor: '#FC6A57',
    cancelButtonText: '{{ translate('no') }}',
    confirmButtonText: '{{ translate('yes') }}',
    reverseButtons: true
    }).then((result) => {
        if (result.value) {
            $('#btn_value').val(data);
            $("#import_form").submit();
        }
        // else {
        //     toastr.success("{{ translate('Cancelled') }}");
        // }
    })
}
    </script>
@endpush
