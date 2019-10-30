<!DOCTYPE html>
<html>
<head>
	<title>Bookings</title>
	<style>
		#bookings {
		font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
		border-collapse: collapse;
		width: 100%;
		}

		#bookings td, #bookings th {
		border: 1px solid #ddd;
		padding: 8px;
		}

		#bookings tr:nth-child(even){background-color: #f2f2f2;}

		#bookings tr:hover {background-color: #ddd;}

		#bookings th {
		padding-top: 12px;
		padding-bottom: 12px;
		text-align: left;
		background-color: #4CAF50;
		color: white;
		}
	</style>
</head>
<body>
	<p style="font-weigth:bold; font-size:30px;">Bookings <small style="font-size:12px;">{{$dates['fromDate']}} to {{$dates['toDate']}}</small></p><hr>

	<table id="bookings">
        <tr>
            <th>Guest Name</th>
            <th>Guest Id</th>
            <th>Guest Phone Number</th>
            <th>Organisation</th>
            <th>Room Number</th>
            <th>Check In Date</th>
            <th>Check Out Date</th>
            <th>Utilities</th>
            <th>Status</th>
            <th>Booked at</th>
            <th>Payment Method</th>
        </tr>
        @foreach($bookings as $booking)
            <tr>
                <td>{{$booking->guest_name}}</td>
                <td>{{$booking->guest_id}}</td>
                <td>{{$booking->mobile}}</td>
                <td>{{$booking->organisation}}</td>
                <?php $room = App\Room::where('id', $booking->room_id)->first(); ?>
                <td>{{$room->room_number}}</td>
                <td>{{$booking->check_in_date}}</td>
                <td>{{$booking->check_out_date}}</td>
                <td>
                    <?php $utilities = explode(",",$booking->utilities); ?>
                    @foreach($utilities as $utility)
                        <?php $option = App\Utility::where('id', $utility)->first(); ?>
                        <i>{{$option['utility']}}</i><br>
                    @endforeach
                </td>
                <td>{{$booking->status}}</td>
                <td>{{ \Carbon\Carbon::parse($booking->created_at)->diffForHumans() }}</td>
                <td>{{$booking->payment_method}}</td>
            </tr>
        @endforeach
	</table>
</body>
</html>