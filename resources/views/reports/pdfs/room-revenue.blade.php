<!DOCTYPE html>
<html>
<head>
	<title>Rooms Revenue</title>
	<style>
		#rooms {
		font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
		border-collapse: collapse;
		width: 100%;
		}

		#rooms td, #rooms th {
		border: 1px solid #ddd;
		padding: 8px;
		}

		#rooms tr:nth-child(even){background-color: #f2f2f2;}

		#rooms tr:hover {background-color: #ddd;}

		#rooms th {
		padding-top: 12px;
		padding-bottom: 12px;
		text-align: left;
		background-color: #4CAF50;
		color: white;
		}
        .bold{
            font-size: 20px;
            font-weight: bold;
        }
	</style>
</head>
<body>
	<p style="font-weigth:bold; font-size:30px;">Room Revenue <small style="font-size:12px;">{{$dates['fromDate']}} to {{$dates['toDate']}}</small></p><hr>

	<table id="rooms">
        <tr>
            <th>Guest Name</th>
            <th>Room Number</th>
            <th>Check In Date</th>
            <th>Check Out Date</th>
            <th>Status</th>
            <th>Booked at</th>
            <th>Revenue</th>
        </tr>
        @foreach($checkins as $booking)
            <tr>
                <td>{{$booking->guest_name}}</td>
                <?php $room = App\Room::where('id', $booking->room_id)->first(); ?>
                <td>{{$room->room_number}}</td>
                <td>{{$booking->check_in_date}}</td>
                <td>{{$booking->check_out_date}}</td>
                <td>{{$booking->status}}</td>
                <td>{{ \Carbon\Carbon::parse($booking->created_at)->diffForHumans() }}</td>
                <td>{{App\Setting::first()->currency}} {{$booking->total}}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="6" class="bold">Total Revenue: </td>
            <td class="bold">
                {{App\Setting::first()->currency}} {{$checkins->sum('total')}}
            </td>
        </tr>
        <tr>
            <td class="bold">Total Rooms:</td>
            <td class="bold">{{App\Room::all()->count()}}</td>
            <td colspan="4" class="bold">Total Revenue for all Rooms</td>
            <td class="bold">
                <?php
                    $checkins = App\CheckIn::where([
                        ['status', 'Checked Out']
                    ])->get();

                    $count = 0;

                    foreach($checkins as $checkin){
                        $count += $checkin->total;
                    }
                ?>
                {{App\Setting::first()->currency}} {{$count}}
            </td>
        </tr>
	</table>
</body>
</html>