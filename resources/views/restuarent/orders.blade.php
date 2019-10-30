@extends('adminlte::page')
@section('title', 'Restaurent Orders')

@section('content_header')
    <h1>Restaurent Orders</h1>
@stop

@section('content')
    @if(count($orders)>0)
    <div class="box">
            <div class="box-header">
                <h3 class="box-title">Restaurent Orders</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
                <table class="table table-striped table-bordered">
                    <tbody>
                        <tr>
                            <th style="width: 20px">ID</th>
                            <th>Item Name</th>
                            <th>Quantity</th>
                            <th>Guest</th>
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
                                <?php $guest = App\User::find($order->guest_id); ?>
                                <td>{{$guest->name}}</td>
                                <td>{{$order->quantity}} x {{$item->item_price}} = {{$order->amount}}</td>
                                <td style="text-transform:capitalize">{{$order->status}}</td>
                                <td>{{ \Carbon\Carbon::parse($order->created_at)->diffForHumans() }}</td>
                                @if($order->status == 'Order Placed' || $order->status == 'Pending')
                                    <td>
                                        <button type="button" data-toggle="modal" data-target="#restuarent-order-{{$order->id}}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></button>
                                    </td>
                                @else
                                    <td></td>
                                @endif
                                <td>
                                    {!! Form::open(['action' => 'InvoiceController@restuarent', 'method' => 'POST','enctype' => 'multipart/form-data', 'id' => 'invoice-generate']) !!}
                                        {{Form::hidden('id', $order->id)}}
                                        {{Form::submit('Invoice', ['class' => 'btn btn-success btn-sm'])}}
                                    {!! Form::close() !!}
                                </td>
                                <td>
                                    <a type="button" data-toggle="modal" data-target="#restuarent_{{$order->id}}" class="btn btn-sm btn-primary">Generate Receipt</a>
                                </td>
                                @if($order->status != 'delivered')
                                    <td>
                                        {!!Form::open(['action' => ['RestuarentController@destroy', $order->id], 'method' => 'POST'])!!}
                                            {{Form::hidden('_method','DELETE')}}
                                            <button class="btn btn-danger btn-sm" type="submit"><i class="fa fa-trash-alt"></i></button>
                                        {!!Form::close()!!}
                                    </td>
                                @else
                                    <td></td>
                                @endif
                                @if($order->payment_status == "Unpaid" && $order->status != "rejected" && $order->status != "cancelled")
                                    <td><a href="/restuarent/orders/{{$order->id}}/edit" class="btn btn-info btn-sm">Mark as Paid</a></td>
                                @endif
                            </tr>
                            @include('modals.restuarent-order')
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
            @include('modals.restuarent-invoice')
        @endforeach
    @else
        <p>No orders Found</p>
    @endif
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop