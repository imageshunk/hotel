@extends('adminlte::page')
@section('title', 'Create New Employee')

@section('content_header')
    <h1>Create New Employee</h1>
@stop

@section('content')
    {!! Form::open(['action' => 'UsersController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
        <div class="form-group">
            {{Form::label('employee_name', 'Employee Name')}}
            {{Form::text('employee_name', '', ['class' => 'form-control', 'placeholder' => 'Employee Name', 'id' => 'employee_name'])}}
        </div>
        <div class="form-group">
            {{Form::label('employee_email', 'Employee Email')}}
            {{Form::text('email', '', ['class' => 'form-control', 'placeholder' => 'Employee Email', 'id' => 'employee_email'])}}
        </div>
        <div class="form-group">
            {{Form::label('Role', 'Role')}}
            {{Form::select('role', ['admin' => 'Admin', 'employee' => 'Employee', 'agent' => 'Agent'],null, ['class' => 'form-control', 'placeholder' => 'Select Role', 'id' => 'role'])}}
        </div>
        <div class="form-group">
            {{Form::label('password', 'Password')}}
            {{Form::password('password', ['class' => 'form-control', 'placeholder' => 'Password', 'id' => 'password'])}}
        </div>
        <div class="form-group">
            {{Form::label('password', 'Confirm Password')}}
            {{Form::password('password2', ['class' => 'form-control', 'placeholder' => 'Confirm Password', 'id' => 'password2'])}}
        </div>
        {{Form::submit('Save', ['class' => 'btn btn-primary', 'id' => 'submit-user'])}}
    {!! Form::close() !!}
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop