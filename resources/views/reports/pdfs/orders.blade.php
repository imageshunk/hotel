<!DOCTYPE html>
<html>
<head>
	<title>Orders</title>
	<style>
		#orders {
		font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
		border-collapse: collapse;
		width: 100%;
		}

		#orders td, #orders th {
		border: 1px solid #ddd;
		padding: 8px;
		}

		#orders tr:nth-child(even){background-color: #f2f2f2;}

		#orders tr:hover {background-color: #ddd;}

		#orders th {
		padding-top: 12px;
		padding-bottom: 12px;
		text-align: left;
		background-color: #4CAF50;
		color: white;
		}
	</style>
</head>
<body>
	<p style="font-weigth:bold; font-size:30px;">Orders <small style="font-size:12px;">{{$dates['fromDate']}} to {{$dates['toDate']}}</small></p><hr>
    <h2>Room Service Revenue</h2>
	<table id="orders">
        <tr>
            <th style="width: 20px">ID</th>
            <th>Item Name</th>
            <th>Quantity</th>
            <th>Room Number</th>
            <th>Guest</th>
            <th>Agent</th>
            <th>Amount</th>
            <TH>Status</TH>
            <th>Ordered At</th>
        </tr>
        @foreach($orders as $order)
            <tr>
                <td>{{$order->id}}</td>
                <?php $item = App\Item::find($order->item_id); ?>
                <td>{{$item->item_name}}</td>
                <td>{{$order->quantity}}</td>
                <?php $room = App\Room::find($order->room_id); ?>
                <td>{{$room->room_number}}</td>
                <?php $guest = App\User::find($order->guest_id); ?>
                <td>{{$guest->name}}</td>
                <?php $agent = App\User::find($order->agent_id); ?>
                <td>
                    @if($agent)
                        {{$agent->name}}
                    @endif
                </td>
                <td>{{App\Setting::first()->currency}} {{$order->amount}}</td>
                <td style="text-transform:capitalize">{{$order->status}}</td>
                <td>{{ \Carbon\Carbon::parse($order->created_at)->diffForHumans() }}</td>
            </tr>
        @endforeach
        <tr>
            <th colspan="6">Total</th>
            <td colspan="3">{{App\Setting::first()->currency}} {{$orders->sum('amount')}}</td>
        </tr>
	</table>
	<br><br>
	<h2>Restuarent Revenue</h2>
	<table id="orders">
        <tr>
            <th style="width: 20px">ID</th>
            <th>Item Name</th>
            <th>Quantity</th>
            <th>Guest</th>
            <th>Amount</th>
            <TH>Status</TH>
            <th>Ordered At</th>
        </tr>
        @foreach($restuarents as $order)
            <tr>
                <td>{{$order->id}}</td>
                <?php $item = App\Item::find($order->item_id); ?>
                <td>{{$item->item_name}}</td>
                <td>{{$order->quantity}}</td>
                <?php $guest = App\User::find($order->guest_id); ?>
                <td>{{$guest->name}}</td>
                <td>{{App\Setting::first()->currency}} {{$order->amount}}</td>
                <td style="text-transform:capitalize">{{$order->status}}</td>
                <td>{{ \Carbon\Carbon::parse($order->created_at)->diffForHumans() }}</td>
            </tr>
        @endforeach
        <tr>
            <th colspan="4">Total</th>
            <td colspan="3">{{App\Setting::first()->currency}} {{$restuarents->sum('amount')}}</td>
        </tr>
	</table>
</body>
</html>