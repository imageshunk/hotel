@extends('adminlte::page')
@section('title', 'Room Availability')

@section('content_header')
    <h1>Room Availability</h1>
@stop

@section('content')
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-room">Add Room</button> 
    <br><br>
    <div class="modal modal-info fade" id="add-room" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Add Room</h4>
                </div>
                {!! Form::open(['action' => 'RoomController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
                    <div class="modal-body">
                        <div class="form-group">
                            {{Form::label('room_number', 'Room Number')}}
                            {{Form::text('room_number', '', ['class' => 'form-control', 'placeholder' => 'Enter Room Number'])}}
                        </div>
                        <div class="form-group">
                            {{Form::label('status', 'Status')}}
                            {{Form::select('status', ['ready' => 'Ready', 'cleanup' => 'Cleanup', 'dirty' => 'Dirty'], 'ready', ['class' => 'form-control'])}}
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
                        {{Form::submit('Save', ['class' => 'btn btn-outline'])}}
                    </div>
                {!! Form::close() !!}
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    @if(count($rooms)>0)
        <div class="row">
            @foreach($rooms as $room)
                @if($room->room_status == 'cleanup')
                    <?php $class = 'bg-yellow'; ?>
                @elseif($room->room_status == 'dirty')
                    <?php $class = 'bg-red'; ?>
                @elseif($room->room_status == 'booked')
                    <?php $class = 'bg-green'; ?>
                @else
                    <?php $class = 'bg-primary'; ?>
                @endif
                <div class="col-sm-2">
                    <div class="small-box {{$class}}">
                        @if($room->room_status == 'ready')
                            <a href="/check-ins/{{$room->id}}">
                                <div class="inner" style="padding: 5px; color: #fff;">
                                    <h3>{{$room->room_number}} <span style="text-transform:capitalize; font-size:12px;">
                                        {{$room->room_status}} 
                                        {{Form::open(['action' => ['RoomController@destroy', $room->id], 'method' => 'POST', 'class' => ''])}}
                                            {{Form::hidden('_method', 'DELETE')}}
                                            {{Form::submit('Delete', ['class' => 'btn btn-primary btn-sm'])}}
                                        {{Form::close()}}
                                    </span></h3>
                                </div>
                            </a>
                        @endif
                        @if($room->room_status == 'booked')
                            <?php $checkins = App\CheckIn::where([
                                ['room_id', $room->id],
                                ['status', 'Room Service']
                            ])->get(); ?>
                            <div class="inner" type="button" data-toggle="modal" data-target="#booked-room-{{$room->id}}" style="padding: 5px; cursor:pointer; color: #fff;">
                                <h3 style="margin-bottom:20px;">{{$room->room_number}} <span style="text-transform:capitalize; font-size:12px;">{{$room->room_status}}</span></h3>
                            </div>
                            <a style="margin-top:-40px; margin-left:6px;" href="/check-ins/{{$room->id}}" class="btn btn-success btn-sm">Check In for Other Dates</a>
                            @include('modals.booked-room')
                        @endif
                        @if($room->room_status == 'dirty' || $room->room_status == 'cleanup')
                            <div class="inner" type="button" data-toggle="modal" data-target="#{{$room->room_number}}" style="padding: 5px; cursor:pointer; color: #fff;">
                                <h3 style="margin-bottom:39px;">{{$room->room_number}} <span style="text-transform:capitalize; font-size:12px;">{{$room->room_status}}</span></h3>
                            </div>
                            @include('modals.change-room-status')
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        {{$rooms->links()}}
    @else
        <p>No Rooms Found</p>
    @endif
@stop

@section('css')
    <link rel="stylesheet" href="/css_custom.css">
@stop