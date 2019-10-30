<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Setting;

class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // $role = Role::create(['name' => 'employee']);
        // $permission = Permission::create(['name' => 'employee access']);
        // $role = Role::create(['name' => 'agent']);
        // $permission = Permission::create(['name' => 'agent access']);
        // $role = Role::create(['name' => 'admin']);
        // $permission = Permission::create(['name' => 'admin access']);

        // auth()->user()->assignRole('admin');
        // auth()->user()->givePermissionTo('admin access');
        // return auth()->user()->getAllPermissions();
        $settings = Setting::first();
        if($settings){
            if(!auth()->user()->hasAnyRole(Role::all())){
                return redirect()->back()->with('error', 'Sorry, You are not authorized');
            }
            return view('home');
        }else{
            return redirect('/settings');
        }
    }
}
