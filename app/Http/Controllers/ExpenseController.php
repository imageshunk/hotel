<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Expense;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Setting;

class ExpenseController extends Controller
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
        $expenses = Expense::latest()->paginate(20);
        return view('expense.index')->with('expenses', $expenses);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!auth()->user()->hasAnyRole(Role::all())){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }
        return view('expense.create');
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
        $this->validate($request, [
            'date' => 'required',
            'receipt' => 'nullable',
            'particular' => 'required',
            'category_id' => 'required',
            'payment_type_id' => 'required',
            'amount' => 'required',
            'added_by_id' => 'required',
        ]);

        $expense = new Expense;
        $expense->date = $request->input('date');
        $expense->particular = $request->input('particular');
        $expense->receipt = $request->input('receipt');
        $expense->category_id = $request->input('category_id');
        $expense->payment_type_id = $request->input('payment_type_id');
        $expense->amount = $request->input('amount');
        $expense->added_by_id = $request->input('added_by_id');
        $expense->save();

        return redirect('/expenses')->with('success', 'Expense Added');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!auth()->user()->hasAnyRole(Role::all())){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }
        $expense = Expense::find($id);
        return view('expense.edit')->with('expense', $expense);
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
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }
        $this->validate($request, [
            'date' => 'required',
            'receipt' => 'nullable',
            'particular' => 'required',
            'category_id' => 'required',
            'payment_type_id' => 'required',
            'amount' => 'required',
            'added_by_id' => 'required',
        ]);

        $expense = Expense::find($id);
        $expense->date = $request->input('date');
        $expense->receipt = $request->input('receipt');
        $expense->particular = $request->input('particular');
        $expense->category_id = $request->input('category_id');
        $expense->payment_type_id = $request->input('payment_type_id');
        $expense->amount = $request->input('amount');
        $expense->added_by_id = $request->input('added_by_id');
        $expense->save();

        return redirect('/expenses')->with('success', 'Expense Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!auth()->user()->hasAnyRole(Role::all())){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }
        $expense = Expense::find($id);
        $expense->delete();

        return redirect('/expenses')->with('error', 'Expense Removed');
    }

    public function search(Request $request)
    {
        if(!auth()->user()->hasAnyRole(Role::all())){
            return redirect()->back()->with('error', 'Sorry, You are not authorized');
        }
        $input = $request->input('search');
        $query = Expense::query();
        $columns = ['particular', 'amount', 'date'];
        foreach($columns as $column){
            $query->orWhere($column, 'LIKE', '%' . $input . '%');
        }
        $expenses = $query->paginate(20);
        return view('expense.index')->with('expenses', $expenses);
    }
}
