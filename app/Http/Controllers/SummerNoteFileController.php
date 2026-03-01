<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SummerNoteFileController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ],[
            'image.max' => 'The image may not be greater than 2048 kilobytes',
            'image.mimes' => 'The image may jpg,jpeg,png,webp allowed',
            'image.image' => 'The image may jpg,jpeg,png,webp allowed',
        ]);
        $file = $request->file('image');

        $path = uploadSummerNoteFile($file, 800, 600, 'summernote');

        return response()->json([
            'url' => asset($path),
        ]);
    }

    public function delete(Request $request)
    {
        $src = $request->src;
        // Example: http://localhost/sve1.1/public/uploads/summernote/699196760f989_800x600.jpg

        // 1️⃣ URL থেকে relative path বের করা base URL অনুযায়ী
        $baseUrl = url('/'); // http://localhost/sve1.1/public
        $relativePath = str_replace($baseUrl . '/', '', $src);
        // relativePath = uploads/summernote/699196760f989_800x600.jpg

        // 2️⃣ Absolute filesystem path
        $fullPath = public_path($relativePath);
        // F:/xampp/htdocs/sve1.1/public/uploads/summernote/699196760f989_800x600.jpg

        // 3️⃣ Delete
        if (file_exists($fullPath)) {
            unlink($fullPath);
            return response()->json(['status' => 'deleted']);
        }

        return response()->json([
            'status' => 'file_not_found',
            'path' => $fullPath
        ], 404);
    }

}
