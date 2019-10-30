@extends('adminlte::page')
@section('title', 'Create New Guest')

@section('content_header')
    <h1>Create New Guest</h1>
@stop

@section('content')
    {!! Form::open(['action' => 'GuestController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'style'=>'width:50%; margin:0 auto;']) !!}
        <div class="form-group">
            {{Form::label('title', 'Title')}}
            {{Form::select('title', ['Mr.' => 'Mr.', 'Ms.' => 'Ms.', 'Mrs.' => 'Mrs.'], '', ['class' => 'form-control', 'placeholder' => 'Title', 'id' => 'title'])}}
        </div>
        <div class="form-group">
            {{Form::label('guest_name', 'Guest Name')}}
            {{Form::text('guest_name', '', ['class' => 'form-control', 'placeholder' => 'Guest Name', 'id' => 'guest_name'])}}
        </div>
        <div class="form-group">
            {{Form::label('guest_email', 'Guest Email')}}
            {{Form::text('email', '', ['class' => 'form-control', 'placeholder' => 'Guest Email', 'id' => 'guest_email'])}}
        </div>
        <div class="form-group">
            {{Form::label('passport', 'NIC/Passport')}}
            {{Form::text('passport', '', ['class' => 'form-control', 'placeholder' => 'NIC/Passport', 'id' => 'passport'])}}
        </div>
        <div class="form-group">
            {{Form::label('mobile', 'Phone Number')}}
            {{Form::text('mobile', '', ['class' => 'form-control', 'placeholder' => 'Phone Number', 'id' => 'mobile'])}}
        </div>
        <div class="form-group">
            {{Form::label('organisation', 'Organisation')}}
            {{Form::text('organisation', '', ['class' => 'form-control', 'placeholder' => 'Organisation', 'id' => 'organisation'])}}
        </div>
        <div class="form-group">
            {{Form::label('country', 'Country')}}
            {{Form::text('country', '', ['class' => 'form-control', 'placeholder' => 'Country', 'id' => 'country'])}}
        </div>
        {{Form::submit('Save', ['class' => 'btn btn-primary', 'id' => 'submit-user'])}}
    {!! Form::close() !!}
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop