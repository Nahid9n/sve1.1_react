<?php

namespace App\Http\Controllers;

use App\HomePageSetting;
use Illuminate\Http\Request;

class HomePageSettingController extends Controller
{
    //index
    public function index()
    {
        $data = HomePageSetting::first();
        return view('backEnd.admin.home-page-setting.index');
    }
    public function update(Request $request)
    {
//         dd($request->all());
        $request->validate([
            'name' => 'required|string',
        ]);
        $name = $request->input('name');
        $data = [];
        if ($name === 'top-header') {
            $data = [
                'free_shipping' => $request->input('free_shipping'),
            ];
        } elseif ($name === 'hero') {
            $data = [
                'call_support_text' => $request->input('call_support_text'),
                'marquee_text' => $request->input('marquee_text'),
            ];
        } elseif ($name === 'footer') {
            if ($request->hasFile('payment_url')) {
                $file = $request->file('payment_url');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('payment'), $fileName);
                $data['payment_url'] = 'payment/' . $fileName;
            }
            else{
                $data['payment_url'] = $request->old_payment_url;
            }
            $data = [
                'newsletter_text' => $request->input('newsletter_text'),
                'payment_url' => $data['payment_url']
            ];
            // dd($data);
        }


        HomePageSetting::updateOrCreate(
            [
                'section' => $name,
                'theme_id' => activeTheme()->id
            ],
            [
                'content' => $data,
            ]
        );

        return response()->json(['success' => true, 'message' => 'Updated Successfully']);
    }
}
