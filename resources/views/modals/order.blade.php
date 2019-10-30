<div class="modal fade" id="order-{{$order->id}}" style="display: none; color:#000;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">CHANGE ORDER STATUS - {{$order->id}}</h4>
            </div>
            {!! Form::open(['action' => ['OrderController@update', $order->id], 'method' => 'POST','enctype' => 'multipart/form-data', 'id' => 'new-user']) !!}
                <div class="modal-body">
                    <div class="form-group">
                        <select name="status" id="status" class="form-control">
                            <option value="">SELECT ORDER STATUS</option>
                            <option value="delivered">Delivered</option>
                            <option value="cancelled">Cancelled</option>
                            <option value="not available">Not Available</option>
                        </select>
                    </div>
                    {{Form::submit('Save', ['class' => 'btn btn-success'])}} <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
                {{Form::hidden('_method','PUT')}}
            {!! Form::close() !!}
            <div class="modal-footer">
                <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>