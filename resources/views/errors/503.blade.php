@extends('master')

@section('title')
    503 Error | {{ env('APP_NAME') }}
@endsection

@section('content')

    <div class="container">
        <div class="row httpError">
            <h1>Service Unavailable</h1>
        </div>
    </div>

@endsection
