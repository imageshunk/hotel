@extends('adminlte::page')
@section('title', 'Create Items')

@section('content_header')
    <h1>Create Item</h1>
@stop

@section('content')
    {!! Form::open(['action' => 'ItemController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
        <div class="form-group">
            {{Form::label('item_name', 'Item Name')}}
            {{Form::text('item_name', '', ['class' => 'form-control', 'placeholder' => 'Item Name', 'id' => 'item_name'])}}
        </div>
        <div class="form-group">
            <?php $setting = App\Setting::first(); ?>
            {{Form::label('item_price', 'Item Price (in '.$setting->currency.')')}}
            {{Form::text('item_price', '', ['class' => 'form-control', 'placeholder' => 'Item Price', 'id' => 'item_price'])}}
        </div>
        <div class="form-group">
            {{Form::label('qty', 'Available Quantity')}}
            {{Form::number('qty', '', ['class' => 'form-control', 'placeholder' => 'Available Quantity', 'id' => 'item_price'])}}
        </div>
        <div class="form-group">
            {{Form::label('item_category', 'Item Category')}}
            <?php $categories = App\Category::latest()->get(); ?>
            <select name="item_category" id="item_category" class="form-control">
                @foreach($categories as $category)
                    <option value="{{$category->id}}">{{$category->category}}</option>
                @endforeach
            </select>
        </div>
        {{Form::submit('Save', ['class' => 'btn btn-primary', 'id' => 'submit-user'])}}
    {!! Form::close() !!}
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop