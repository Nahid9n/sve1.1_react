<?php

namespace App\Http\Controllers;

use App\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $data = Supplier::orderBy('id', 'desc')->paginate(25);

        return view('backEnd.admin.supplier.index', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
        ]);
        Supplier::create($request->all());

        return back()->with('success', 'Supplier Created Successfully');
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
        ]);
        Supplier::find($request->id)->update($request->all());

        return back()->with('success', 'Supplier Updated Successfully');
    }

    public function delete($id)
    {
        Supplier::find($id)->delete();

        return back()->with('success', 'Supplier Deleted Successfully');
    }

    public function status(Request $request)
    {
        // dd($request->all());
        $data = Supplier::find($request->id);
        $data->update([
            'status' => ! $data->status,
        ]);

        return back()->with('success', 'Status Updated Successfully');
    }
}
