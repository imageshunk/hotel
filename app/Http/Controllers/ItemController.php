<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Setting;

class ItemController extends Controller
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
        $items = Item::latest()->paginate(20);
        return view('item.index')->with('items', $items);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Setting::first() == null){
            return redirect('/settings')->with('error', 'Site Settings needs to be filled!');
        }
        if(!auth()->user()->hasRole('admin')){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }
        return view('item.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!auth()->user()->hasRole('admin')){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }
        $this->validate($request, [
            'item_name' => 'required|string',
            'item_price' => 'required|integer',
            'qty' => 'nullable|integer',
            'item_category' => 'required',
        ]);

        if($request->input('item_category') == 2){
            $this->validate($request, [
                'qty' => 'required|integer|min:1',
            ]);
        }

        $item = new Item;
        $item->item_name = $request->input('item_name');
        $item->item_category = $request->input('item_category');
        $item->item_price = $request->input('item_price');
        $item->qty = $request->input('qty');
        $item->save();

        return redirect()->back()->with('success', 'Item Added');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!auth()->user()->hasRole('admin')){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }
        $item = Item::find($id);
        return view('item.edit')->with('item', $item);
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
        if(!auth()->user()->hasRole('admin')){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }
        $this->validate($request, [
            'item_name' => 'required|string',
            'item_price' => 'required|integer|min:1',
            'item_category' => 'required',
        ]);

        if($request->input('item_category') != 1){
            $this->validate($request, [
                'qty' => 'required|integer|min:1',
            ]);
        }

        $item = Item::find($id);
        $item->item_name = $request->input('item_name');
        $item->item_category = $request->input('item_category');
        $item->item_price = $request->input('item_price');
        $item->qty = $request->input('qty');
        $item->save();

        return redirect('/items')->with('success', 'Item Added');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!auth()->user()->hasRole('admin')){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }
        $item = Item::find($id);
        $item->delete();
        return redirect('/items')->with('error', 'Item Removed');
    }
}
