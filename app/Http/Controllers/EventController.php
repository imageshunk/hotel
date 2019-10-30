<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Room;
use App\CheckIn;
use App\Event;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class EventController extends Controller
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
        if(!auth()->user()->hasRole('admin')){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }
        return view('room.calendar');
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
        if(request()->ajax()){    
            $event = new Event;
            $event->title = $request->input('title');
            $event->start = $request->input('start');
            $event->end = $request->input('end');
            $event->save();

            $response = array(
                'status' => 'success',
                'title' => $request->input('title'),
                'start' => $request->input('start'),
                'end' => $request->input('end'),
            );

            return response()->json($response);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(!auth()->user()->hasRole('admin')){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }
        $json = array();
        $sqlQuery = Event::all();
        $eventArray = array();
        foreach($sqlQuery as $row) {
            array_push($eventArray, $row);
        }
        echo json_encode($eventArray);
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
        $event = Event::find($id);
        $event->delete();
        $response = array(
            'status' => 'success',
        );

        return response()->json($response);
    }
}
