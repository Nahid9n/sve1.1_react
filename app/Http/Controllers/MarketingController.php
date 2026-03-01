<?php

namespace App\Http\Controllers;

use App\ConversionApi;
use Illuminate\Http\Request;

class MarketingController extends Controller
{
    // conversionApi
    public function index()
    {
        $conversion = ConversionApi::get();

        return view('backEnd.admin.conversion-api.index', compact('conversion'));
    }

    public function update(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required|string',
        ]);
        $name = $request->input('name');
        $data = [];
        if ($name === 'facebook') {
            $fb_option = $request->input('fb_option');
            if ($fb_option === 'gtm') {
                $data = [
                    'fb_pixel_script' => $request->input('fb_pixel_script'),
                    'fb_option' => $request->input('fb_option'),
                    'fb_gtm_head' => $request->input('fb_gtm_head'),
                    'fb_gtm_body' => $request->input('fb_gtm_body'),
                ];
            } elseif ($fb_option === 'builtin') {
                $data = [
                    'fb_pixel_script' => $request->input('fb_pixel_script'),
                    'fb_option' => $request->input('fb_option'),
                    'fb_pixel_id' => $request->input('fb_pixel_id'),
                    'fb_access_token' => $request->input('fb_access_token'),
                ];
            }
        } elseif ($name === 'youtube') {
            $data = [
                'youtube_script' => $request->input('youtube_script'),
            ];
        } elseif ($name === 'tiktok') {
            $data = [
                'tiktok_script' => $request->input('tiktok_script'),
            ];
        }
        ConversionApi::updateOrCreate(
            ['name' => $name],
            ['data' => $data]
        );

        return response()->json(['success' => true, 'message' => 'Conversion API updated successfully.']);
    }
}
