@extends('adminlte::page')
@section('title', 'Add Expense Categories')

@section('content_header')
    <h1>Add Expense Categories</h1>
@stop

@section('content')
    {{Form::open(['action'=>'ExpenseCategoryController@store', 'method'=>'POST'])}}
        <div class="form-group form-inline">
            {{Form::text('expense_category', '', ['class'=>'form-control', 'placeholder'=>'Category...'])}}
            {{Form::submit('Save',['class'=>'btn btn-success'])}}
        </div>
    {{Form::close()}}
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')

@stop