@extends('master')

@section('title')
    505 Error | {{ env('APP_NAME') }}
@endsection

@section('content')

    <div class="container">
        <div class="row httpError">
            <h1>500 Unknown Error</h1>
        </div>
    </div>

@endsection