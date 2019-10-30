@extends('adminlte::page')
@section('title', 'Create Categories')

@section('content_header')
    <h1>Create Categories</h1>
@stop

@section('content')
    {!! Form::open(['action' => 'CategoryController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
        <div class="form-group">
            {{Form::label('category', 'Category')}}
            {{Form::text('category', '', ['class' => 'form-control', 'placeholder' => 'Category', 'id' => 'category'])}}
        </div>
        {{Form::submit('Save', ['class' => 'btn btn-primary', 'id' => 'submit-user'])}}
    {!! Form::close() !!}
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop