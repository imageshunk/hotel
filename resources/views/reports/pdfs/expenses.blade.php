<!DOCTYPE html>
<html>
<head>
	<title>Expenses</title>
	<style>
		#expenses {
		font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
		border-collapse: collapse;
		width: 100%;
		}

		#expenses td, #expenses th {
		border: 1px solid #ddd;
		padding: 8px;
		}

		#expenses tr:nth-child(even){background-color: #f2f2f2;}

		#expenses tr:hover {background-color: #ddd;}

		#expenses th {
		padding-top: 12px;
		padding-bottom: 12px;
		text-align: left;
		background-color: #4CAF50;
		color: white;
		}
	</style>
</head>
<body>
	<p style="font-weigth:bold; font-size:30px;">Expenses <small style="font-size:12px;">{{$dates['fromDate']}} to {{$dates['toDate']}}</small></p><hr>

	<table id="expenses">
        <tr>
            <th>#</th>
            <th>Date</th>
            <th>Receipt No.</th>
            <th>Particulars</th>
            <th>Expense Category</th>
            <th>Payment Type</th>
            <th>Amount</th>
            <th>Added By</th>
        </tr>
        @foreach($expenses as $expense)
            <tr>
                <td>{{$expense->id}}</td>
                <td>{{\Carbon\Carbon::parse($expense->date)->format('d M, Y')}}</td>
                <td>{{$expense->receipt}}</td>
                <td>{{$expense->particular}}</td>
                <td>{{App\ExpenseCategory::where('id', $expense->category_id)->first()->name}}</td>
                <td>{{DB::table('payment_types')->where('id', $expense->payment_type_id)->first()->payment_type}}</td>
                <td>{{App\Setting::first()->currency}} {{$expense->amount}}</td>
                <td>{{App\User::find($expense->added_by_id)->name}}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="6">Total Expenditure</td>
            <td colspan="2">{{App\Setting::first()->currency}} {{$expenses->sum('amount')}}</td>
        </tr>
        <tr>
            <td colspan="6">Total Expenditure for all Categories</td>
            <td colspan="2">{{App\Setting::first()->currency}} {{App\Expense::sum('amount')}}</td>
        </tr>
	</table>
</body>
</html>