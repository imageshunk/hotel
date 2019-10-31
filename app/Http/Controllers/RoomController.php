<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Room;
use App\CheckIn;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Setting;

class RoomController extends Controller
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
        $rooms = Room::orderBy('room_number', 'asc')->paginate(30);
        return view('room.availability')->with('rooms', $rooms);
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
            'room_number' => 'required|string|max:10|unique:rooms',
            'status' => 'required|string',
        ]);

        $room = new Room;
        $room->room_number = $request->input('room_number');
        $room->room_status = $request->input('status');
        $room->save();

        $rooms = Room::orderBy('room_number', 'asc')->paginate(30);
        return redirect('/availability')->with('rooms', $rooms);
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
            'room_status' => 'required',
        ]);

        $room = Room::find($id);
        $room->room_status = $request->input('room_status');
        $room->save();

        return redirect('/availability')->with('success', 'Room Status Changed');
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
        $room = Room::find($id);
        $checkins = CheckIn::where('room_id', $room->id)->count();
        if($checkins > 0){
            return redirect()->back()->with('error', 'This room is associated with a guest');
        }
        $room->delete();

        return redirect()->back()->with('error', 'This room has been deleted');
    }
}
