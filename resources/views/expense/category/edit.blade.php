@extends('adminlte::page')
@section('title', 'Edit Expense Category')

@section('content_header')
    <h1>Edit Expense Category</h1>
@stop

@section('content')
    {{Form::open(['action'=>['ExpenseCategoryController@update', $category->id], 'method'=>'POST'])}}
        <div class="form-group form-inline">
            {{Form::text('expense_category', $category->name, ['class'=>'form-control', 'placeholder'=>'Category...'])}}
            {{Form::hidden('_method', 'PUT')}}
            {{Form::submit('Save',['class'=>'btn btn-success'])}}
        </div>
    {{Form::close()}}
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')

@stop