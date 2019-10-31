<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Setting;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SettingsController extends Controller
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
    
    public function index()
    {
        if(!auth()->user()->hasRole('admin')){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }
        $settings = Setting::first();
        return view('settings.index')->with('settings', $settings);
    }

    public function create(Request $request){
        if(!auth()->user()->hasRole('admin')){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }
        if($request->hasFile('logo')){
            // Get filename with the extension
            $filenameWithExt = $request->file('logo')->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just ext
            $extension = $request->file('logo')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore= 'logo.'.$extension;
            // Upload Image
            $path = $request->file('logo')->storeAs('public/images', $fileNameToStore);
        } else {
            $fileNameToStore = 'noimage.png';
        }

        $setting = new Setting;
        $setting->hotel_name = $request->input('hotel_name');
        $setting->description = $request->input('description');
        $setting->address = $request->input('address');
        $setting->contact = $request->input('contact');
        $setting->currency = $request->input('currency');
        $setting->bank_name = $request->input('bank_name');
        $setting->account_name = $request->input('account_name');
        $setting->account_no = $request->input('account_no');
        $setting->branch_name = $request->input('branch_name');
        $setting->code = $request->input('code');
        $setting->website = $request->input('website');
        $setting->logo = $fileNameToStore;
        $setting->save();

        return redirect('/home')->with('success', 'Settings Saved');
    }

    public function edit($id){
        if(!auth()->user()->hasRole('admin')){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }
        $setting = Setting::find($id);
        return view('settings.edit')->with('setting', $setting);
    }

    public function update(Request $request, $id){
        if(!auth()->user()->hasRole('admin')){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }
        if($request->hasFile('logo')){
            // Get filename with the extension
            $filenameWithExt = $request->file('logo')->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just ext
            $extension = $request->file('logo')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore= 'logo.'.$extension;
            // Upload Image
            $path = $request->file('logo')->storeAs('public/images', $fileNameToStore);
        } else {
            $fileNameToStore = 'noimage.jpg';
        }

        $setting = Setting::find($id);
        $setting->hotel_name = $request->input('hotel_name');
        $setting->description = $request->input('description');
        $setting->address = $request->input('address');
        $setting->contact = $request->input('contact');
        $setting->currency = $request->input('currency');
        if($request->hasFile('logo')){
            $setting->logo = $fileNameToStore;
        }
        $setting->save();

        return redirect('/home')->with('success', 'Settings Saved');
    }
}
