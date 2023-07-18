@extends('layouts.admin.app')
@section('title', translate('Contact Messages'))
@push('css_or_js')
    <!-- Custom styles for this page -->
    {{-- <link href="{{asset('public/assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"> --}}
@endpush

@section('content')
<div class="content container-fluid">
    <div class="card mt-2">
        <div class="card-header py-2 border-0">
            <div class="search--button-wrapper">
                <h5 class="card-title"> {{translate('messages.Message')}} {{translate('messages.list')}}<span class="badge badge-soft-dark ml-2" id="itemCount">{{$contacts->total()}}</span></h5>

                <form id="search-form">
                    <div class="input--group input-group input-group-merge input-group-flush">
                        <input id="datatableSearch" type="search" name="search" class="form-control" placeholder="{{ translate('messages.Ex :') }} {{translate('Search_by_name_or_email')}}" aria-label="Search contacts">
                        <button type="submit" class="btn btn--secondary">
                            <i class="tio-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive datatable-custom">
                <table id="datatable" class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"  data-hs-datatables-options='{
                    "search": "#datatableSearch",
                    "entries": "#datatableEntries",
                    "isResponsive": false,
                    "isShowPaging": false,
                    "paging":false
                        }'>
                    <thead class="thead-light">
                    <tr>
                        <th style="width: 5%">{{translate('sl')}}</th>
                        <th class="text-center" style="width: 15%">{{translate('messages.name')}}</th>
                        <th class="text-center" style="width: 15%">{{translate('messages.email')}}</th>
                        <th class="text-center" style="width: 50%">{{translate('messages.message')}}</th>
                        <th class="text-center" style="width: 7%">{{translate('messages.status')}}</th>
                        <th class="text-center" style="width: 8%">{{translate('messages.action')}}</th>
                    </tr>
                    </thead>

                    <tbody id="set-rows">
                        @foreach($contacts as $k=>$contact)
                        <tr>
                            <td style="width: 5%">{{$contacts->firstItem()+$k}}</td>
                            <td style="width: 15%">
                            <span class="d-block font-size-sm text-body text-center">
                                {{Str::limit($contact['name'],20,'...')}}
                            </span>
                            </td>
                            <td style="width: 15%">
                                <div class="text-right max-130px">
                                    {{$contact['email']}}
                                </div>
                            </td>
                            <td class="text-center" style="width: 50%;">{{Str::limit($contact['message'],120,'...') }}</td>
                            <td style="width: 7%;">
                                @if ($contact->seen == 1)
                                <label class="badge badge-success">{{ translate('Seen') }}</label>
                            @else
                                <label class="badge badge-primary">{{ translate('Not_replied_Yet') }}</label>
                            @endif
                            </td>

                        <td style="width: 8%">
                            <div class="btn--container justify-content-center">
                                <a  title="{{translate('View')}}"
                                class="btn btn-sm btn--warning btn-outline-warning action-btn" style="cursor: pointer;"
                                href="{{route('admin.contact.view',$contact->id)}}">
                                <i class="tio-visible"></i>
                            </a>

                            <a class="btn btn-sm btn--danger btn-outline-danger action-btn"   href="javascript:"
                                        onclick="form_alert('contact-{{$contact['id']}}','{{ translate('messages.Want_to_delete_this_contact_message_?') }}')" title="{{translate('messages.delete')}} {{translate('messages.contact')}}"><i class="tio-delete-outlined"></i></a>
                                <form action="{{route('admin.contact.delete')}}"
                                                method="post" id="contact-{{$contact['id']}}">
                                                <input type="hidden" name="id" value="{{ $contact['id'] }}">
                                        @csrf @method('delete')
                                    </form>
                            </div>

                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
                @if(count($contacts) === 0)
                <div class="empty--data">
                    <img src="{{asset('/public/assets/admin/img/empty.png')}}" alt="public">
                    <h5>
                        {{translate('no_data_found')}}
                    </h5>
                </div>
                @endif
            </div>
        </div>
        <div class="card-footer p-0 border-0">
            <!-- Pagination -->
            <div class="page-area px-4 pb-3">
                <div class="d-flex align-items-center justify-content-end">

                    <div>
                        {!! $contacts->links() !!}
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
            var datatable = $.HSCore.components.HSDatatables.init($('#datatable'), {
            select: {
                style: 'multi',
                classMap: {
                checkAll: '#datatableCheckAll',
                counter: '#datatableCounter',
                counterInfo: '#datatableCounterInfo'
                }
          },
          language: {
            zeroRecords: '<div class="text-center p-4">' +
                '<img class="w-7rem mb-3" src="{{asset('public/assets/admin/svg/illustrations/sorry.svg')}}" alt="Image Description">' +
                '<p class="mb-0">{{ translate('No data to show') }}</p>' +
                '</div>'
          }
        });

        });

    </script>

@endpush
