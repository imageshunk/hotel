@extends('adminlte::page')

@section('title', 'Edit Room Type')

@section('content_header')
    <h1>Edit Room Type</h1>
@stop

@section('content')
    {!! Form::open(['action' => ['PackagesController@update', $package->id], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
        <div class="form-group">
            {{Form::label('package_name', 'Room Type Name')}}
            {{Form::text('package_name', $package->package_name, ['class' => 'form-control', 'placeholder' => 'Room Type Name'])}}
        </div>
        <div class="form-group">
            {{Form::label('package_price', 'Room Type Price')}}
            {{Form::text('package_price', $package->package->price, ['class' => 'form-control', 'placeholder' => 'Room Type Price Excluding Tax'])}}
        </div>
        {{Form::hidden('_method','PUT')}}
        {{Form::submit('Save', ['class' => 'btn btn-primary'])}}
    {!! Form::close() !!}
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop