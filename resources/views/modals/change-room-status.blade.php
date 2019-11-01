<div class="modal fade" id="{{$room->room_number}}" style="display: none; color:#000;">
    <div class="modal-dialog" style="width:40%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">CHANGE ROOM STATUS - {{$room->room_number}}</h4>
            </div>
            {!! Form::open(['action' => ['RoomController@update', $room->id], 'method' => 'POST','enctype' => 'multipart/form-data', 'id' => 'new-user']) !!}
                <div class="modal-body">
                    <div class="form-group">
                        <select name="room_status" id="room_status" class="form-control">
                            <option value="">SELECT ROOM STATUS</option>
                            <option value="ready">Ready</option>
                            <option value="cleanup">Cleanup</option>
                            <option value="dirty">Dirty</option>
                        </select>
                    </div>
                    {{Form::submit('Save', ['class' => 'btn btn-success'])}} <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
                {{Form::hidden('_method','PUT')}}
            {!! Form::close() !!}
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>