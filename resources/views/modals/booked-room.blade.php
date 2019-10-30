<div class="modal fade" id="booked-room-{{$room->id}}" style="display: none; color:#000;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Room Booked</h4>
            </div>
            <div class="modal-body">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        @foreach($checkins as $checkin)
                            <li class=""><a href="#tab_{{$checkin->id}}" data-toggle="tab">{{$checkin->guest_name}}</a></li>
                        @endforeach
                    </ul>
                    <div class="tab-content">
                        @foreach($checkins as $checkin)
                            <div class="tab-pane" id="tab_{{$checkin->id}}">
                                <div class="nav-tabs-custom">
                                    <ul class="nav nav-tabs">
                                        <li class="active"><a href="#tab_1_{{$checkin->id}}" data-toggle="tab" aria-expanded="true">Home</a></li>
                                        <li class=""><a href="#tab_2_{{$checkin->id}}" data-toggle="tab" aria-expanded="false">Options</a></li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane active text-black" id="tab_1_{{$checkin->id}}">
                                            <p>GUEST SUMMARY</p>
                                            <div class="box">
                                                <div class="box-header">
                                                    <h3 class="box-title">Items</h3>
                                                </div>
                                                <!-- /.box-header -->
                                                <div class="box-body no-padding">
                                                    <table class="table table-striped table-bordered">
                                                        <tbody>
                                                            <tr>
                                                                <th>Reservation #</th>
                                                                <th>Guest</th>
                                                                <th>Check In</th>
                                                                <th>Check Out</th>
                                                                <th>Comments</th>
                                                            </tr>
                                                            <tr>
                                                                <td>{{$checkin->id}}</td>
                                                                <td>{{$checkin->guest_name}}</td>
                                                                <td>{{$checkin->check_in_date}} ({{$checkin->check_in_time}})</td>
                                                                <td>{{$checkin->check_out_date}}</td>
                                                                <td>{{$checkin->comments}}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <!-- /.box-body -->
                                                <div class="box-footer clearfix">
                                                    <button class="btn btn-primary" type="button" id="invoice-{{$checkin->id}}">Invoice</button><br><br>
                                                    <div id="invoice_detail-{{$checkin->id}}" style="display:none">
                                                        <div style="padding:5px; border-radius:4px">
                                                            Total Due <span class="pull-right text-white">{{$checkin->total}}</span>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                        <div style="padding:5px; border-radius:4px">
                                                            Total Received <span class="pull-right text-white">0.00</span>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                        <div style="padding:5px; border-radius:4px">
                                                            <b>Total to be paid</b> <span class="pull-right text-white">{{$checkin->total}}</span>
                                                            <div class="clearfix"></div>
                                                        </div>

                                                        <button class="btn btn-primary" type="button" id="accomodation-{{$checkin->id}}">Show Details</button> 
                                                        <a href="/checkout/{{$checkin->id}}" class="btn btn-danger">Check Out Anyway</a>
                                                        <br><br>

                                                        <div id="accomodation_detail-{{$checkin->id}}" style="display:none">
                                                            <div class="box">
                                                                <div class="box-header with-border">
                                                                    <h3 class="box-title">Details</h3>
                                                                </div>
                                                                <!-- /.box-header -->
                                                                <div class="box-body">
                                                                    <table class="table table-bordered">
                                                                        <tbody>
                                                                            <tr>
                                                                                <th>Invoice #</th>
                                                                                <th>Description</th>
                                                                                <th>Total</th>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>{{$checkin->id}}</td>
                                                                                <td>Room charges for {{$checkin->check_in_date}}</td>
                                                                                <td>{{$checkin->total}}</td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <!-- /.box-body -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <script src="https://code.jquery.com/jquery-3.3.1.min.js"
                                        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
                                        crossorigin="anonymous"></script>
                                        <script>
                                            $( "#invoice-{{$checkin->id}}" ).click(function() {
                                                $("#invoice_detail-{{$checkin->id}}").toggle();
                                            });

                                            $( "#accomodation-{{$checkin->id}}" ).click(function() {
                                                $("#accomodation_detail-{{$checkin->id}}").toggle();
                                            });
                                        </script>

                                        <!-- /.tab-pane -->
                                        <div class="tab-pane" id="tab_2_{{$checkin->id}}">
                                            <h4>CHANGE ROOM</h4>
                                            {!! Form::open(['action' => ['CheckInController@update', $checkin->id], 'method' => 'POST','enctype' => 'multipart/form-data', 'id' => 'new-user']) !!}
                                                <?php $rooms = App\Room::whereNotIn('room_status', ['booked', 'cleanup', 'dirty'])->get(); ?>
                                                <div class="form-group">
                                                    <select name="room_id" id="room_id" class="form-control">
                                                        <option value="">SELECT ROOM</option>
                                                        @foreach($rooms as $room)
                                                            <option value="{{$room->id}}">{{$room->room_number}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                {{Form::submit('Confirm Transfer', ['class' => 'btn btn-success', 'id' => 'submit-user'])}} <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                                {{Form::hidden('_method','PUT')}}
                                            {!! Form::close() !!}
                                        </div>
                                        <!-- /.tab-pane -->
                                    </div>
                                    <!-- /.tab-content -->
                                </div>
                            </div>
                            <!-- /.tab-pane -->
                        @endforeach
                    </div>
                    <!-- /.tab-content -->
                </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>