@extends('adminlte::page')

@section('title', 'Settings')

@section('content_header')
    <h1>Settings</h1>
@stop

@section('content')
    @if($settings)
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Settings</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <strong><i class="fa fa-hotel margin-r-5"></i> {{$settings->hotel_name}}</strong>

                <p class="text-muted">
                    {{$settings->description}}
                </p>

                <hr>

                <strong><i class="fa fa-map margin-r-5"></i> Location</strong>

                <p class="text-muted">{{$settings->address}}, Contact: {{$settings->contact}}</p>

                <hr>

                <strong><i class="fa fa-edit margin-r-5"></i> Logo</strong>

                <p>
                    <img src="/storage/images/{{$settings->logo}}" style="max-width: 60px;">
                </p>

                <hr>

                <strong><i class="fa fa-dollar margin-r-5"></i> Currency</strong>

                <p>{{$settings->currency}}</p>
                
                <hr>

                <strong><i class="fa fa-bank margin-r-5"></i> Banking</strong>

                <p class="text-muted">Bank Name: {{$settings->bank_name}} <br> Account Name: {{$settings->account_name}} <br> 
                    Account No.: {{$settings->account_no}} <br> Branch Name: {{$settings->branch_name}} <br> SWIFT CODE: {{$settings->code}}
                </p>
            </div>
            <!-- /.box-body -->
        </div>
        <a href="/settings/{{$settings->id}}/edit" class="btn btn-success">Edit Settings</a>
    @else
        {{Form::open(['url' => '/settings', 'method' => 'POST', 'enctype' => 'multipart/form-data'])}}
            <div class="col-sm-6 form-group">
                {{Form::label('hotel_name', 'Hotel Name')}}
                {{Form::text('hotel_name', '', ['class' => 'form-control', 'placeholder' => 'Hotel Name'])}}
            </div>
            <div class="col-sm-6 form-group">
                {{Form::label('logo', 'Logo')}}
                {{Form::file('logo', ['class' => 'form-control'])}}
            </div>
            <div class="col-sm-6 form-group">
                {{Form::label('description', 'Description')}}
                {{Form::textarea('description', '', ['class' => 'form-control', 'placeholder' => 'Description', 'rows' => '5'])}}
            </div>
            <div class="col-sm-6 form-group">
                {{Form::label('contact', 'Contact No.')}}
                {{Form::text('contact', '', ['class' => 'form-control', 'placeholder' => 'Contact No.'])}}
            </div>
            <div class="col-sm-6 form-group">
                {{Form::label('address', 'Full Address')}}
                {{Form::text('address', '', ['class' => 'form-control', 'placeholder' => 'Full Address'])}}
            </div>
            <div class="col-sm-6 form-group">
                {{Form::label('website', 'Website Address')}}
                {{Form::text('website', '', ['class' => 'form-control', 'placeholder' => 'Website Address'])}}
            </div>
            <div class="col-sm-6 form-group">
                {{Form::label('currency', 'Currency')}}
                {{Form::text('currency', '', ['class' => 'form-control', 'placeholder' => 'Currency'])}}
            </div>
            <div class="col-sm-6 form-group">
                {{Form::label('bank_name', 'Bank Name')}}
                {{Form::text('bank_name', '', ['class' => 'form-control', 'placeholder' => 'Bank Name'])}}
            </div>
            <div class="col-sm-6 form-group">
                {{Form::label('account_name', 'Account Name')}}
                {{Form::text('account_name', '', ['class' => 'form-control', 'placeholder' => 'Account Name'])}}
            </div>
            <div class="col-sm-6 form-group">
                {{Form::label('account_no', 'Account Number')}}
                {{Form::text('account_no', '', ['class' => 'form-control', 'placeholder' => 'Account Number'])}}
            </div>
            <div class="col-sm-6 form-group">
                {{Form::label('branch_name', 'Branch Name')}}
                {{Form::text('branch_name', '', ['class' => 'form-control', 'placeholder' => 'Branch Name'])}}
            </div>
            <div class="col-sm-3 form-group">
                {{Form::label('code', 'SWIFT CODE')}}
                {{Form::text('code', '', ['class' => 'form-control', 'placeholder' => 'SWIFT CODE'])}}
            </div>
            <div class="col-sm-3 form-group">
                {{Form::label('save', 'Save')}} <br>
                {{Form::submit('Save Changes', ['class' => 'btn btn-primary btn-sm'])}}
            </div>
        {{Form::close()}}
    @endif
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop