@extends('adminlte::page')

@section('title', 'Edit Utility')

@section('content_header')
    <h1>Edit {{$utility->utility}}</h1>
@stop

@section('content')
    {!! Form::open(['action' => ['UtilityController@update', $utility->id], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
        <div class="form-group">
            {{Form::label('utility', 'Utility')}}
            {{Form::text('utility', $utility->utility, ['class' => 'form-control', 'placeholder' => 'Utility'])}}
        </div>
        <div class="form-group">
            {{Form::label('description', 'Description')}}
            {{Form::text('description', $utility->description, ['class' => 'form-control', 'placeholder' => 'Description'])}}
        </div>
        <div class="form-group">
            <?php $setting = App\Setting::first(); ?>
            {{Form::label('price', 'Price (in '.$setting->currency.')')}}
            {{Form::number('price', $utility->price, ['class' => 'form-control', 'placeholder' => 'Price'])}}
        </div>
        {{Form::hidden('_method', 'PUT')}}
        {{Form::submit('Save', ['class' => 'btn btn-primary'])}}
    {!! Form::close() !!}
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop