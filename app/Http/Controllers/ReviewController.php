<?php

namespace App\Http\Controllers;

use App\Review;

class ReviewController extends Controller
{
    public function index()
    {
        $data = Review::with('get_product', 'get_review_images')->orderBy('id', 'DESC')->paginate(10);

        return view('backEnd.admin.review.index', compact('data'));
    }

    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return redirect()->route('admin.reviews')->with('success', 'Review deleted successfully.');
    }
}
