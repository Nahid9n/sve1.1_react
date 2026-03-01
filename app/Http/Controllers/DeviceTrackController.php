<?php

namespace App\Http\Controllers;

use App\DeviceTrack;

class DeviceTrackController extends Controller
{
    public function index()
    {
        $data = DeviceTrack::with('user')->orderBy('id', 'desc')->paginate(20);

        // dd($data);
        return view('backEnd.admin.device.index', compact('data'));
    }

    public function status($id, $status)
    {
        DeviceTrack::findOrFail($id)->update(['status' => $status]);

        return back()->with('success', 'Customer status updated successfully.');
    }
}
