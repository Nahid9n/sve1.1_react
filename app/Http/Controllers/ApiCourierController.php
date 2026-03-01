<?php

namespace App\Http\Controllers;

use App\ApiCourier;
use App\CarryBeeCity;
use App\CarryBeeZone;
use App\Services\Courier\PathaoCourier;
use Illuminate\Http\Request;

class ApiCourierController extends Controller
{
    // pathao
    public function pathao()
    {
        $data = ApiCourier::where('courier_name', 'pathao')->first();

        return view('backEnd.admin.api-courier.pathao', compact('data'));
    }

    public function pathaoUpdate(Request $request)
    {
        $pathao = ApiCourier::firstOrNew(['courier_name' => 'pathao']);
        $newConfig = $request->only(['store_id', 'client_id', 'client_secret']);
        $config = $pathao->config ?? [];
        $config = array_merge($config, $newConfig);
        $otherData = $request->only(['base_url', 'username', 'password']);
        $otherData['status'] = $request->has('status') ? 1 : 0;
        ApiCourier::updateOrCreate(
            ['courier_name' => 'pathao'],
            array_merge(['config' => $config], $otherData)
        );

        return back()->with('success', 'Courier updated successfully');
    }

    // steadfast
    public function steadfast()
    {
        $data = ApiCourier::where('courier_name', 'steadfast')->first();

        return view('backEnd.admin.api-courier.steadfast', compact('data'));
    }

    public function steadfastUpdate(Request $request)
    {
        $steadfast = ApiCourier::firstOrNew(['courier_name' => 'steadfast']);
        $newConfig = $request->only(['api_key', 'secret_key']);
        $config = $steadfast->config ?? [];
        $config = array_merge($config, $newConfig);
        $otherData = $request->only(['base_url', 'username', 'password']);
        $otherData['status'] = $request->has('status') ? 1 : 0;
        ApiCourier::updateOrCreate(
            ['courier_name' => 'steadfast'],
            array_merge(['config' => $config], $otherData)
        );

        return back()->with('success', 'Courier updated successfully');
    }

    // redx
    public function redx()
    {
        $data = ApiCourier::where('courier_name', 'redx')->first();

        return view('backEnd.admin.api-courier.redx', compact('data'));
    }

    public function redxUpdate(Request $request)
    {
        $redx = ApiCourier::firstOrNew(['courier_name' => 'redx']);
        $newConfig = $request->only(['api_token']);
        $config = $redx->config ?? [];
        $config = array_merge($config, $newConfig);
        $otherData = $request->only(['base_url', 'username', 'password']);
        $otherData['status'] = $request->has('status') ? 1 : 0;
        ApiCourier::updateOrCreate(
            ['courier_name' => 'redx'],
            array_merge(['config' => $config], $otherData)
        );

        return back()->with('success', 'Courier updated successfully');
    }
    // carrybee
    public function carrybee()
    {

        $data = ApiCourier::where('courier_name', 'carrybee')->first();
        return view('backEnd.admin.api-courier.carrybee', compact('data'));
    }

    public function carrybeeUpdate(Request $request)
    {
        $carrybee = ApiCourier::firstOrNew(['courier_name' => 'carrybee']);
        $newConfig = $request->only(['store_id', 'client_id', 'client_secret', 'client_context']);
        $config = $carrybee->config ?? [];
        $config = array_merge($config, $newConfig);
        $otherData = $request->only(['base_url', 'username', 'password']);
        $otherData['status'] = $request->has('status') ? 1 : 0;
        ApiCourier::updateOrCreate(
            ['courier_name' => 'carrybee'],
            array_merge(['config' => $config], $otherData)
        );

        return back()->with('success', 'Courier updated successfully');
    }

    public function pathaoGenerate(Request $request)
    {
        $pathao = ApiCourier::where('courier_name', 'pathao')->first();
        if ($pathao->status == 1) {
            $courier = new PathaoCourier;
            // dd($courier);
            $access_token = $courier->generateApiKey(['data' => $pathao]);
            // dd($access_token);
            if (isset($access_token['refresh_token']) && isset($access_token['access_token'])) {
                $config = $pathao->config ?? [];
                $config['access_token'] = $access_token['access_token'];
                $config['refresh_token'] = $access_token['refresh_token'];
                $pathao->update(['config' => $config]);

                return back()->with('success', 'New Access Token Generated Successfully');
            } else {
                return back()->with('error', 'Something went wrong');
            }
        } else {
            return back()->with('error', 'Courier is disabled');
        }
    }
}
