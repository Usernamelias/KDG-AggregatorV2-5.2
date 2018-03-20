@extends('master')

@section('title')
    Authtoken | KDG Aggregator
@endsection

@section('content')
<div class="container authTokenContainer">
    <div class="row">
        <div class='col-sm-2'></div>
        
        <div class='col-sm-8' id="authtokenDirections">
            <h3>Directions on Getting Started</h3>
            @include('misc.authTokenDirections')
        </div>

        <div class='col-sm-2'></div>
    </div>
    @include('forms.authtokenForm') 
</div>
@endsection