@extends('layouts.admin.app')

@section('title',translate('Review List'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon rating"><i class="tio-star"></i></span>
                <span>{{translate('messages.deliveryman')}} {{translate('messages.reviews')}}</span> <span class="badge badge-soft-dark ml-2" id="itemCount">{{$reviews->total()}}</span></h1>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <!-- Card -->
                <div class="card">
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
                                <th>{{ translate('messages.sl') }}</th>
                                <th class="w-30p">{{translate('messages.deliveryman')}}</th>
                                <th class="w-25p">{{translate('messages.customer')}}</th>
                                <th>{{translate('messages.review')}}</th>
                                <th>{{translate('messages.rating')}}</th>
                                {{-- <th>{{translate('messages.status')}}</th> --}}
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($reviews as $key=>$review)
                                @if(isset($review->delivery_man))
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>
                                        <span class="d-block font-size-sm text-body">
                                            <a href="{{route('admin.delivery-man.preview',[$review['delivery_man_id']])}}">
                                                {{$review->delivery_man->f_name.' '.$review->delivery_man->l_name}}
                                            </a>
                                        </span>
                                        </td>
                                        <td>
                                            @if ($review->customer)
                                            <a href="{{route('admin.customer.view',[$review->user_id])}}">
                                                {{$review->customer?$review->customer->f_name:""}} {{$review->customer?$review->customer->l_name:""}}
                                            </a>
                                            @else
                                                {{translate('messages.customer_not_found')}}
                                            @endif
                                        </td>
                                        <td>
                                            {{$review->comment}}
                                        </td>
                                        <td>
                                            <label class="rating">
                                                {{$review->rating}} <i class="tio-star"></i>
                                            </label>
                                        </td>
                                        {{-- <td>
                                            <label class="toggle-switch toggle-switch-sm" for="reviewCheckbox{{$review->id}}">
                                                <input type="checkbox" onclick="status_form_alert('status-{{$review['id']}}','{{$review->status?translate('messages.you_want_to_hide_this_review_for_customer'):translate('messages.you_want_to_show_this_review_for_customer')}}', event)" class="toggle-switch-input" id="reviewCheckbox{{$review->id}}" {{$review->status?'checked':''}}>
                                                <span class="toggle-switch-label">
                                                    <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                            <form action="{{route('admin.delivery-man.reviews.status',[$review['id'],$review->status?0:1])}}" method="get" id="status-{{$review['id']}}">
                                            </form>
                                        </td> --}}
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>

                        @if(count($reviews) === 0)
                        <div class="empty--data">
                            <img src="{{asset('/public/assets/admin/img/empty.png')}}" alt="public">
                            <h5>
                                {{translate('no_data_found')}}
                            </h5>
                        </div>
                        @endif

                        <div class="page-area px-4 pb-3">
                            <div class="d-flex align-items-center justify-content-end">
                                {{-- <div>
                                    1-15 of 380
                                </div> --}}
                                <div>
                                    {!! $reviews->links() !!}
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

@endsection

@push('script_2')
    <script>
        $(document).on('ready', function () {
            // INITIALIZATION OF DATATABLES
            // =======================================================
            var datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'));

        });

        function status_form_alert(id, message, e) {
            e.preventDefault();
            Swal.fire({
                title: '{{translate('messages.are_you_sure')}}',
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
                    $('#'+id).submit()
                }
            })
        }
    </script>
@endpush
