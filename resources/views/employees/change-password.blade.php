@extends('adminlte::page')
@section('title', 'Change Password')

@section('content_header')
    <h1>Change Password</h1>
@stop

@section('content')
    <p>Change Password for Employee: {{$user->name}}</p>
    {!! Form::open(['action' => ['UsersController@change', $user->id], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
        <div class="form-group">
            {{Form::label('password', 'Password')}}
            {{Form::password('password', ['class' => 'form-control', 'placeholder' => 'Password', 'id' => 'password'])}}
        </div>
        <div class="form-group">
            {{Form::label('password', 'Confirm Password')}}
            {{Form::password('password2', ['class' => 'form-control', 'placeholder' => 'Confirm Password', 'id' => 'password2'])}}
        </div>
        {{Form::hidden('_method', 'PUT')}}
        {{Form::submit('Save', ['class' => 'btn btn-primary', 'id' => 'submit-user'])}}
    {!! Form::close() !!}
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop