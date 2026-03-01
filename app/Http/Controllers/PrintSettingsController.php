<?php

namespace App\Http\Controllers;

use App\PageSetting;
use App\PrintSetting;
use Illuminate\Http\Request;

class PrintSettingsController extends Controller
{
    public function index()
    {
        $data = PrintSetting::first();

        if (!$data) {
            PrintSetting::create([
                'single_print' => 0,
                'bulk_print' => 0,
            ]);
        }

        return view('backEnd.admin.print.index',compact('data'));
    }

    public function update(Request $request)
    {
//        try {
            $input = $request->all();
            $setting = PrintSetting::first();

            if ($setting) {
                $setting->update($input);
            } else {
                PrintSetting::create($input);
            }

            return redirect()->back()->with('success', 'Print Settings Updated Successfully');
//
//        } catch (\Exception $e) {
//            return redirect()->back()->with('error', $e);
//        }
    }

}
