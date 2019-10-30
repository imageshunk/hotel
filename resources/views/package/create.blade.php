@extends('adminlte::page')

@section('title', 'Create Room Type')

@section('content_header')
    <h1>Create Room Type</h1>
@stop

@section('content')
    {!! Form::open(['action' => 'PackagesController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
        <div class="form-group">
            {{Form::label('package_name', 'Room Type Name')}}
            {{Form::text('package_name', '', ['class' => 'form-control', 'placeholder' => 'Room Type Name'])}}
        </div>
        {{Form::submit('Save', ['class' => 'btn btn-primary'])}}
    {!! Form::close() !!}
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop