@extends('adminlte::page')
@section('title', 'Orders')

@section('content_header')
    <h1>Orders</h1>
@stop

@section('content')
    @if(count($orders)>0)
    <div class="box">
            <div class="box-header">
                <h3 class="box-title">Orders</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
                <table class="table table-striped table-bordered">
                    <tbody>
                        <tr>
                            <th style="width: 20px">ID</th>
                            <th>Item Name</th>
                            <th>Quantity</th>
                            <th>Room Number</th>
                            <th>Guest</th>
                            <th>Agent</th>
                            <th>Amount</th>
                            <TH>Status</TH>
                            <th>Ordered At</th>
                            <TH colspan="5">Actions</TH>
                        </tr>
                        @foreach($orders as $order)
                            <tr>
                                <td>{{$order->id}}</td>
                                <?php $item = App\Item::find($order->item_id); ?>
                                <td>{{$item->item_name}}</td>
                                <td>{{$order->quantity}}</td>
                                <?php $room = App\Room::find($order->room_id); ?>
                                <td>{{$room->room_number}}</td>
                                <?php $guest = App\User::find($order->guest_id); ?>
                                <td>{{$guest->name}}</td>
                                <?php $agent = App\User::find($order->agent_id); ?>
                                <td>
                                    @if($agent)
                                        {{$agent->name}}
                                    @endif
                                </td>
                                <?php $setting = App\Setting::first(); ?>
                                <td>{{$order->quantity}} x {{$item->item_price}} = {{$setting->currency}} {{$order->amount}}</td>
                                <td style="text-transform:capitalize">{{$order->status}}</td>
                                <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d M, Y H:i A') }}</td>
                                @if($order->status == 'Order Placed')
                                    <td>
                                        <button type="button" data-toggle="modal" data-target="#order-{{$order->id}}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></button>
                                    </td>
                                @else
                                    <td></td>
                                @endif
                                <td>
                                    {!! Form::open(['action' => 'InvoiceController@order', 'method' => 'POST','enctype' => 'multipart/form-data', 'id' => 'invoice-generate']) !!}
                                        {{Form::hidden('id', $order->id)}}
                                        {{Form::submit('Invoice', ['class' => 'btn btn-sm btn-success'])}}
                                    {!! Form::close() !!}
                                </td>
                                <td>
                                    <a type="button" data-toggle="modal" data-target="#order_{{$order->id}}" class="btn btn-sm btn-primary">Generate Receipt</a>
                                </td>
                                <td>
                                    {!!Form::open(['action' => ['OrderController@destroy', $order->id], 'method' => 'POST'])!!}
                                        {{Form::hidden('_method','DELETE')}}
                                        <button class="btn btn-danger btn-sm" type="submit"><i class="fa fa-trash-alt"></i></button>
                                    {!!Form::close()!!}
                                </td>
                                @if($order->payment_status == "Unpaid" && $order->status != "rejected" && $order->status != "cancelled")
                                    <td><a href="/orders/{{$order->id}}/edit" class="btn btn-info btn-sm">Mark as Paid</a></td>
                                @endif
                            </tr>
                            @include('modals.order')
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
                {{$orders->links()}}
            </div>
        </div>
        @foreach($orders as $order)
            @include('modals.order-invoice')
        @endforeach
    @else
        <p>No orders Found</p>
    @endif
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop