@extends('adminlte::page')
@section('title', 'Guest Cart')

@section('content_header')
    <h1>Guest Cart</h1>
@stop

@section('content')
    @if(count($carts)>0)
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Cart Items</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <th style="width: 20px">ID</th>
                            <th>Item Name</th>
                            <th>Quantity</th>
                            <th>Room Number</th>
                            <th>Guest</th>
                            <th>Amount</th>
                            <th>Actions</th>
                        </tr>
                        @foreach($carts as $cart)
                            <tr>
                                <td>{{$cart->id}}</td>
                                <?php $item = App\Item::find($cart->item_id); ?>
                                <td>{{$item->item_name}}</td>
                                <td>
                                    {{Form::open(['action' =>['CartController@update', $cart->id], 'method' => 'POST', 'enctype' => 'multipart/form-data', 'class' => 'form-inline'])}}
                                        <p>{{Form::number('quantity', $cart->quantity, ['class' => 'form-control', 'style' => 'width: 60px'])}}
                                        {{Form::submit('Update', ['class' => 'btn btn-success'])}}</p>
                                        {{Form::hidden('_method', 'PUT')}}
                                    {{Form::close()}}
                                </td>
                                <?php $room = App\Room::find($cart->room_id); ?>
                                <td>{{$room->room_number}}</td>
                                <?php $guest = App\User::find($cart->guest_id); ?>
                                <td>{{$guest->name}}</td>
                                <?php $setting = App\Setting::first(); ?>
                                <td>{{$cart->quantity}} x {{$item->item_price}} = {{$setting->currency}} {{$cart->amount}}</td>
                                <td>
                                    {!!Form::open(['action' => ['CartController@destroy', $cart->id], 'method' => 'POST'])!!}
                                        {{Form::hidden('_method','DELETE')}}
                                        {{Form::submit('Remove', ['class' => 'btn btn-danger btn-sm'])}}
                                    {!!Form::close()!!}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        {{Form::open(['action' => 'OrderController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'class'=>'form-inline'])}}
            <select name="agent_id" id="agent_id" class="form-control">
                <?php $agents = App\User::where('role', 'agent')->get(); ?>
                <option value="">Select a Agent</option>
                @foreach($agents as $agent)
                    <option value="{{$agent->id}}">{{$agent->name}}</option>
                @endforeach
            </select>
            {{Form::hidden('guest_id', $guest->id)}}
            {{Form::submit('CONFIRM ORDER', ['class' => 'btn btn-danger'])}}
        {{Form::close()}}
    @else
        <p>Cart is Empty</p>
    @endif
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop