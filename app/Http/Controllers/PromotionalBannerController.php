<?php

namespace App\Http\Controllers;

use App\Banner;
use App\BannerSection;
use App\Theme;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class PromotionalBannerController extends Controller
{
    public function edit()
    {
        $activeTheme = activeThemeData();

        if (!$activeTheme) {
            return redirect()->back()->with('error', 'No active theme found');
        }

        $configSections = config("banner_section")[$activeTheme->path] ?? [];

        // 1️⃣ Update or Create sections
        foreach ($configSections as $key => $maxItems) {

            $section = BannerSection::updateOrCreate(
                [
                    'theme_id' => $activeTheme->id,
                    'section'  => $key
                ],
                [
                    'max_items' => $maxItems,
                    'status'    => 1
                ]
            );

            // Delete extra banners if max_items reduced
            $banners = $section->banners()->orderBy('order')->get();
            if ($banners->count() > $maxItems) {
                $toDelete = $banners->slice($maxItems); // extra
                foreach ($toDelete as $banner) {
                    if ($banner->image && file_exists(public_path($banner->image))) {
                        unlink(public_path($banner->image));
                    }
                    $banner->delete();
                }
            }
        }

        // 2️⃣ Delete sections that are NOT in config
        $allSections = BannerSection::where('theme_id', $activeTheme->id)->get();

        foreach ($allSections as $section) {
            if (!array_key_exists($section->section, $configSections)) {
                // Delete all banners first
                foreach ($section->banners as $banner) {
                    if ($banner->image && file_exists(public_path($banner->image))) {
                        unlink(public_path($banner->image));
                    }
                    $banner->delete();
                }

                // Then delete section
                $section->delete();
            }
        }

        $sections = BannerSection::where('theme_id', $activeTheme->id)
            ->with('banners')
            ->get();

        return view('backEnd.admin.promo-banner.index', compact('activeTheme', 'sections'));
    }

    public function updateOrCreate(Request $request)
    {
        $activeTheme = Theme::where('is_active', 1)->first();

        if (!$activeTheme) {
            return back()->with('error', 'No active theme found');
        }

        $sections = BannerSection::where('theme_id', $activeTheme->id)->get();

        foreach ($sections as $section) {

            for ($i = 0; $i < $section->max_items; $i++) {
                $banner = Banner::where('banner_section_id', $section->id)
                    ->where('order', $i)
                    ->first();

                // Image Upload
                if (
                    isset($request->banner_image[$section->section][$i]) &&
                    $request->banner_image[$section->section][$i] instanceof \Illuminate\Http\UploadedFile
                ) {

                    // Delete old image if exists
                    if ($banner && $banner->image && file_exists(public_path($banner->image))) {
                        unlink(public_path($banner->image));
                    }

                    $file = $request->banner_image[$section->section][$i];

                    $path = public_path('uploads/banners');
                    if (!file_exists($path)) {
                        mkdir($path, 0777, true);
                    }

                    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move($path, $filename);

                    $image = 'uploads/banners/' . $filename;
                } else {
                    $image = $banner->image ?? null;
                }

                // If no image and no link → skip
                if (!$image && empty($request->banner_link[$section->section][$i])) {
                    continue;
                }

                $data = [
                    'banner_section_id' => $section->id,
                    'image' => $image,
                    'link' => $request->banner_link[$section->section][$i] ?? null,
                    'order' => $i,
                    'status' => 1
                ];

                if ($banner) {
                    $banner->update($data);
                } else {
                    Banner::create($data);
                }
            }
        }

        return back()->with('success', 'Banners saved successfully');
    }
}
