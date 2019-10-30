<div class="modal fade" id="generate_{{$booking->id}}" style="display: none; color:#000;">
    <div class="modal-dialog" style="width:30%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Generate Invoice</h4>
            </div>
            {!! Form::open(['action' => 'InvoiceController@booking', 'method' => 'POST','enctype' => 'multipart/form-data', 'id' => 'invoice-generate']) !!}
                <div class="modal-body">
                    <div class="form-group">
                        <select name="payment_type" id="payment_type" class="form-control">
                            <option value="" selected="selected">SELECT Payment Method</option>
                            @foreach(DB::table('payment_types')->get() as $key)
                                <option value="{{$key->payment_type}}">{{$key->payment_type}}</option>
                            @endforeach
                        </select>
                    </div>
                    {{Form::hidden('id', $booking->id)}}
                    {{Form::hidden('type', 'Receipt')}}
                    {{Form::submit('Generate', ['class' => 'btn btn-success'])}}
                </div>
            {!! Form::close() !!}
            <div class="modal-footer">
                <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>