@extends('adminlte::page')
@section('title', 'Room Service')

@section('content_header')
    <h1>{{$room->room_number}} Room Service</h1>
@stop

@section('content')
    {!! Form::open(['action' => 'CartController@store', 'method' => 'POST','enctype' => 'multipart/form-data']) !!}
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
            <!-- /.tab-content -->
        </div>
        {{Form::hidden('room_id', $room->id)}}
        {{Form::hidden('guest_id', $checkin->guest_id)}}
        <button type="submit" class="btn btn-primary">Order</button>
    {!! Form::close() !!}
@stop

@section('css')
    <link rel="stylesheet" href="/css_custom.css">
@stop