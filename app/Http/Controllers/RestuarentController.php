<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Item;
use App\Restuarent;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Setting;

class RestuarentController extends Controller
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
    
    public function store(Request $request){
        if(!auth()->user()->hasAnyRole(Role::all())){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }
        $guest = new User;
        $guest->title = $request->input('title');
        $guest->name = $request->input('name');
        $guest->mobile = $request->input('mobile');
        $guest->save();

        $guest = $guest->id;

        $presents = $request->get('present');
        foreach($presents as $present){
            $amount = Item::find($present);
            $available_cart_items = Restuarent::where([
                ['item_id', $present],
                ['guest_id', $guest]
            ])->first();
            if($available_cart_items){
                $available_cart_items->quantity += 1;
                $available_cart_items->save();
            }else{
                $cart = new Restuarent;
                $cart->item_id = $present;
                $cart->quantity = 1;
                $cart->amount = $amount->item_price;
                $cart->guest_id = $guest;
                $cart->save();
            }
        }

        return redirect('/restuarent/cart/'.$guest);
    }

    public function show($id){
        if(!auth()->user()->hasAnyRole(Role::all())){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }
        $carts = Restuarent::where([
            ['guest_id', $id]
        ])->get();

        return view('restuarent.cart')->with(compact('carts', 'id'));
    }

    public function update(Request $request, $id){
        if(!auth()->user()->hasAnyRole(Role::all())){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }
        $cart = Restuarent::find($id);
        $amount = Item::find($cart->item_id);
        $amount_total = $request->input('quantity') * $amount->item_price;
        $cart->quantity = $request->input('quantity');
        $cart->amount = $amount_total;
        $cart->save();

        return redirect()->back()->with('success', 'Cart Updated');
    }

    public function destroy($id)
    {
        if(!auth()->user()->hasAnyRole(Role::all())){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }
        $cart = Restuarent::find($id);
        $cart->delete();
        return redirect()->back()->with('error', 'Item Removed');
    }

    public function order(Request $request){
        if(!auth()->user()->hasAnyRole(Role::all())){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }
        $guest = $request->input('guest_id');
        $carts = Restuarent::where([
            ['guest_id', $guest],
            ['status', 'Pending']
        ])->get();

        foreach($carts as $cart){
            $cart->status = 'Order Placed';
            $cart->save();
        }

        return redirect('/restuarents/orders')->with('success', 'Orders Placed');
    }

    public function list(){
        if(Setting::first() == null){
            return redirect('/settings')->with('error', 'Site Settings needs to be filled!');
        }
        if(!auth()->user()->hasAnyRole(Role::all())){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }
        $orders = Restuarent::latest()->paginate();
        return view('restuarent.orders')->with('orders', $orders);
    }

    public function setStatus(Request $request, $id){
        if(!auth()->user()->hasAnyRole(Role::all())){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }
        $order = Restuarent::find($id);
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

        return redirect()->back();
    }

    public function edit($id){
        if(!auth()->user()->hasAnyRole(Role::all())){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }
        $order = Restuarent::find($id);
        $order->payment_status = 'Paid';
        $order->save();
        return redirect()->back()->with('success','Order Marked as Paid');
    }
}
