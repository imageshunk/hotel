@extends('adminlte::page')

@section('title', 'Create New Utility')

@section('content_header')
    <h1>Create New Utility</h1>
@stop

@section('content')
    {!! Form::open(['action' => 'UtilityController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
        <div class="form-group">
            {{Form::label('utility', 'Utility')}}
            {{Form::text('utility', '', ['class' => 'form-control', 'placeholder' => 'Utility'])}}
        </div>
        <div class="form-group">
            {{Form::label('description', 'Description')}}
            {{Form::text('description', '', ['class' => 'form-control', 'placeholder' => 'Description'])}}
        </div>
        <div class="form-group">
            <?php
                $setting = App\Setting::first();
                if($setting->currency == null){
                    $setting->currency = 'USD';
                }
            ?>
            {{Form::label('price', 'Price (in '.$setting->currency.')')}}
            {{Form::number('price', '', ['class' => 'form-control', 'placeholder' => 'Price'])}}
        </div>
        {{Form::submit('Save', ['class' => 'btn btn-primary'])}}
    {!! Form::close() !!}
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop