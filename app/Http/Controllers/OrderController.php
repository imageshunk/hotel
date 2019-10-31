<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cart;
use App\Order;
use App\Item;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Setting;

class OrderController extends Controller
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
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if(Setting::first() == null){
            return redirect('/settings')->with('error', 'Site Settings needs to be filled!');
        }
        if(!auth()->user()->hasAnyRole(Role::all())){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }
        $orders = Order::latest()->paginate(20);
        return view('orders.index')->with('orders', $orders);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!auth()->user()->hasAnyRole(Role::all())){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }
        $id = $request->input('guest_id');
        $carts = Cart::where('guest_id', $id)->get();
        foreach($carts as $cart){
            $order = new Order;
            $order->item_id = $cart->item_id;
            $order->quantity = $cart->quantity;
            $order->room_id = $cart->room_id;
            $order->guest_id = $cart->guest_id;
            $order->agent_id = $request->input('agent_id');
            $order->status = "Order Placed";
            $order->amount = $cart->amount;
            $order->save();

            $cart->delete();
        }

        return redirect('/room-service')->with('success', 'Order Placed');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!auth()->user()->hasAnyRole(Role::all())){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }
        $order = Order::find($id);
        $order->payment_status = "Paid";
        $order->save();
        return redirect()->back()->with('success', 'Order Marked as Paid');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(!auth()->user()->hasAnyRole(Role::all())){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }
        $order = Order::find($id);

        $item = Item::find($order->item_id);
        if($item->qty != NULL && $item->qty > 0){
            if($request->input('status') == "delivered"){
                if($item->qty >= $order->quantity){
                    $item->qty = $item->qty - $order->quantity;
                    $item->save();
                }
                else{
                    return redirect()->back()->with('error', 'Order could not be completed, item quantity is less than order quantity!');
                }
            }
        }
        $order->status = $request->input('status');
        $order->save();

        return redirect()->back()->with('success', 'Order Status Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!auth()->user()->hasAnyRole(Role::all())){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }
        $order = Order::find($id);
        $order->delete();
        return redirect()->back()->with('error', 'Order Deleted');
    }
}
