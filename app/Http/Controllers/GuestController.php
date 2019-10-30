<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Order;
use App\Restuarent;
use App\CheckIn;
use Input;
use Validator;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class GuestController extends Controller
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
        $guests = User::where('role', 'guest')->orderBy('created_at', 'desc')->paginate(20);
        return view('guests.index')->with('guests', $guests);
    }

    public function create(){
        if(!auth()->user()->hasAnyRole(Role::all())){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }
        return view('guests.create');
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
        if(request()->ajax()){
            $guest = new User;
            $guest->title =  $request->input('title');
            $guest->name =  $request->input('name');
            $guest->email =  $request->input('email');
            $guest->passport =  $request->input('passport');
            $guest->mobile =  $request->input('mobile');
            $guest->organisation =  $request->input('organisation');
            $guest->role =  "guest";
            $guest->country =  $request->input('country');
            $guest->save();
            $name = $request->input('title').' '.$request->input('name');

            $response = array(
                'status' => 'success',
                'name' => $name,
                'mobile' => $request->input('mobile'),
                'organisation' => $request->input('organisation'),
                'guestid' => $guest->id,
            );
            
            return response()->json($response);
        }
        
        $rules = ['email'=>'unique:users'];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            // handler errors
            $error = $validator->errors();
            return redirect()->back()->with('error', 'Duplicate Email Entry');
        }
        
        $guest = new User;
        $guest->title =  $request->input('title');
        $guest->name =  $request->input('guest_name');
        $guest->email =  $request->input('guest_email');
        $guest->passport =  $request->input('passport');
        $guest->mobile =  $request->input('mobile');
        $guest->organisation =  $request->input('organisation');
        $guest->role =  "guest";
        $guest->country =  $request->input('country');
        $guest->save();

        return redirect()->back()->with('success', 'Guest Added');
    }

    public function search(Request $request){
        if(!auth()->user()->hasAnyRole(Role::all())){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }
        $input = $request->input('search');
        $query = User::query();
        $columns = ['name', 'title', 'email'];
        foreach($columns as $column){
            $query->orWhere($column, 'LIKE', '%' . $input . '%')->where('role', 'guest');
        }
        $guests = $query->paginate(20);
        return view('guests.index')->with('guests', $guests);
    }

    public function guest_search(Request $request){
        if(!auth()->user()->hasAnyRole(Role::all())){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }
        if($request->ajax()){
            $output="";
            $guests=User::where([
                ['name','LIKE','%'.$request->search."%"],
                ['role', 'guest']
            ])->orderBy('created_at', 'desc')->get();
            if($guests){
                foreach ($guests as $key => $guest) {
                    $output.='<option value="'.$guest->id.'">'.$guest->name.'</td>';
                }        
                return response()->json($output);
            }
        }
    }

    public function guest_details(Request $request){
        if(!auth()->user()->hasAnyRole(Role::all())){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }
        if($request->ajax()){
            $guest=User::where([
                ['id', $request->guest],
            ])->first();
            if($guest){
                $response = array(
                    'status' => 'success',
                    'name' => $guest->title.' '.$guest->name,
                    'mobile' => $guest->mobile,
                    'organisation' => $guest->organisation,
                    'guestid' => $guest->id,
                );

                return response()->json($response);
            }
        }
    }

    public function edit($id){
        if(!auth()->user()->hasAnyRole(Role::all())){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }
        $guest = User::find($id);
        return view('guests.edit')->with('guest',$guest);
    }

    public function update(Request $request, $id){
        if(!auth()->user()->hasAnyRole(Role::all())){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }
        $guest = User::find($id);
        $guest->name =  $request->input('guest_name');
        $guest->email =  $request->input('guest_email');
        $guest->passport =  $request->input('passport');
        $guest->role =  "guest";
        $guest->country =  $request->input('country');
        $guest->save();

        return redirect('/guests')->with('success', 'Guest Updated');
    }

    public function destroy($id){
        if(!auth()->user()->hasAnyRole(Role::all())){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }
        $guest = User::find($id);
        $orders = Order::where('guest_id', $id)
        ->whereNotIn('payment_status', ['delivered'])
        ->whereNotIn('status', ['delivered'])->get();
        if(count($orders)>0){
            foreach($orders as $key){
                $key->delete();
            }
        }
        $checkins = CheckIn::where('guest_id', $id)
        ->whereNotIn('status', ['Checked Out'])->get();
        if(count($checkins)>0){
            foreach($checkins as $key){
                $key->delete();
            }
        }
        $restuarents = Restuarent::where('guest_id', $id)
        ->whereNotIn('payment_status', ['delivered'])
        ->whereNotIn('status', ['delivered'])->get();
        if(count($restuarents)>0){
            foreach($restuarents as $key){
                $key->delete();
            }
        }
        $guest->delete();
        return redirect('/guests')->with('success', 'Guest Removed');
    }
}
