@extends('adminlte::page')

@section('title', 'Reports')

@section('content_header')
    <h1>Reports</h1>
@stop

@section('content')
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">Guest Reports</a></li>
            <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">Room Occupancy</a></li>
            <li class=""><a href="#tab_3" data-toggle="tab" aria-expanded="false">Sales Report</a></li>
            <li class=""><a href="#tab_5" data-toggle="tab" aria-expanded="false">Room Revenue</a></li>
            <li class=""><a href="#tab_6" data-toggle="tab" aria-expanded="false">Expense Report</a></li>
            <li class=""><a href="#tab_7" data-toggle="tab" aria-expanded="false">Inventory Report</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active text-black" id="tab_1">
                {{Form::open(['url' => '/reports/users', 'method' => 'POST', 'class' => 'form-inline'])}}
                    <div class="form-group">
                        {{Form::label('fromDate', 'From')}}<br>
                        {{Form::date('fromDate')}}
                    </div>
                    <div class="form-group">
                        {{Form::label('fromDate', 'To')}}<br>
                        {{Form::date('toDate')}}
                    </div>
                    <br><br>
                    {{Form::submit('Get Reports', ['class' => 'btn btn-sm btn-primary'])}}
                {{Form::close()}}
            </div>
            <!-- /.tab-pane -->
            <div class="tab-pane text-black" id="tab_2">
                {{Form::open(['url' => '/reports/bookings', 'method' => 'POST', 'class' => 'form-inline'])}}
                    <div class="form-group">
                        {{Form::label('fromDate', 'From')}}<br>
                        {{Form::date('fromDate')}}
                    </div>
                    <div class="form-group">
                        {{Form::label('fromDate', 'To')}}<br>
                        {{Form::date('toDate')}}
                    </div>
                    <br><br>
                    {{Form::submit('Get Reports', ['class' => 'btn btn-sm btn-primary'])}}
                {{Form::close()}}
            </div>
            <!-- /.tab-pane -->
            <div class="tab-pane text-black" id="tab_3">
                {{Form::open(['url' => '/reports/orders', 'method' => 'POST', 'class' => 'form-inline'])}}
                    <div class="form-group">
                        {{Form::label('fromDate', 'From')}}<br>
                        {{Form::date('fromDate')}}
                    </div>
                    <div class="form-group">
                        {{Form::label('fromDate', 'To')}}<br>
                        {{Form::date('toDate')}}
                    </div>
                    <br><br>
                    {{Form::submit('Get Reports', ['class' => 'btn btn-sm btn-primary'])}}
                {{Form::close()}}
            </div>
            <!-- /.tab-pane -->
            <div class="tab-pane text-black" id="tab_5">
                {{Form::open(['url' => '/reports/room-revenue', 'method' => 'POST', 'class' => 'form-inline'])}}
                    <div class="form-group">
                        {{Form::label('fromDate', 'From')}}<br>
                        {{Form::date('fromDate', '', ['class'=>'form-control'])}}
                    </div>
                    <div class="form-group">
                        {{Form::label('fromDate', 'To')}}<br>
                        {{Form::date('toDate', '', ['class'=>'form-control'])}}
                    </div>
                    <div class="form-group">
                        {{Form::label('room', 'Select Room')}}<br>
                        <select name="room_id" class="form-control">
                            <option value="" selected="selected">All</option>
                            @foreach(App\Room::all() as $room)
                                <option value="{{$room->id}}">{{$room->room_number}}</option>
                            @endforeach
                        </select>
                    </div>
                    <br><br>
                    {{Form::submit('Get Reports', ['class' => 'btn btn-sm btn-primary'])}}
                {{Form::close()}}
            </div>
            <!-- /.tab-pane -->
            <div class="tab-pane text-black" id="tab_6">
                {{Form::open(['url' => '/reports/expense', 'method' => 'POST', 'class' => 'form-inline'])}}
                    <div class="form-group">
                        {{Form::label('fromDate', 'From')}}<br>
                        {{Form::date('fromDate', '', ['class'=>'form-control'])}}
                    </div>
                    <div class="form-group">
                        {{Form::label('fromDate', 'To')}}<br>
                        {{Form::date('toDate', '', ['class'=>'form-control'])}}
                    </div>
                    <div class="form-group">
                        {{Form::label('category', 'Select Category')}}<br>
                        <select name="expense_category_id" class="form-control">
                            <option value="" selected="selected">All</option>
                            @foreach(App\ExpenseCategory::all() as $key)
                                <option value="{{$key->id}}">{{$key->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <br><br>
                    {{Form::submit('Get Reports', ['class' => 'btn btn-sm btn-primary'])}}
                {{Form::close()}}
            </div>
            <!-- /.tab-pane -->
            <div class="tab-pane text-black" id="tab_7">
                {{Form::open(['url' => '/reports/inventory', 'method' => 'POST', 'class' => 'form-inline'])}}
                    <div class="form-group">
                        {{Form::label('item', 'Select Item')}}<br>
                        <select name="item_id" class="form-control">
                            <option value="" selected="selected">Select a Item</option>
                            <option value="">All</option>
                            @foreach(App\Item::get() as $key)
                                <option value="{{$key->id}}">{{$key->item_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <br><br>
                    {{Form::submit('Get Reports', ['class' => 'btn btn-sm btn-primary'])}}
                {{Form::close()}}
            </div>
            <!-- /.tab-pane -->
        </div>
        <!-- /.tab-content -->
    </div>
    
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop