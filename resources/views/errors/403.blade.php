@extends('master')

@section('title')
    403 Error | {{ env('APP_NAME') }}
@endsection

@section('content')

    <div class="container">
        <div class="row httpError">
            <h1>Forbidden</h1>
        </div>
    </div>

@endsection
