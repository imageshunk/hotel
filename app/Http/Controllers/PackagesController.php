<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Package;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PackagesController extends Controller
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
        if(!auth()->user()->hasAnyRole(Role::all())){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }
        $packages = Package::latest()->get();
        return view('package.index')->with('packages', $packages);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!auth()->user()->hasRole('admin')){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }
        return view('package.create');
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
            'package_name' => 'required|string',
        ]);

        $package = new Package;
        $package->package_name = $request->input('package_name');
        $package->save();

        return redirect('/room-types')->with('success', 'Package Added');
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
        $package = Package::find($id);
        return view('package.edit')->with('package', $package);
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
            'package_name' => 'required|string',
        ]);

        $package = Package::find($id);
        $package->package_name = $request->input('package_name');
        $package->save();

        return redirect('/packages')->with('success', 'Package Updated');
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
        $package = Package::find($id);
        $package->delete();
        return redirect()->back();
    }
}
