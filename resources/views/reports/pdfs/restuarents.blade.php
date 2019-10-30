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

	<table id="orders">
        <tr>
            <th style="width: 20px">ID</th>
            <th>Item Name</th>
            <th>Quantity</th>
            <th>Guest</th>
            <th>Amount</th>
            <TH>Status</TH>
            <th>Ordered At</th>
            <TH colspan="3">Actions</TH>
        </tr>
        @foreach($orders as $order)
            <tr>
                <td>{{$order->id}}</td>
                <?php $item = App\Item::find($order->item_id); ?>
                <td>{{$item->item_name}}</td>
                <td>{{$order->quantity}}</td>
                <?php $guest = App\User::find($order->guest_id); ?>
                <td>{{$guest->name}}</td>
                <td>{{$order->quantity}} x {{$item->item_price}} = {{$order->amount}}</td>
                <td style="text-transform:capitalize">{{$order->status}}</td>
                <td>{{ \Carbon\Carbon::parse($order->created_at)->diffForHumans() }}</td>
                <td><a href="/restuarent/order/invoice/{{$order->id}}" class="btn btn-success">Invoice</a></td>
            </tr>
        @endforeach
	</table>
</body>
</html>