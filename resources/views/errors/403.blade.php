@extends('errors::minimal')

@section('title', translate('Forbidden'))
@section('code', '403')
@section('message', __($exception->getMessage() ?: 'Forbidden'))
