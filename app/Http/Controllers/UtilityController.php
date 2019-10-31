<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utility;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Setting;

class UtilityController extends Controller
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
        if(auth()->user()->role != 'admin'){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }
        $utilities = Utility::all();
        return view('utility.index')->with('utilities', $utilities);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(auth()->user()->role != 'admin'){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }
        return view('utility.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(auth()->user()->role != 'admin'){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }
        $this->validate($request, [
            'utility' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|integer',
        ]);

        $utility = new Utility;
        $utility->utility = $request->input('utility');
        $utility->description = $request->input('description');
        $utility->price = $request->input('price');
        $utility->save();

        return redirect('/utilities')->with('success', 'Utility Added');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(auth()->user()->role != 'admin'){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }
        $utility = Utility::find($id);
        return view('utility.edit')->with('utility', $utility);
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
        if(auth()->user()->role != 'admin'){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }
        $this->validate($request, [
            'utility' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|integer',
        ]);

        $utility = Utility::find($id);
        $utility->utility = $request->input('utility');
        $utility->description = $request->input('description');
        $utility->price = $request->input('price');
        $utility->save();

        return redirect('/utilities')->with('success', 'Utility Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(auth()->user()->role != 'admin'){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }
        $utility = Utility::find($id);
        $utility->delete();

        return redirect('/utilities')->with('success', 'Utility Removed');
    }
}
