<!DOCTYPE html>
<html>
<head>
	<title>Guests</title>
	<style>
		#guests {
		font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
		border-collapse: collapse;
		width: 100%;
		}

		#guests td, #guests th {
		border: 1px solid #ddd;
		padding: 8px;
		}

		#guests tr:nth-child(even){background-color: #f2f2f2;}

		#guests tr:hover {background-color: #ddd;}

		#guests th {
		padding-top: 12px;
		padding-bottom: 12px;
		text-align: left;
		background-color: #4CAF50;
		color: white;
		}
	</style>
</head>
<body>
	<p style="font-weigth:bold; font-size:30px;">Guests <small style="font-size:12px;">{{$dates['fromDate']}} to {{$dates['toDate']}}</small></p><hr>

	<table id="guests">
		<tr>
			<th style="width: 20px">ID</th>
			<th>Name</th>
			<th>Email</th>
			<th>NIC/Passport</th>
			<th>Phone Number</th>
			<th>Organisation</th>
			<th>Country</th>
			<th>Registered At</th>
		</tr>
		@foreach($guests as $guest)
			<tr>
				<td>{{$guest->id}}</td>
				<td>{{$guest->title}} {{$guest->name}}</td>
				<td>{{$guest->email}}</td>
				<td>{{$guest->passport}}</td>
				<td>{{$guest->mobile}}</td>
				<td>{{$guest->organisation}}</td>
				<td>{{$guest->country}}</td>
				<td>{{\Carbon\Carbon::parse($guest->created_at)->diffForHumans()}}</td>
			</tr>
		@endforeach
	</table>
</body>
</html>