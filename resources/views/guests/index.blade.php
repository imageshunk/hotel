@extends('adminlte::page')
@section('title', 'Guests')

@section('content_header')
    <h1>Guests</h1>
@stop

@section('content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Guests</h3>
            <div class="box-tools">
                {{Form::open(['action'=>'GuestController@search', 'method'=>'POST'])}}
                    <div class="form-inline">
                        <label style="margin-left:20px;">Search: </label>
                        <input type="text" class="form-control" id="search" name="search">
                        <button type="submit" class="btn btn-primary">Go</button>
                    </div>
                {{Form::close()}}
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body no-padding">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th style="width: 20px">#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>NIC/Passport</th>
                        <th>Phone Number</th>
                        <th>Organisation</th>
                        <th>Country</th>
                        <th colspan="2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($guests as $guest)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$guest->title}} {{$guest->name}}</td>
                            <td>{{$guest->email}}</td>
                            <td>{{$guest->passport}}</td>
                            <td>{{$guest->mobile}}</td>
                            <td>{{$guest->organisation}}</td>
                            <td>{{$guest->country}}</td>
                            <td><a href="/guests/{{$guest->id}}/edit" class="btn btn-sm btn-primary">Edit</a></td>
                            <td>
                                {{Form::open(['action'=>['GuestController@destroy', $guest->id], 'method'=>'POST'])}}
                                    {{Form::hidden('_method', 'DELETE')}}
                                    {{Form::submit('Remove', ['class'=>'btn btn-danger btn-sm'])}}
                                {{Form::close()}}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="box-footer">
            {{$guests->links()}}
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')

@stop