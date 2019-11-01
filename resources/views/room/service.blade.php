@extends('adminlte::page')
@section('title', 'Room Service')

@section('content_header')
    <h1>Room Service</h1>
@stop

@section('content')
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#room_service" data-toggle="tab" aria-expanded="true">ROOM SERVICE</a></li>
            <li class=""><a href="#restuarent" data-toggle="tab" aria-expanded="false">RESTAURANT</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active text-black" id="room_service">
                @if(count($rooms)>0)
                    <div class="row">
                        @foreach($rooms as $room)
                            <div class="col-sm-2">
                                <div class="small-box bg-green">
                                    <?php $number = App\Room::where('id', $room->room_id)->first(); ?>
                                    <a href="/room-service/{{$room->room_id}}">
                                        <div class="inner" style="padding: 5px; cursor:pointer; color: #fff;">
                                            <h3>{{$number->room_number}} <span style="text-transform:capitalize; font-size:12px;">{{$room->room_status}}</span></h3>
                                            <small>{{$room->guest_name}}</small>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    {{$rooms->links()}}
                @else
                    <p>No Rooms Found</p>
                @endif
            </div>
            <!-- /.tab-pane -->
            <div class="tab-pane" id="restuarent">
                {!! Form::open(['url' => '/restaurent/store', 'method' => 'POST','enctype' => 'multipart/form-data']) !!}
                    <div class="form-inline">
                        <div class="form-group">
                            {{Form::label('title', 'Title')}}
                            {{Form::select('title', ['Mr.' => 'Mr.', 'Ms.' => 'Ms.', 'Mrs.' => 'Mrs.'], '', ['id' => 'title', 'class' => 'form-control', 'placeholder' => 'Title', 'id' => 'title'])}}
                        </div>
                        <div class="form-group">
                            {{Form::label('name', 'Full Name')}}
                            {{Form::text('name', '', ['id' => 'name', 'class' => 'form-control', 'placeholder' => 'Enter Full Name'])}}
                        </div>
                        <div class="form-group" style="margin-left:20px;">
                            {{Form::label('mobile', 'Phone Number')}}
                            {{Form::text('mobile', '', ['id' => 'mobile', 'class' => 'form-control', 'placeholder' => 'Enter Phone Number'])}}
                        </div>
                    </div><br>
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            @foreach(App\Category::all() as $cat)
                                <li @if($loop->iteration == 1) class="active" @endif><a href="#tab_{{$loop->iteration}}" data-toggle="tab" aria-expanded="true">{{$cat->category}}</a></li>
                            @endforeach
                        </ul>
                        <div class="tab-content">
                            @foreach(App\Category::all() as $cat)
                                <div class="tab-pane @if($loop->iteration == 1) active @endif text-black" id="tab_{{$loop->iteration}}">
                                    <div class="row">
                                        @foreach(App\Item::where('item_category', $cat->id)->get() as $food)
                                            <div class="col-sm-2" id="foods">
                                                <input id="{{$food->id}}" type="checkbox" name="present[]" value="{{$food->id}}" />
                                                <label for="{{$food->id}}">
                                                    <p>{{$food->id}}</p>
                                                    <h4>{{$food->item_name}}</h4>
                                                    <?php $setting = App\Setting::first(); ?>
                                                    <p>{{$setting->currency}} {{$food->item_price}}</p>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <!-- /.tab-pane -->
                            @endforeach
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Order</button>
                {!! Form::close() !!}
            </div>
            <!-- /.tab-pane -->
        </div>
        <!-- /.tab-content -->
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/custom.css">
@stop