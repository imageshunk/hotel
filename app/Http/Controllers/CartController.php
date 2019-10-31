<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cart;
use App\Item;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Setting;

class CartController extends Controller
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
        $presents = $request->get('present');
        foreach($presents as $present){
            $amount = Item::find($present);
            $available_cart_items = Cart::where([
                ['item_id', $present],
                ['guest_id', $request->input('guest_id')]
            ])->first();
            if($available_cart_items){
                $available_cart_items->quantity += 1;
                $available_cart_items->save();
            }else{
                $cart = new Cart;
                $cart->item_id = $present;
                $cart->quantity = 1;
                $cart->amount = $amount->item_price;
                $cart->room_id = $request->input('room_id');
                $cart->guest_id = $request->input('guest_id');
                $cart->status = 'Pending';
                $cart->save();
            }
        }

        $guest_id = $request->input('guest_id');

        return redirect('/cart/'.$guest_id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(!auth()->user()->hasAnyRole(Role::all())){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }
        $carts = Cart::where([
            ['guest_id', $id],
            ['status', 'Pending']
        ])->get();

        $guest = $id;

        return view('cart.index')->with(compact('carts', 'guest'));
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
        $cart = Cart::find($id);
        $amount = Item::find($cart->item_id);
        $amount_total = $request->input('quantity') * $amount->item_price;
        $cart->quantity = $request->input('quantity');
        $cart->amount = $amount_total;
        $cart->save();

        return redirect()->back()->with('success', 'Cart Updated');
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
        $cart = Cart::find($id);
        $cart->delete();
        return redirect()->back()->with('error', 'Item Removed');
    }
}
