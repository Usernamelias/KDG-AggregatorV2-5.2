@extends('master')

@section('title')
    500 Error | {{ env('APP_NAME') }}
@endsection

@section('content')

    <div class="container">
        <div class="row httpError">
            <h1>Internal Server Error</h1>
        </div>
    </div>

@endsection
