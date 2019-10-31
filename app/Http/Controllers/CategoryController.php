<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Setting;

class CategoryController extends Controller
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
        $categories = Category::latest()->paginate(10);
        return view('category.index')->with('categories', $categories);
    }

    public function create(){
        if(auth()->user()->role != 'admin'){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }
        return view('category.create');
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
            'category' => 'required|string'
        ]);

        $category = new Category;
        $category->category = $request->input('category');
        $category->save();

        return redirect()->back()->with('success', 'Category Added');
    }
}
