@extends('layouts.admin.app')
@section('title', translate('Contact View'))
{{-- @push('css_or_js')
    <link href="{{asset('public/assets/back-end')}}/css/select2.min.css" rel="stylesheet"/>
    <link href="{{asset('public/assets/back-end/css/croppie.css')}}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush --}}

@section('content')



    <div class="content container-fluid">




        <div class="mt-4">
            <div id="restaurant_details" class="row g-3">

                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="card-title m-0 d-flex align-items-center">
                                <span class="card-header-icon mr-2">
                                    <i class="tio-email"></i>
                                </span>
                                <span class="ml-1">{{ translate('messages.Contact') }} {{ translate('messages.Message') }}
                                    &nbsp;

                                </span>
                            </h5>
                        </div>
                        <div class="card-body d-flex flex-column justify-content-center">
                            <div class="card-body d-flex flex-column justify-content-center">
                                <ul class="list-unstyled list-unstyled-py-3 text-dark">
                                    <li class="pb-0 pt-0">
                                        <strong class="text--title">{{ translate('messages.Status') }} :</strong>
                                        @if ($contact->seen == 1)
                                            <label class="badge badge-success">{{ translate('Seen') }}</label>
                                        @else
                                            <label class="badge badge-primary">{{ translate('Not_replied_Yet') }}</label>
                                        @endif
                                    </li>
                                    <li class="pb-2 pt-2">
                                        <strong class="text--title">{{ translate('messages.Name') }}:</strong>
                                        {{ $contact->name }}
                                    </li>
                                    <li class="pb-2 pt-2">
                                        <strong class="text--title">{{ translate('messages.Email') }} :</strong>
                                        {{ $contact->email }}
                                    </li>
                                    <li class="pb-2 pt-2">
                                        <strong class="text--title">{{ translate('messages.Message') }} :</strong>
                                        {{ $contact->message }}
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="card-title m-0 d-flex align-items-center">
                                <span class="card-header-icon mr-2">
                                    <i class="tio-chat nav-icon"></i>
                                </span>
                                <span class="ml-1">{{ translate('Sent_a_reply') }}</span>
                            </h5>
                        </div>
                        <div class="card-body d-flex flex-column justify-content-center">
                            <form action="{{ route('admin.contact.send-mail', $contact->id) }}" method="post">
                                @csrf
                                <div class="form-group">
                                    <h6>{{ translate('Subject') }}</h6>
                                    <input class="form-control" value="{{ old('subject') }}" name="subject">
                                    <br>
                                    <h6>{{ translate('Mail_Body') }}</h6>
                                    <textarea class="form-control " name="mail_body" id="" rows="5"
                                        placeholder="{{ translate('Please_send_a_Feedback') }}">{{ old('mail_body') }}</textarea>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary float-right">
                                        <i class="fa fa-check"></i>{{ translate('send') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @php($data = json_decode($contact->reply, true))
                @if (isset($data['subject']) && isset($data['body']))
                    <div class="col-lg-12">
                        <div class="card h-100">
                            <div class="card-header">
                                <h5 class="card-title m-0 d-flex align-items-center">
                                    <span class="card-header-icon mr-2">
                                        <i class="tio-chat nav-icon"></i>
                                    </span>
                                    <span class="ml-1">{{ translate('Reply') }}</span>
                                </h5>
                            </div>
                            <div class="card-body d-flex flex-column justify-content-center">

                                <ul class="list-unstyled list-unstyled-py-3 text-dark">
                                    <li class="pb-1 pt-1">
                                        <strong class="text--title">{{ translate('messages.Subject') }}:</strong>
                                        {{ $data['subject'] }}
                                    </li>
                                    <li class="pb-2 pt-2">
                                        <strong class="text--title">{{ translate('messages.Reply') }} :</strong>
                                        {{ $data['body'] }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>


    </div>










@endsection

@push('script')
@endpush
