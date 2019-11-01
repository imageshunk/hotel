@extends('adminlte::page')

@section('title', 'Hotel Management')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-sm-3">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-money-check-alt "></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Today Sales</span>
                    <?php 
                        $total_sales = 0;
                        $sales = App\Order::where('payment_status', 'Paid')->whereDate('created_at', '=', date('Y-m-d'))->get();
                        foreach ($sales as $sale) {
                            $total_sales += $sale->amount;
                        }
                        
                        $sales = App\Restuarent::where('payment_status', 'Paid')->whereDate('created_at', '=', date('Y-m-d'))->get();
                        foreach ($sales as $sale) {
                            $total_sales += $sale->amount;
                        }
                        
                        $rooms = App\CheckIn::where('status', 'Checked Out')->whereDate('created_at', '=', date('Y-m-d'))->get();
                        foreach ($rooms as $sale) {
                            $total_sales += $sale->total;
                        }
                    ?>
                    <?php $setting = App\Setting::first(); ?>
                    <h2 class="info-box-number">{{$setting->currency}} {{$total_sales}}</h2>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>

        <div class="col-sm-3">
            <div class="info-box">
                <span class="info-box-icon bg-red"><i class="fa fa-users"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Guests Today</span>
                    <?php 
                        $guests = App\CheckIn::whereNotIn('status', ['Checked Out'])->whereDate('created_at', '=', date('Y-m-d'))->count();
                    ?>
                    <h2 class="info-box-number">{{$guests}}</h2>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>

        <div class="col-sm-3">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-home"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Rooms Booked</span>
                    <?php 
                        $rooms = App\CheckIn::whereNotIn('status', ['Checked Out'])->count();
                    ?>
                    <h2 class="info-box-number">{{$rooms}}</h2>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>

        <div class="col-sm-3">
            <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="fa fa-hotel"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Total Rooms</span>
                    <?php 
                        $rooms = App\Room::count();
                    ?>
                    <h2 class="info-box-number">{{$rooms}}</h2>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <div class="box box-danger">
                <div class="box-header with-border">
                    <h3 class="box-title">New Guests</h3>
                    <?php $guests = App\User::where('role', 'guest')->whereDate('created_at', '=', date('Y-m-d'))->orderBy('created_at', 'desc')->limit(12)->get(); ?>
                    <div class="box-tools pull-right">
                        <span class="label label-danger">{{count($guests)}} New Members</span>
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding">
                    <ul class="users-list clearfix">
                        @foreach($guests as $guest)
                            <li>
                                <i class="fa fa-user"></i>
                                <a class="users-list-name" href="#!">{{$guest->name}}</a>
                                <span class="users-list-date">Today</span>
                            </li>
                        @endforeach
                    </ul>
                  <!-- /.users-list -->
                </div>
                <!-- /.box-body -->
                <div class="box-footer text-center">
                  <a href="/guests" class="uppercase">View All Users</a>
                </div>
                <!-- /.box-footer -->
            </div>
        </div>
        <div class="col-sm-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Recently Added Items</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <ul class="products-list product-list-in-box">
                        <?php $items = App\Item::latest()->limit(5)->get(); ?>
                        @foreach($items as $item)
                            <li class="item">
                                <div class="product-img">
                                    <i class="fa fa-utensils"></i>
                                </div>
                                <div class="product-info">
                                    <?php $setting = App\Setting::first(); ?>
                                    <a href="javascript:void(0)" class="product-title">{{$item->item_name}}
                                    <span class="label label-warning pull-right">{{$setting->currency}} {{$item->item_price}}</span></a>
                                    <span class="product-description">
                                        <?php $category = App\Category::find($item->item_category); ?>
                                        {{$category->category}}
                                        @if($item->qty != NULL)
                                            , Quantity: {{$item->qty}}
                                        @endif
                                    </span>
                                </div>
                            </li>
                        @endforeach
                        <!-- /.item -->
                    </ul>
                </div>
                <!-- /.box-body -->
                <div class="box-footer text-center">
                    <a href="/items" class="uppercase">View All Products</a>
                </div>
                <!-- /.box-footer -->
            </div>
        </div>
        <div class="col-sm-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Latest Orders</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                <div class="table-responsive">
                    <table class="table no-margin">
                        <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Item</th>
                            <th>Status</th>
                            <th>Amount</th>
                        </tr>
                        </thead>
                        <?php $orders = App\Order::whereDate('created_at', '=', date('Y-m-d'))->orderBy('created_at', 'desc')->limit(7)->get(); ?>
                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td><a href="/order/invoice/{{$order->id}}">{{$order->id}}</a></td>
                                    <?php $item = App\Item::find($order->item_id); ?>
                                    <td>{{$item->item_name}}</td>
                                    <td><span class="label label-success" style="text-transform:capitalize">{{$order->status}}</span></td>
                                    <?php $setting = App\Setting::first(); ?>
                                    <td>{{$setting->currency}} {{$order->amount}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.table-responsive -->
                </div>
                <!-- /.box-body -->
                <div class="box-footer clearfix">
                <a href="/room-service" class="btn btn-sm btn-info btn-flat pull-left">Room Service</a>
                <a href="/orders" class="btn btn-sm btn-default btn-flat pull-right">View All Orders</a>
                </div>
                <!-- /.box-footer -->
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/custom.css">
@stop