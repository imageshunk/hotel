@extends('adminlte::page')
@section('title', 'Add Expense')

@section('content_header')
    <h1>Add Expense</h1>
@stop

@section('content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Add Expense</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body no-padding">
            <table class="table table-striped expense">
                {{Form::open(['action'=>'ExpenseController@store', 'method'=>'POST'])}}
                    <tr>
                        <th>Date</th>
                        <th>Receipt No.</th>
                        <th>Particulars</th>
                        <th>Expense Category</th>
                    </tr>
                    <tr>
                        <td>{{Form::date('date', '',['class'=>'form-control'])}}</td>
                        <td>{{Form::text('receipt', '', ['class'=>'form-control', 'placeholder'=>'Receipt No.'])}}</td>
                        <td>{{Form::text('particular', '', ['class'=>'form-control', 'placeholder'=>'Particulars'])}}</td>
                        <td>
                            <select name="category_id" class="form-control">
                                <option value="" selected="selected">Select a Category</option>
                                @foreach(App\ExpenseCategory::all() as $key)
                                    <option value="{{$key->id}}">{{$key->name}}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>Payment Type</th>
                        <th>Amount</th>
                        <th>Added By</th>
                    </tr>
                    <tr>
                        <td>
                            <select name="payment_type_id" class="form-control">
                                @foreach(DB::table('payment_types')->get() as $key)
                                    <option value="{{$key->id}}">{{$key->payment_type}}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>{{Form::text('amount', '', ['class'=>'form-control', 'placeholder'=>'Expense Amount'])}}</td>
                        <td>
                            <select name="added_by_id" class="form-control">
                                <option value="" selected="selected">Select a User</option>
                                @foreach(App\User::whereIn('role', ['admin', 'employee', 'agent'])->get() as $key)
                                    <option value="{{$key->id}}">{{$key->name}}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>{{Form::submit('Save', ['class'=>'btn btn-success'])}}</td>
                    </tr>
                {{Form::close()}}
            </table>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <style>
        .expense th, .expense td{
            width:25%;
        }
    </style>
@stop

@section('js')

@stop