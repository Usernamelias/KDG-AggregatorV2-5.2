@extends('master')

@section('title')
    Settings | KDG Aggregator
@endsection

@push('head')
  <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class='container'>
        <h2>Generate and Store New Auth Token</h2>
        <hr>
        <div class='row'>
            <div class='col-sm-2'></div>
            <div class='col-sm-8' id="authtokenDirections">
                @include('misc.authTokenDirections')
            </div>
            <div class='col-sm-2'></div>
        </div>
        @include('forms.authtokenForm')
        <h2>Reset Password</h2>
        <hr>
        @include('forms.resetPasswordForm')
    </div>
@endsection

@push('body')

@endpush