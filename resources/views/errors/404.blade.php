@extends('master')

@section('title')
    404 Error | {{ env('APP_NAME') }}
@endsection

@section('content')

    <div class="container">
        <div class="row httpError">
            <h1>Page not found</h1>
        </div>
    </div>

@endsection