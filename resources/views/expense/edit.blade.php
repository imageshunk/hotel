@extends('adminlte::page')
@section('title', 'Edit Expense')

@section('content_header')
    <h1>Edit Expense</h1>
@stop

@section('content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Edit Expense</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body no-padding">
            <table class="table table-striped expense">
                {{Form::open(['action'=>['ExpenseController@update', $expense->id], 'method'=>'POST'])}}
                    <tr>
                        <th>Date</th>
                        <th>Receipt No.</th>
                        <th>Particulars</th>
                        <th>Expense Category</th>
                    </tr>
                    <tr>
                        <td>{{Form::date('date', $expense->date,['class'=>'form-control'])}}</td>
                        <td>{{Form::text('receipt', $expense->receipt, ['class'=>'form-control', 'placeholder'=>'Receipt No.'])}}</td>
                        <td>{{Form::text('particular', $expense->particular, ['class'=>'form-control', 'placeholder'=>'Particulars'])}}</td>
                        <td>
                            <select name="category_id" class="form-control">
                                @foreach(App\ExpenseCategory::all() as $key)
                                    <?php
                                        if($key->id == $expense->category_id){
                                            $selected = 'selected="selected"';
                                        }else{
                                            $selected = '';
                                        }
                                    ?>
                                    <option value="{{$key->id}}" {{$selected}}>{{$key->name}}</option>
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
                                    <?php
                                        if($key->id == $expense->payment_type_id){
                                            $selected = 'selected="selected"';
                                        }else{
                                            $selected = '';
                                        }
                                    ?>
                                    <option value="{{$key->id}}" {{$selected}}>{{$key->payment_type}}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>{{Form::text('amount', $expense->amount, ['class'=>'form-control', 'placeholder'=>'Expense Amount'])}}</td>
                        <td>
                            <select name="added_by_id" class="form-control">
                                @foreach(App\User::whereIn('role', ['admin', 'employee', 'agent'])->get() as $key)
                                    <?php
                                        if($key->id == $expense->added_by_id){
                                            $selected = 'selected="selected"';
                                        }else{
                                            $selected = '';
                                        }
                                    ?>
                                    <option value="{{$key->id}}" {{$selected}}>{{$key->name}}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            {{Form::hidden('_method', 'PUT')}}
                            {{Form::submit('Save', ['class'=>'btn btn-success'])}}
                        </td>
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