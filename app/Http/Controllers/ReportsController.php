<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\CheckIn;
use App\Order;
use App\Expense;
use App\Item;
use App\Restuarent;
use Carbon\Carbon;
use PDF;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Setting;

class ReportsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {

        if(Setting::first() == null){
            return redirect('/settings')->with('error', 'Site Settings needs to be filled!');
        }
        if(!auth()->user()->hasRole('admin')){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }
        return view('reports.index');
    }

    public function users(Request $request){
        if(!auth()->user()->hasRole('admin')){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }
        if(auth()->user()->role != 'employee' && auth()->user()->role != 'admin'){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }
        $toDate = Carbon::parse($request->input('toDate'))->endOfDay();
        $fromDate = Carbon::parse($request->input('fromDate'))->startOfDay();

        $guests = User::where('role', 'guest')
        ->whereBetween('created_at', [$fromDate, $toDate])->get();
        if(count($guests)<=0){
            return redirect()->back()->with('error', 'No available guests registered on this time frame');
        }
        $dates = [
            'fromDate' => $fromDate,
            'toDate'   => $toDate
        ];

        $pdf = PDF::loadView('reports.pdfs.guests', compact(['guests', 'dates']));
        return $pdf->stream('document.pdf');
    }

    public function bookings(Request $request){
        if(!auth()->user()->hasRole('admin')){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }
        $toDate = Carbon::parse($request->input('toDate'))->endOfDay();
        $fromDate = Carbon::parse($request->input('fromDate'))->startOfDay();

        $bookings = CheckIn::whereBetween('created_at', [$fromDate, $toDate])->get();

        if(count($bookings)<=0){
            return redirect()->back()->with('error', 'No available bookings on this time frame');
        }

        $dates = [
            'fromDate' => $fromDate,
            'toDate'   => $toDate
        ];

        $pdf = PDF::loadView('reports.pdfs.booking', compact(['bookings', 'dates']));
        return $pdf->stream('document.pdf');
    }

    public function orders(Request $request){
        if(!auth()->user()->hasRole('admin')){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }
        $toDate = Carbon::parse($request->input('toDate'))->endOfDay();
        $fromDate = Carbon::parse($request->input('fromDate'))->startOfDay();

        $orders = Order::whereNotIn('status', ['Order Placed'])->whereBetween('created_at', [$fromDate, $toDate])->get();
        
        $restuarents = Restuarent::whereNotIn('status', ['Order Placed'])->whereBetween('created_at', [$fromDate, $toDate])->get();

        if(count($orders)<=0){
            return redirect()->back()->with('error', 'No available orders on this time frame');
        }

        $dates = [
            'fromDate' => $fromDate,
            'toDate'   => $toDate
        ];

        $pdf = PDF::loadView('reports.pdfs.orders', compact(['orders', 'dates', 'restuarents']));
        return $pdf->stream('document.pdf');
    }

    public function roomRevenue(Request $request){
        if(!auth()->user()->hasRole('admin')){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }

        $toDate = Carbon::parse($request->input('toDate'))->endOfDay();
        $fromDate = Carbon::parse($request->input('fromDate'))->startOfDay();

        if($request->input('room_id') == ''){
            $checkins = CheckIn::where([
                ['status', 'Checked Out']
            ])->whereBetween('created_at', [$fromDate, $toDate])
            ->get();
        }else{
            $checkins = CheckIn::where([
                ['room_id', $request->input('room_id')],
                ['status', 'Checked Out']
            ])->whereBetween('created_at', [$fromDate, $toDate])
            ->get();
        }

        if(count($checkins)<=0){
            return redirect()->back()->with('error', 'Records Not Found');
        }

        $dates = [
            'fromDate' => $fromDate,
            'toDate'   => $toDate
        ];

        $pdf = PDF::loadView('reports.pdfs.room-revenue', compact(['checkins', 'dates']));
        return $pdf->stream('document.pdf');
    }

    public function expense(Request $request){
        if(!auth()->user()->hasRole('admin')){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }

        $toDate = Carbon::parse($request->input('toDate'))->endOfDay();
        $fromDate = Carbon::parse($request->input('fromDate'))->startOfDay();

        if($request->input('expense_category_id') != ''){
            $expenses = Expense::where([
                ['category_id', $request->input('expense_category_id')]
            ])->whereBetween('created_at', [$fromDate, $toDate])
            ->get();
        }else{
            $expenses = Expense::whereBetween('created_at', [$fromDate, $toDate])->get();
        }

        if(count($expenses)<=0){
            return redirect()->back()->with('error', 'Records Not Found');
        }

        $dates = [
            'fromDate' => $fromDate,
            'toDate'   => $toDate
        ];

        $pdf = PDF::loadView('reports.pdfs.expenses', compact(['expenses', 'dates']));
        return $pdf->stream('document.pdf');
    }

    public function inventory(Request $request){
        if(!auth()->user()->hasRole('admin')){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }

        if($request->input('item_id') != ''){
            $items = Item::where([
                ['id', $request->input('item_id')]
            ])->get();
        }else{
            $items = Item::get();
        }

        if(count($items)<=0){
            return redirect()->back()->with('error', 'Records Not Found');
        }

        $pdf = PDF::loadView('reports.pdfs.inventory', compact(['items']));
        return $pdf->stream('document.pdf');
    }
}
