@extends('adminlte::page')
@section('title', 'Room Bookings')

@section('content_header')
    <h1>Room Bookings</h1>
@stop

@section('content')
    @if(count($bookings)>0)
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Check Ins</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <th>Guest Name</th>
                            <th>Guest Id</th>
                            <th>Guest Phone Number</th>
                            <th>Organisation</th>
                            <th>Room Number</th>
                            <th>Room Type</th>
                            <th>Check In Date</th>
                            <th>Check Out Date</th>
                            <th>Utilities</th>
                            <th>Status</th>
                            <th>Booked at</th>
                            <th colspan="2">Actions</th>
                        </tr>
                        @foreach($bookings as $booking)
                            <tr>
                                <td>{{$booking->guest_name}}</td>
                                <td>{{$booking->guest_id}}</td>
                                <td>{{$booking->mobile}}</td>
                                <td>{{$booking->organisation}}</td>
                                <?php $room = App\Room::where('id', $booking->room_id)->first(); ?>
                                <td>{{$room->room_number}}</td>
                                <td>{{App\Package::find($booking->package)->package_name}}</td>
                                <td>{{$booking->check_in_date}}</td>
                                <td>{{$booking->check_out_date}}</td>
                                <td>
                                    <?php $utilities = explode(",",$booking->utilities); ?>
                                    @foreach($utilities as $utility)
                                        <?php $option = App\Utility::where('id', $utility)->first(); ?>
                                        <i>{{$option['utility']}}</i><br>
                                    @endforeach
                                </td>
                                <td>
                                    @if($booking->status == 'Room Service')
                                        Checked In
                                    @else
                                        {{$booking->status}}
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($booking->created_at)->diffForHumans() }}</td>
                                <td>
                                    {!! Form::open(['action' => 'InvoiceController@booking', 'method' => 'POST','enctype' => 'multipart/form-data', 'id' => 'invoice-generate']) !!}
                                        {{Form::hidden('id', $booking->id)}}
                                        {{Form::submit('Invoice', ['class' => 'btn btn-sm btn-success'])}}
                                    {!! Form::close() !!}
                                </td>
                                <td><a type="button" data-toggle="modal" data-target="#generate_{{$booking->id}}" class="btn btn-sm btn-primary">Generate Receipt</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
                {{$bookings->links()}}
            </div>
        </div>
        @foreach($bookings as $booking)
            @include('modals.generate-invoice')
        @endforeach
    @else
        <p>No Room Bookings</p>
    @endif
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop