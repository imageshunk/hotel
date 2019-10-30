<div class="modal modal-info fade" id="new-guest" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Add Room</h4>
            </div>
            {!! Form::open(['enctype' => 'multipart/form-data', 'id' => 'new-user']) !!}
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        {{Form::label('title', 'Title')}}
                        {{Form::select('title', ['Mr.' => 'Mr.', 'Ms.' => 'Ms.', 'Mrs.' => 'Mrs.'], '', ['class' => 'form-control', 'placeholder' => 'Title', 'id' => 'title'])}}
                    </div>
                    <div class="form-group">
                        {{Form::label('name', 'Name')}}
                        {{Form::text('name', '', ['class' => 'form-control', 'placeholder' => 'Name', 'id' => 'name'])}}
                    </div>
                    <div class="form-group">
                        {{Form::label('email', 'Email')}}
                        {{Form::text('email', '', ['class' => 'form-control', 'placeholder' => 'Valid Email', 'id' => 'email'])}}
                    </div>
                    <div class="form-group">
                        {{Form::label('passport', 'NIC/Passport')}}
                        {{Form::text('passport', '', ['class' => 'form-control', 'placeholder' => 'NIC/Passport No.', 'id' => 'passport'])}}
                    </div>
                    <div class="form-group">
                        {{Form::label('mobile', 'Phone Number')}}
                        {{Form::text('mobile', '', ['class' => 'form-control', 'placeholder' => 'Phone Number', 'id' => 'mobile'])}}
                    </div>
                    <div class="form-group">
                        {{Form::label('organisation', 'Organisation')}}
                        {{Form::text('organisation', '', ['class' => 'form-control', 'placeholder' => 'Organisation', 'id' => 'organisation'])}}
                    </div>
                    <div class="form-group">
                        {{Form::label('country', 'Country')}}
                        {{Form::text('country', '', ['class' => 'form-control', 'placeholder' => 'Country', 'id' => 'country'])}}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
                    {{Form::submit('Save', ['class' => 'btn btn-outline', 'id' => 'submit-user'])}}
                </div>
            {!! Form::close() !!}
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
jQuery( document ).ready( function( $ ) {
    $( '#new-user' ).on( 'submit', function(e) {
        e.preventDefault();
        var title = $('#title').val();
        var name = $('#name').val();
        var email = $('#email').val();
        var passport = $('#passport').val();
        var mobile = $('#mobile').val();
        var organisation = $('#organisation').val();
        var country = $('#country').val();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "POST",
            url: '/guests',
            data: { title:title, name:name, email:email, passport:passport, mobile:mobile, organisation:organisation, country:country }, 
            success: function( data ) {
                $('#new-guest').toggle();
                $(document.body).removeClass("modal-open");
                $(".modal-backdrop").remove();

                $('#guest_name').val(data.name);
                $('#guest_mobile').val(data.mobile);
                $('#guest_organisation').val(data.organisation);
                $('#guest_id').val(data.guestid);
            }
        });
    });
});
</script>