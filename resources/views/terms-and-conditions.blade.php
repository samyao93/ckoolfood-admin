@extends('layouts.landing.app')

@section('title',translate('messages.terms_and_condition'))

@section('content')
<div class="h-148px"></div>
    <main>
        <div class="main-body-div">
            <!-- Top Start -->
            <section class="top-start min-h-100px">
                <div class="container">
                    <div class="row">
                        <div class="col-12 mt-2 text-center">
                           <h1>{{translate('messages.terms_and_condition')}}</h1>
                           <br> <br>
                        </div>
                        <div class="col-12">
                            {!! $data !!}
                        </div>
                    </div>
                </div>
            </section>
            <!-- Top End -->
        </div>
    </main>
    <div class="h-148px"></div>
@endsection
