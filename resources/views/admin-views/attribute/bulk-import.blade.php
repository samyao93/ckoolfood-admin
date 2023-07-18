@extends('layouts.admin.app')

@section('title',translate('Attribute Bulk Import'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{translate('messages.dashboard')}}</a>
                </li>
                <li class="breadcrumb-item" aria-current="page"><a
                        href="{{route('admin.attribute.add-new')}}">{{translate('messages.foods')}}</a>
                </li>
                <li class="breadcrumb-item">{{translate('messages.bulk_import')}} </li>
            </ol>
        </nav>
        <h1 class="text-capitalize">{{translate('messages.attributes')}} {{translate('messages.bulk_import')}}</h1>
        <!-- Content Row -->
        <div class="row">
            <div class="col-12">
                <div class="jumbotron pt-1 bg-white">
                    <h2 class="mb-3 text-primary">{{ translate('Instructions') }}</h2>
                    <p> {{ translate('1. Download the format file and fill it with proper data.') }}</p>

                    <p>{{ translate('2. You can download the example file to understand how the data must be filled.') }}</p>

                    <p>{{ translate('3. Once you have downloaded and filled the format file, upload it in the form below and
                        submit.') }}</p>

                </div>
            </div>

            <div class="col-md-12">
                <form class="product-form" action="{{route('admin.attribute.bulk-import')}}" method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="card mt-2 rest-part">
                        <div class="card-header">
                            <h4>{{ translate('Import Attributes File') }}</h4>
                            <a href="{{asset('public/assets/attributes_bulk_format.xlsx')}}" download=""
                               class="btn btn-secondary">{{ translate('Download Format') }}</a>
                        </div>
                        <div class="card-body">
                            <h4 class="mb-3">{{ translate('Import Addons File') }}</h4>
                            <div class="custom-file custom--file">
                                <input type="file" name="products_file" class="form-control" id="bulk__import">
                                <label class="custom-file-label" for="bulk__import">{{ translate('Choose File') }}</label>
                            </div>
                        </div>
                        <div class="card card-footer">
                            <div class="row">
                                <div class="col-md-12 pt-4">
                                    <button type="submit" class="btn btn--primary">{{ translate('Submit') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')

@endpush
