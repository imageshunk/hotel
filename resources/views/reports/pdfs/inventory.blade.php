<!DOCTYPE html>
<html>
<head>
	<title>Inventory</title>
	<style>
		#items {
		font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
		border-collapse: collapse;
		width: 100%;
		}

		#items td, #items th {
		border: 1px solid #ddd;
		padding: 8px;
		}

		#items tr:nth-child(even){background-color: #f2f2f2;}

		#items tr:hover {background-color: #ddd;}

		#items th {
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
	<p style="font-weigth:bold; font-size:30px;">Inventory</p><hr>

	<table id="items">
        <tr>
            <th>Item Name</th>
            <th>Item Category</th>
            <th>Updated at</th>
            <th>Quantity Left</th>
        </tr>
        @foreach($items as $item)
            <tr>
                <td>{{$item->item_name}}</td>
                <td>{{$item->item_category}}</td>
                <td>{{ \Carbon\Carbon::parse($item->updated_at)->diffForHumans() }}</td>
                <td>{{$item->qty}}</td>
            </tr>
        @endforeach
	</table>
</body>
</html>