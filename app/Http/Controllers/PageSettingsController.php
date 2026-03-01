<?php

namespace App\Http\Controllers;

use App\PageSetting;
use Illuminate\Http\Request;

class PageSettingsController extends Controller
{
    public function index()
    {
        $data = PageSetting::find(1);

        return view('backEnd.admin.page_settings', compact('data'));
    }

    public function update(Request $request)
    {
        //         dd($request->all());
        try {
            $page_setting = PageSetting::first();
            if ($page_setting) {
                $page_setting->update($request->all());
            } else {
                PageSetting::create($request->all());
            }

            return redirect()->back()->with('success', 'Page Settings Updated Successfully');
        } catch (\Exception $e) {
            // dd($e);
            return redirect()->back()->with('error', $e);
        }
    }
}
