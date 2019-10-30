<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UsersController extends Controller
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
    
    public function index(){
        if(!auth()->user()->hasRole('admin')){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }
        $employees = User::whereNotIn('role', ['guest'])->whereNotIn('id', [auth()->user()->id])->latest()->paginate();
        return view('employees.index')->with('employees', $employees);
    }

    public function create(){
        if(!auth()->user()->hasRole('admin')){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }
        return view('employees.create');
    }

    public function store(Request $request){
        if(!auth()->user()->hasRole('admin')){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }
        $this->validate($request, [
            'employee_name' => 'required|string',
            'email' => 'required|email|unique:users',
            'role' => 'required',
            'password' => 'required|string|min:6',
            'password2' => 'required|string|min:6',
        ]);

        if($request->input('password') == $request->input('password2')){
            $user = new User;
            $user->name = $request->input('employee_name');
            $user->email = $request->input('email');
            $user->role = $request->input('role');
            $user->password = Hash::make($request->input('password'));
            $user->save();

            if($request->input('role') == 'admin'){
                $user->assignRole('admin');
                $user->givePermissionTo('admin access');
            }

            if($request->input('role') == 'employee'){
                $user->assignRole('employee');
                $user->givePermissionTo('employee access');
            }

            if($request->input('role') == 'agent'){
                $user->assignRole('agent');
                $user->givePermissionTo('agent access');
            }
        }else{
            return redirect()->back()->with('error', "Password doesn't match");
        }

        return redirect()->back()->with('success', "Employee Added");
    }

    public function destroy($id){
        if(!auth()->user()->hasRole('admin')){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }
        $user = User::find($id);
        $user->delete();
        return redirect()->back()->with('success', "Employee Removed");
    }

    public function changePassword($id){
        if(!auth()->user()->hasRole('admin')){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }
        $user = User::find($id);
        return view('employees.change-password')->with('user', $user);
    }

    public function change(Request $request, $id){
        if(!auth()->user()->hasRole('admin')){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }
        $this->validate($request, [
            'password' => 'required|string|min:6',
            'password2' => 'required|string|min:6',
        ]);

        if($request->input('password') == $request->input('password2')){
            $user = User::find($id);
            $user->password = Hash::make($request->input('password'));
            $user->save();
        }else{
            return redirect()->back()->with('error', "Password doesn't match");
        }

        return redirect('/users')->with('success', "Password changed");
    }
}
