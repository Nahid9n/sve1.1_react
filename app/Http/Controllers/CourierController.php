<?php

namespace App\Http\Controllers;

use App\Courier;
use App\CourierCity;
use App\CourierZone;
use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourierController extends Controller
{
    public function index()
    {
        $data = Courier::all();

        return view('backEnd.admin.couriers.index', compact('data'));
    }

    public function store(Request $request)
    {

        Courier::create($request->all());
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.courier')->with('success', 'Courier Added Successfully');
        } elseif (Auth::guard('manager')->check()) {
            return redirect()->route('manager.courier')->with('success', 'Courier Added Successfully');
        } else {
            return back()->with('warning', 'Something Went Wrong');
        }
    }

    public function update(Request $request)
    {
        if ($request->is_city && $request->is_city == 'on') {
            $is_city = 1;
        } else {
            $is_city = 0;
        }

        if ($request->is_zone && $request->is_zone == 'on') {
            $is_zone = 1;
        } else {
            $is_zone = 0;
        }

        $input = array_merge($request->all(), [
            'is_city' => $is_city,
            'is_zone' => $is_zone,
        ]);
        Courier::find($request->id)->update($input);
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.courier')->with('success', 'Courier Updated Successfully');
        } elseif (Auth::guard('manager')->check()) {
            return redirect()->route('manager.courier')->with('success', 'Courier Updated Successfully');
        } else {
            return back()->with('warning', 'Something Went Wrong');
        }
    }

    public function status(Request $request, $id)
    {

        $status = Courier::findOrFail($id);
        $status->update(['status' => ! $status->status]);

        return back()->with('success', 'Courier Status Updated Successfully');
    }

    public function delete($id)
    {
        $has_courier = Order::where('courier_id', $id)->first();
        if ($has_courier) {
            return back()->with('warning', 'This Product Already In Order');
        } else {
            CourierCity::where('courier_id', $id)->delete();
            CourierZone::where('courier_id', $id)->delete();
            Courier::find($id)->delete();

            return back()->with('success', 'Courier Deleted Successfully');
        }
    }

    // //for city
    public function cityIndex()
    {
        $data = CourierCity::all();
        $couriers = Courier::pluck('courier_name', 'id');

        return view('backEnd.admin.couriers.cities.index', compact('data', 'couriers'));
    }

    public function cityStore(Request $request)
    {
        $courier_name = Courier::find($request->courier_id)->courier_name;
        $input = array_merge($request->all(), [
            'courier_name' => $courier_name,
        ]);
        CourierCity::create($input);
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.courier.city')->with('success', 'Courier City Added Successfully');
        } elseif (Auth::guard('manager')->check()) {
            return redirect()->route('manager.courier.city')->with('success', 'Courier City Added Successfully');
        } else {
            return back()->with('warning', 'Something Went Wrong');
        }
    }

    public function cityUpdate(Request $request)
    {
        $courier_name = Courier::find($request->courier_id)->courier_name;
        $input = array_merge($request->all(), [
            'courier_name' => $courier_name,
        ]);

        CourierCity::find($request->id)->update($input);
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.courier.city')->with('success', 'Courier City Updated Successfully');
        } elseif (Auth::guard('manager')->check()) {
            return redirect()->route('manager.courier.city')->with('success', 'Courier City Updated Successfully');
        } else {
            return back()->with('warning', 'Something Went Wrong');
        }
    }

    public function cityStatus(Request $request, $id)
    {

        $status = CourierCity::findOrFail($id);
        $status->update(['status' => ! $status->status]);

        return back()->with('success', 'Courier City Status Updated Successfully');
    }

    public function cityDelete($id)
    {
        $has_courier_city = CourierZone::where('city_id', $id)->first();
        if ($has_courier_city) {
            return back()->with('warning', 'This City Already In Zone');
        } else {
            CourierZone::where('city_id', $id)->delete();
            CourierCity::find($id)->delete();

            return back()->with('success', 'Courier City Deleted Successfully');
        }
    }

    // //for zone
    public function zoneIndex()
    {
        $data = CourierZone::all();
        $couriers = Courier::pluck('courier_name', 'id');
        $courier_cities = CourierCity::pluck('city_name', 'id');

        return view('backEnd.admin.couriers.zones.index', compact('data', 'couriers', 'courier_cities'));
    }

    public function zoneStore(Request $request)
    {
        $courier_name = Courier::find($request->courier_id)->courier_name;
        $city_name = CourierCity::find($request->city_id)->city_name;
        $input = array_merge($request->all(), [
            'courier_name' => $courier_name,
            'city_name' => $city_name,
        ]);
        CourierZone::create($input);
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.courier.zone')->with('success', 'Courier Zone Added Successfully');
        } elseif (Auth::guard('manager')->check()) {
            return redirect()->route('manager.courier.zone')->with('success', 'Courier Zone Added Successfully');
        } else {
            return back()->with('warning', 'Something Went Wrong');
        }
    }

    public function zoneUpdate(Request $request)
    {
        $courier_name = Courier::find($request->courier_id)->courier_name;
        $city_name = CourierCity::find($request->city_id)->city_name;
        $input = array_merge($request->all(), [
            'courier_name' => $courier_name,
            'city_name' => $city_name,
        ]);

        CourierZone::find($request->id)->update($input);
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.courier.zone')->with('success', 'Courier Zone Updated Successfully');
        } elseif (Auth::guard('manager')->check()) {
            return redirect()->route('manager.courier.zone')->with('success', 'Courier Zone Updated Successfully');
        } else {
            return back()->with('warning', 'Something Went Wrong');
        }
    }

    public function zoneStatus(Request $request, $id)
    {

        $status = CourierZone::findOrFail($id);
        $status->update(['status' => ! $status->status]);

        return back()->with('success', 'Courier Zone Status Updated Successfully');
    }

    public function zoneDelete($id)
    {
        CourierZone::find($id)->delete();

        return back()->with('success', 'Courier Zone Deleted Successfully');
    }

    public function ajaxGetCities(Request $request)
    {
        $data = CourierCity::where('courier_id', $request->id)->pluck('city_name', 'id');

        return response()->json($data);
    }

    public function ajaxGetZones(Request $request)
    {
        $data = CourierZone::where('city_id', $request->id)->pluck('zone_name', 'id');

        return response()->json($data);
    }

    public function ajaxGetCCharge(Request $request)
    {
        $data = Courier::find($request->id)->courier_charge;

        return response()->json($data);
    }
}
