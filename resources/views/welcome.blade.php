@extends('layouts.master')

@section('title')
Welcome to Files Cabinet
@endsection

@section('content')
@include('includes.message-block')

<br>
<div class="row justify-content-center">
    <div class="col-md-8 text-center">
        <header>
            <h1>Welcome to the Files Cabinet!</h1>
        </header>
        <h5>A safe Cabinet for your classified files</h5>
        <br />
        <br />
        <p>Please login or register first to use our service</p>
        <a href="{{ route('login') }}"><button class="btn btn-dark mx-2">Login</button></a>
        <a href="{{ route('register') }}"><button class="btn btn-dark mx-2">Register</button></a>
    </div>
</div>
@endsection