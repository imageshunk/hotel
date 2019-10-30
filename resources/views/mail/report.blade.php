<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        .box{
            border: 2px solid #ccc;
            border-radius: 10px;
        }
        .list{
            list-style-type:none;
        }
        .list li{
            display:block;
            padding:8px;
        }
    </style>
</head>
<body>
    <h1>Daily Reports</h1>
    <div class="box">
        <ul class="list">
            <li>Romm Service Revenue: {{App\Setting::first()->currency}}{{App\Order::where('updated_at', 'like', \Carbon\Carbon::now()->format('Y-m-d').'%')->where('payment_status', 'Paid')->sum('amount')}}</li>
            <li>Restuarent Revenue: {{App\Setting::first()->currency}}{{App\Restuarent::where('updated_at', 'like', \Carbon\Carbon::now()->format('Y-m-d').'%')->where('payment_status', 'Paid')->sum('amount')}}</li>
            <li>Room Revenue: {{App\Setting::first()->currency}}{{App\CheckIn::where('updated_at', 'like', \Carbon\Carbon::now()->format('Y-m-d').'%')->where('status', 'Checked Out')->sum('total')}}</li>
            <li>Total: {{App\Setting::first()->currency}}{{App\CheckIn::where('updated_at', 'like', \Carbon\Carbon::now()->format('Y-m-d').'%')->where('status', 'Checked Out')->sum('total') + App\Order::where('updated_at', 'like', \Carbon\Carbon::now()->format('Y-m-d').'%')->where('payment_status', 'Paid')->sum('amount') + App\Restuarent::where('updated_at', 'like', \Carbon\Carbon::now()->format('Y-m-d').'%')->where('payment_status', 'Paid')->sum('amount')}}</li>
            <li>Today Expense: {{App\Setting::first()->currency}}{{App\Expense::where('created_at', 'like', \Carbon\Carbon::now()->format('Y-m-d').'%')->sum('amount')}}</li>
            <li>Total Profit Today: {{App\Setting::first()->currency}}{{App\CheckIn::where('updated_at', 'like', \Carbon\Carbon::now()->format('Y-m-d').'%')->where('status', 'Checked Out')->sum('total') + App\Order::where('updated_at', 'like', \Carbon\Carbon::now()->format('Y-m-d').'%')->where('payment_status', 'Paid')->sum('amount') + App\Restuarent::where('updated_at', 'like', \Carbon\Carbon::now()->format('Y-m-d').'%')->where('payment_status', 'Paid')->sum('amount') - App\Expense::where('created_at', 'like', \Carbon\Carbon::now()->format('Y-m-d').'%')->sum('amount')}}</li>
        </ul>
    </div>

    <br><br>
    <p>This is a automatic generated mail. Please do not reply back.</p>
    
</body>
</html>