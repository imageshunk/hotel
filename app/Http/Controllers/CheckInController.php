<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CheckIn;
use App\Package;
use App\Room;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Setting;

class CheckInController extends Controller
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
            return redirect()->back()->with('error', 'You are not authorized');
        }
        $bookings = CheckIn::latest()->paginate(20);
        return view('room.booking')->with('bookings', $bookings);
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
            return redirect()->back()->with('error', 'You are not authorized');
        }
        $this->validate($request, [
            'room_id' => 'required',
            'check_in_date' => 'required',
            'check_in_time' => 'required',
            'check_out_date' => 'required',
            'guest_name' => 'required',
            'guest_id' => 'required',
            'agent_id' => 'nullable',
            'adults' => 'required',
            'childrens' => 'nullable',
            'comments' => 'nullable',
            'package' => 'nullable',
            'rate' => 'required',
            'utility' => 'nullable',
            'guest_mobile' => 'nullable',
            'guest_organisation' => 'nullable',
            'payment_method' => 'nullable'
        ]);

        $checkins = CheckIn::where('guest_id', $request->input('guest_id'))->get();
        if(count($checkins) > 0){
            foreach($checkins as $checkin){
                $checkin->previous_checkin = 1;
                $checkin->save();
            }
        }

        $checkin = new CheckIn;
        $checkin->room_id = $request->input('room_id');
        $checkin->check_in_date = $request->input('check_in_date');
        $checkin->check_in_time = $request->input('check_in_time');
        $checkin->check_out_date = $request->input('check_out_date');
        $checkin->guest_name = $request->input('guest_name');
        $checkin->guest_id = $request->input('guest_id');
        $checkin->agent_id = $request->input('agent_id');
        $checkin->adults = $request->input('adults');
        $checkin->childrens = $request->input('childrens');
        $checkin->comments = $request->input('comments');
        $checkin->package = $request->input('package');
        $checkin->mobile = $request->input('guest_mobile');
        $checkin->organisation = $request->input('guest_organisation');
        $checkin->payment_method = $request->input('payment_method');
        if($request->get('utility')!=null){
            $utilities = implode(",", $request->get('utility'));
            $checkin->utilities = $utilities;
        }
        $checkin->previous_checkin = 0;
        $checkin->status = "Room Service";

        $start = \Carbon\Carbon::parse($request->input('check_in_date'));
        $end = \Carbon\Carbon::parse($request->input('check_out_date'));
        $nights = $end->diffInDays($start);
        if($nights == 0){
            $nights = 1;
            $checkin->nights = 1;
        }else{
            $checkin->nights = $nights;
        }
        $checkin->package =$request->input('package');
        $checkin->total = $nights * $request->input('rate');
        $checkin->save();

        $room = Room::find($request->input('room_id'));
        $room->room_status = 'booked';
        $room->save();
        
        return redirect('/availability')->with('success', 'Room Booked');
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
            return redirect()->back()->with('error', 'You are not authorized');
        }
        $room = Room::find($id);
        return view('room.check-in')->with('room', $room);
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
            return redirect()->back()->with('error', 'You are not authorized');
        }
        $this->validate($request, [
            'room_id' => 'required'
        ]);

        $checkin = CheckIn::find($id);
        $previous_room = $checkin->room_id;
        $checkin->room_id = $request->input('room_id');
        $checkin->save();

        $room = Room::find($request->input('room_id'));
        $room->room_status = 'booked';
        $room->save();

        $room = Room::find($previous_room);
        $room->room_status = 'ready';
        $room->save();

        return redirect('/availability')->with('success', 'Guest Transfered');
    }

    public function checkOut($id){
        if(!auth()->user()->hasAnyRole(Role::all())){
            return redirect()->back()->with('error', 'You are not authorized');
        }
        $checkin = CheckIn::find($id);

        $check_other_checkin = CheckIn::where([
            ['room_id', $checkin->room_id],
            ['status', 'Room Service']
        ])
        ->whereNotIn('guest_id', [$checkin->guest_id])
        ->get();

        $check_other_checkin2 = CheckIn::where([
            ['room_id', $checkin->room_id],
            ['status', 'Checked Out']
        ])
        ->whereNotIn('guest_id', [$checkin->guest_id])
        ->get();

        if(count($check_other_checkin)>0){
            $checkin->status = "Checked Out";
            $checkin->save();
        }elseif(count($check_other_checkin2)>0){
            $checkin->status = "Checked Out";
            $room = Room::find($checkin->room_id);
            $room->room_status = "dirty";
            $room->save();
            $checkin->save();
        }else{
            $checkin->status = "Checked Out";
            $room = Room::find($checkin->room_id);
            $room->room_status = "dirty";
            $room->save();
            $checkin->save();
        }

        return redirect()->back()->with('success', 'Guest has been checked out');
    }

    public function checkAvailability(Request $request){
        if(!auth()->user()->hasAnyRole(Role::all())){
            return redirect()->back()->with('error', 'You are not authorized');
        }
        if(request()->ajax()){
            $checkin = CheckIn::where([
                ['room_id', $request->input('room')],
                ['status', 'Room Service'],
            ])->first();

            if($checkin){
                $checkindate = \Carbon\Carbon::parse($checkin->check_in_date)->format('Y-m-d');
                $datepicker = \Carbon\Carbon::parse($request->input('datepicker'))->format('Y-m-d');
                $checkoutdate = \Carbon\Carbon::parse($checkin->check_out_date)->format('Y-m-d');
                $date = \Carbon\Carbon::parse($request->input('date'))->format('Y-m-d');
                $check = CheckIn::where('id', $checkin->id)
                ->whereBetween('check_in_date', [$datepicker, $date])
                ->first();

                if($check){
                    $response = array(
                        'status' => false,
                        'class'  => 'text-red',
                        'string' => 'Not Available',
                    );
                }else{
                    $response = array(
                        'status' => true,
                        'class'  => 'text-green',
                        'string' => 'Available',
                    );
                }
            }else{
                $response = array(
                    'status' => true,
                    'class'  => 'text-green',
                    'string' => 'Available',
                );
            }

            return response()->json($response);
        }
    }
}
