@extends('adminlte::page')
@section('title', 'Employees')

@section('content_header')
    <h1>Employees</h1>
@stop

@section('content')
    <a href="/users/create" class="btn btn-success">Add Employee</a><br><br>
    @if(count($employees)>0)
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Employees</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <style>
                                th{
                                    width:25%;
                                }
                            </style>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th colspan="2">Actions</th>
                        </tr>
                        @foreach($employees as $employee)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$employee->name}}</td>
                                <td>{{$employee->email}}</td>
                                <td style="text-transform:capitalize">{{$employee->role}}</td>
                                <td><a href="/users/change-password/{{$employee->id}}" class="btn btn-info btn-sm">Change Password</a></td>
                                <td>
                                    {!!Form::open(['action' => ['UsersController@destroy', $employee->id], 'method' => 'POST'])!!}
                                        {{Form::hidden('_method','DELETE')}}
                                        {{Form::submit('Remove', ['class' => 'btn btn-danger btn-sm'])}}
                                    {!!Form::close()!!}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="box-footer">
                {{$employees->links()}}
            </div>
        </div>
    @else
        <p>Employees Not Found</p>
    @endif
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop