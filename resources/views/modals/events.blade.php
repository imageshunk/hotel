<div class="modal fade" id="event_{{$i}}" style="display: none; color:#000;">
    <div class="modal-dialog" style="width:42%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Occupancy Planner</h4>
            </div>
            {{Form::open(['action'=>'EventController@store', 'method'=>'POST'])}}
                <div class="modal-body">
                    <div class="form-group form-inline">
                        <div class="row">
                            <div class="col-sm-6">
                                {{Form::label('startDate', 'Start Date')}}
                                {{Form::date('start_date', '', ['id' => 'date', 'class' => 'form-control'])}}
                            </div>
                            <div class="col-sm-6 text-right">
                                {{Form::label('ebdDate', 'Start Date')}}
                                {{Form::date('end_date', '', ['id' => 'date', 'class' => 'form-control', 'style'=>'text-align:right'])}}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        {{Form::label('name', 'Guest Name')}}
                        {{Form::text('name', '', ['class'=>'form-control', 'placeholder'=>'Guest Name'])}}
                    </div>
                    <div class="form-group form-inline">
                        <div class="row">
                            <div class="col-sm-6">
                                {{Form::label('room_id', 'Select Room')}}
                                <select name="room_id" class="form-control">
                                    <option value="" selected="selected">Select a Room</option>
                                    @foreach(App\Room::all() as $key)
                                        <option value="{{$key->id}}">{{$key->room_number}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6 text-right">
                                {{Form::label('rate', 'Rate')}}
                                {{Form::text('rate', '', ['class'=>'form-control', 'placeholder'=>'Rate of the Room'])}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Close</button>
                    {{Form::submit('Save', ['class' => 'btn btn-primary'])}}
                </div>
            {{Form::close()}}
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>