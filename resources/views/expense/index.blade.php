@extends('adminlte::page')
@section('title', 'Expenses')

@section('content_header')
    <h1>Expenses</h1>
@stop

@section('content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Expenses</h3>
            <div class="box-tools">
                {{Form::open(['action'=>'ExpenseController@search', 'method'=>'POST'])}}
                    <div class="form-group form-inline">
                        <a href="/expenses/create" class="btn btn-primary">Add Expense</a>
                        {{Form::text('search', '', ['class'=>'form-control', 'placeholder'=>'Search Expenses ...'])}}
                        {{Form::submit('Go', ['class'=>'btn btn-primary'])}}
                    </div>
                {{Form::close()}}
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body no-padding">
            <table class="table table-striped expense">
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Receipt No.</th>
                    <th>Particulars</th>
                    <th>Expense Category</th>
                    <th>Payment Type</th>
                    <th>Amount</th>
                    <th>Added By</th>
                    <th colspan="2">Actions</th>
                </tr>
                @foreach($expenses as $expense)
                    <tr>
                        <td>{{$expense->id}}</td>
                        <td>{{\Carbon\Carbon::parse($expense->date)->format('d M, Y')}}</td>
                        <td>{{$expense->receipt}}</td>
                        <td>{{$expense->particular}}</td>
                        <td>{{App\ExpenseCategory::where('id', $expense->category_id)->first()->name}}</td>
                        <td>{{DB::table('payment_types')->where('id', $expense->payment_type_id)->first()->payment_type}}</td>
                        <td>{{App\Setting::first()->currency}} {{$expense->amount}}</td>
                        <td>{{App\User::find($expense->added_by_id)->name}}</td>
                        <td><a href="/expenses/{{$expense->id}}/edit" class="btn btn-primary btn-sm">Edit</a></td>
                        <td>
                            {{Form::open(['action'=>['ExpenseController@destroy', $expense->id], 'method'=>'POST'])}}
                                {{Form::hidden('_method', 'DELETE')}}
                                {{Form::submit('Delete', ['class'=>'btn btn-sm btn-danger'])}}
                            {{Form::close()}}
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
        <div class="box-footer">
            {{$expenses->links()}}
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')

@stop