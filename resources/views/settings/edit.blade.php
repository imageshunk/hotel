@extends('adminlte::page')

@section('title', 'Settings')

@section('content_header')
    <h1>Settings</h1>
@stop

@section('content')
    <div class="row">
        {{Form::open(['action' => ['SettingsController@update', $setting->id], 'method' => 'POST', 'enctype' => 'multipart/form-data'])}}
            <div class="col-sm-6 form-group">
                {{Form::label('hotel_name', 'Hotel Name')}}
                {{Form::text('hotel_name', $setting->hotel_name, ['class' => 'form-control', 'placeholder' => 'Hotel Name'])}}
            </div>
            <div class="col-sm-6 form-group">
                {{Form::label('logo', 'Logo')}}
                {{Form::file('logo', ['class' => 'form-control'])}}
            </div>
            <div class="col-sm-6 form-group">
                {{Form::label('description', 'Description')}}
                {{Form::textarea('description', $setting->description, ['class' => 'form-control', 'placeholder' => 'Description', 'rows' => '5'])}}
            </div>
            <div class="col-sm-6 form-group">
                {{Form::label('contact', 'Contact No.')}}
                {{Form::text('contact', $setting->contact, ['class' => 'form-control', 'placeholder' => 'Contact No.'])}}
            </div>
            <div class="col-sm-6 form-group">
                {{Form::label('address', 'Full Address')}}
                {{Form::text('address', $setting->address, ['class' => 'form-control', 'placeholder' => 'Full Address'])}}
            </div>
            <div class="col-sm-6 form-group">
                {{Form::label('website', 'Website Address')}}
                {{Form::text('website', $setting->website, ['class' => 'form-control', 'placeholder' => 'Website Address'])}}
            </div>
            <div class="col-sm-6 form-group">
                {{Form::label('currency', 'Currency')}}
                {{Form::text('currency', $setting->currency, ['class' => 'form-control', 'placeholder' => 'Currency'])}}
            </div>
            <div class="col-sm-6 form-group">
                {{Form::label('bank_name', 'Bank Name')}}
                {{Form::text('bank_name', $setting->bank_name, ['class' => 'form-control', 'placeholder' => 'Bank Name'])}}
            </div>
            <div class="col-sm-6 form-group">
                {{Form::label('account_name', 'Account Name')}}
                {{Form::text('account_name', $setting->account_name, ['class' => 'form-control', 'placeholder' => 'Account Name'])}}
            </div>
            <div class="col-sm-6 form-group">
                {{Form::label('account_no', 'Account Number')}}
                {{Form::text('account_no', $setting->account_no, ['class' => 'form-control', 'placeholder' => 'Account Number'])}}
            </div>
            <div class="col-sm-6 form-group">
                {{Form::label('branch_name', 'Branch Name')}}
                {{Form::text('branch_name', $setting->branch_name, ['class' => 'form-control', 'placeholder' => 'Branch Name'])}}
            </div>
            <div class="col-sm-3 form-group">
                {{Form::label('code', 'SWIFT CODE')}}
                {{Form::text('code', $setting->code, ['class' => 'form-control', 'placeholder' => 'SWIFT CODE'])}}
            </div>
            {{Form::hidden('_method', 'PUT')}}
            <div class="col-sm-3 form-group">
                {{Form::label('save', 'Save')}} <br>
                {{Form::submit('Save Changes', ['class' => 'btn btn-primary'])}}
            </div>
        {{Form::close()}}
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop