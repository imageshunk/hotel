@extends('adminlte::page')
@section('title', 'Categories')

@section('content_header')
    <h1>Categories</h1>
@stop

@section('content')
    <a href="/categories/create" class="btn btn-primary">Create New</a>
    <br><br>
    @if(count($categories)>0)
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Categories</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <th style="width: 20px">ID</th>
                            <th>Category</th>
                            <th>Created At</th>
                        </tr>
                        @foreach($categories as $category)
                            <tr>
                                <td>{{$category->id}}</td>
                                <td>{{$category->category}}</td>
                                <td>{{ \Carbon\Carbon::parse($category->created_at)->diffForHumans() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
                {{$categories->links()}}
            </div>
        </div>
    @else
        <p>No Categories Found</p>
    @endif
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop