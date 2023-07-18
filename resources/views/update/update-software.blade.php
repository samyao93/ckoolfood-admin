@extends('layouts.blank')

@section('content')
    <div class="container">
        <div class="row mt-5">
            <div class="col-2"></div>
            <div class="col-md-8">
                <div class="card mt-3">
                    <div class="card-body">
                        <div class="mar-ver pad-btm text-center mb-3">
                            <h1 class="h3">{{trans('StackFood Software Update')}}</h1>
                            <span class="alert-warning text-danger px-1" role="alert">
                                    {{trans('Note: Update can take more then 5 minutes. Please do not close the window.')}}
                            </span>
                        </div>
                        <div class="d-flex gap-3 align-items-center mt-4">
                            <img
                                src="{{asset('public/assets/installation')}}/assets/img/svg-icons/php-version.svg"
                                alt="">
                            <div
                                class="d-flex align-items-center gap-2 justify-content-between flex-grow-1">
                                @php($phpVersion = number_format((float)phpversion(), 2, '.', ''))
                                @if ($phpVersion < 8.1)
                                <p class="text-danger" >
                                PHP Version 8.1 + Required. Please update your PHP version.
                                </p>
                                @endif

                            </div>
                        </div>
                        <form method="POST" action="{{route('update-system')}}">
                            @csrf
                            <div class="form-group">
                                <label for="purchase_code">{{trans('Codecanyon Username')}}</label>
                                <input type="text" class="form-control" id="username" value="{{env('BUYER_USERNAME')}}"
                                        name="username" required>
                            </div>

                            <div class="form-group">
                                <label for="purchase_code">{{trans('Purchase Code')}}</label>
                                <input type="text" class="form-control" id="purchase_key"
                                        value="{{env('PURCHASE_CODE')}}" name="purchase_key" required>
                            </div>
                            <div class="text-center">
                                <button type="submit" {{ $phpVersion >= 8.1?'':'disabled' }}  class="btn btn-info">{{trans('messages.submit')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-2"></div>
        </div>
    </div>
@endsection
