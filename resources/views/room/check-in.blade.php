@extends('adminlte::page')
@section('title', 'Room Check In')

@section('content_header')
    <h1>{{$room->room_number}} Check In</h1>
@stop

@section('content')
    {!! Form::open(['action' => 'CheckInController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'style' => 'width: 98%; margin: 0 auto;', 'autocomplete' => 'off']) !!}
        <div class="row" style="background-color: #ccc; border: 1px solid #ccc; border-radius: 7px; padding: 10px; margin-bottom:10px;">
            <div class="col-sm-3">
                <div class="form-group">
                    {{Form::label('check_in_date', 'Check In Date')}}
                    {{Form::date('check_in_date', '', ['id' => 'datepicker'])}}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    {{Form::label('check_in_time', 'Check In Time')}}
                    {{Form::time('check_in_time', '', ['id' => 'time'])}}
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    {{Form::label('check_out_date', 'Check Out Date')}}
                    {{Form::date('check_out_date', '', ['id' => 'date', 'placeholder' => 'Pick Checkout Date'])}}
                </div>
                <div id="date-status"></div>
            </div>
            <div class="col-sm-2">
                <button type="button" class="btn btn-success" id="check-availability">Check Availability</button>
            </div>
        </div>

        <div class="row" style="background-color: #ccc; border: 1px solid #ccc; border-radius: 7px; padding: 10px; margin-bottom:10px;">
            <div class="col-sm-3">
                <div class="form-group">
                    {{Form::label('guest_name', 'Guest Name')}}
                    {{Form::text('guest_name', '', ['id' => 'guest_name', 'class' => 'form-control', 'placeholder' => 'Guest Name'])}}
                </div>
                
                <select id="results" onclick="myFunction(event)" class="form-control"><option selected="selected">Select a Guest</option></select>

                <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#new-guest">New Guest</button>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    {{Form::label('guest_id', 'Guest Id')}}
                    {{Form::text('guest_id', '', ['id' => 'guest_id', 'class' => 'form-control', 'placeholder' => 'Guest Id'])}}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    {{Form::label('guest_mobile', 'Guest Phone Number')}}
                    {{Form::text('guest_mobile', '', ['id' => 'guest_mobile', 'class' => 'form-control', 'placeholder' => 'Guest Phone Number'])}}
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    {{Form::label('guest_organisation', 'Guest Organisation')}}
                    {{Form::text('guest_organisation', '', ['id' => 'guest_organisation', 'class' => 'form-control', 'placeholder' => 'Guest Organisation'])}}
                </div>
            </div>
        </div>

        <div class="row" style="background-color: #ccc; border: 1px solid #ccc; border-radius: 7px; padding: 10px; margin-bottom:10px;">
            <div class="col-sm-3">
                <div class="form-group">
                    {{Form::label('agent_id', 'Agent')}}
                    <select name="agent_id" class="form-control">
                        <option value="" selected="selected">Select Agent</option>
                        @foreach(App\User::where('role', 'agent')->get() as $key)
                            <option value="{{$key->id}}">{{$key->title}} {{$key->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    {{Form::label('adults', 'Adults')}}
                    {{Form::number('adults', '1', ['class' => 'form-control', 'placeholder' => 'Adults'])}}
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    {{Form::label('childrens', 'Childrens')}}
                    {{Form::number('childrens', '0', ['class' => 'form-control', 'placeholder' => 'Childrens'])}}
                </div>
            </div>
            <div class="col-sm-5">
                <div class="form-group">
                    {{Form::label('comments', 'Comments')}}
                    {{Form::textarea('comments', '', ['class' => 'form-control', 'placeholder' => 'Comments', 'rows' => '3'])}}
                </div>
            </div>
        </div>

        <div class="row" style="background-color: #ccc; border: 1px solid #ccc; border-radius: 7px; padding: 10px; margin-bottom:10px;">
            <div class="col-sm-3">
                <div class="form-group">
                    {{Form::label('package', 'Room Type')}}
                    <?php $packages = App\Package::all(); ?>
                    <select name="package" id="package" class="form-control">
                        @foreach($packages as $package)
                            <option value="{{$package->id}}">{{$package->package_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-sm-2">
                {{Form::label('rate', 'Price')}}
                {{Form::text('rate', '', ['class'=>'form-control', 'placeholder'=>'Price'])}}
            </div>
            
            <div class="col-sm-4">
                {{Form::label('utilities', 'Utilities')}} <br>
                <?php $utilities = App\Utility::all(); ?>
                @foreach($utilities as $utility)
                    <label class="checkbox-inline"><input type="checkbox" name="utility[]" value="{{$utility->id}}">{{$utility->utility}}</label>
                @endforeach
            </div>
        </div>
        {{Form::hidden('room_id', $room->id, ['id' => 'room_id'])}}
        {{Form::submit('Save', ['class' => 'btn btn-primary'])}}
    {!! Form::close() !!}

    @include('modals.new-guest')
@stop

@section('css')
    <link rel="stylesheet" href="/css_custom.css">
@stop

<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script>
    $(document).ready(function ($){
        $('#guest_id').prop("readonly", true);

        var date = new Date();
        var day = date.getDate(),
            month = date.getMonth() + 1,
            year = date.getFullYear(),
            hour = date.getHours(),
            min  = date.getMinutes();

        month = (month < 10 ? "0" : "") + month;
        day = (day < 10 ? "0" : "") + day;
        hour = (hour < 10 ? "0" : "") + hour;
        min = (min < 10 ? "0" : "") + min;

        var today = year + "-" + month + "-" + day,
            displayTime = hour + ":" + min; 

        document.getElementById('datepicker').value = today;      
        document.getElementById("time").value = displayTime;
    });
</script>

<script>
    jQuery( document ).ready( function( $ ) {
        $('#date-status').hide();
        $( '#check-availability' ).click(function(e) {
            $('#appended_status').remove();
            e.preventDefault();
            var datepicker = $('#datepicker').val();
            var date = $('#date').val();
            var room = $('#room_id').val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: '/check-ins/check-availability',
                data: { date:date, datepicker:datepicker, room:room }, 
                success: function( data ) {
                    $('#date-status').show();
                    $('#date-status').append(`
                        <b class="${data.class}" id="appended_status">${data.string}</b>
                    `);
                }
            });
        });
        });
</script>

@section('js')
    <script type="text/javascript">
    $('#results').hide();
    $('#guest_name').on('keyup',function(){
        $value=$(this).val();
        $.ajax({
            type : 'get',
            url : '/guest-search',
            data:{'search':$value},
            success:function(data){
                $('#results').show();
                $('#results').html(data);
            }
        });
    });
    </script>

    <script type="text/javascript">
        $.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });
    </script>

    <script>
        function myFunction(e) {
            $guest = e.target.value;
            $.ajax({
                type : 'get',
                url : '/guest-details',
                data:{'guest':$guest},
                success:function(data){
                    $('#guest_name').val(data.name);
                    $('#guest_mobile').val(data.mobile);
                    $('#guest_organisation').val(data.organisation);
                    $('#guest_id').val(data.guestid);
                }
            });
        }
    </script>
@stop