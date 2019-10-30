<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CheckIn;
use App\Room;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoomServiceController extends Controller
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
        if(!auth()->user()->hasAnyRole(Role::all())){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }
        $rooms = CheckIn::where('status', 'Room Service')->orderBy('created_at', 'desc')->paginate(20);
        return view('room.service')->with('rooms', $rooms);
    }

    public function show($id){
        if(!auth()->user()->hasAnyRole(Role::all())){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }
        $room = Room::find($id);
        $checkin = CheckIn::where([
            ['room_id', $id],
            ['status', 'Room Service']
        ])->first();

        return view('room.show-room-service')->with(compact('room', 'checkin'));
    }
}
