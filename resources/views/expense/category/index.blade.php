@extends('adminlte::page')
@section('title', 'Expense Categories')

@section('content_header')
    <h1>Expense Categories</h1>
@stop

@section('content')
    <p><a href="/expense-categories/create" class="btn btn-success">Create a Category</a></p>
    @if(count($categories)>0)
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Expense Categories</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th style="width: 20px">#</th>
                            <th>Category</th>
                            <th>Time</th>
                            <th colspan="2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                            <tr>
                                <td>{{$category->id}}</td>
                                <td>{{$category->name}}</td>
                                <td>{{ \Carbon\Carbon::parse($category->created_at)->diffForHumans() }}</td>
                                <td><a href="/expense-categories/{{$category->id}}/edit" class="btn btn-primary btn-sm">Edit</a></td>
                                <td>
                                    {{Form::open(['action'=>['ExpenseCategoryController@destroy', $category->id], 'method'=>'POST'])}}
                                        {{Form::hidden('_method', 'DELETE')}}
                                        {{Form::Submit('Delete', ['class'=>'btn btn-sm btn-danger'])}}
                                    {{Form::close()}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <p>No Expense Category Found</p>
    @endif
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')

@stop