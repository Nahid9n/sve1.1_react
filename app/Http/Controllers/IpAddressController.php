<?php

namespace App\Http\Controllers;

use App\IpAddress;
use Illuminate\Http\Request;

class IpAddressController extends Controller
{
    // index
    public function index(Request $request)
    {
        // dd($request->all());
        $data = IpAddress::query();
        if ($request->input('query')) {
            $data = $data->where('ip_address', 'like', '%'.$request->input('query').'%');
        }
        $data = $data->orderBy('id', 'desc')->paginate(10);

        return view('backEnd.admin.ip-address.index', compact('data'));
    }

    public function delete($id)
    {
        IpAddress::find($id)->delete();

        return back()->with('success', 'IP Address delete Successfully');
    }

    public function status($id)
    {
        $data = IpAddress::find($id);
        $data->status = $data->status == 1 ? 0 : 1;
        $data->save();

        return back()->with('success', 'Status Change Successfully');
    }
}
