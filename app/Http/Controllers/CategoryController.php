<?php

namespace App\Http\Controllers;

use App\Category;
use App\Theme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class CategoryController extends Controller
{
    // Display top-level categories
    public function index()
    {
        // dd(activeThemeData());
        $data = Category::whereNull('parent_id')->with('childrenRecursive')->where('theme_id', activeThemeData()->id)->get();

        return view('backEnd.admin.categories.index', compact('data'));
    }

    // Store category or subcategory
    public function store(Request $request)
    {
        try {
            $request->validate([
                'category_name' => 'required|string|max:255',
                'status' => 'required|boolean',
                'file_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ]);

            $category = new Category;
            $category->category_name = $request->category_name;
            $category->status = $request->status;
            $category->parent_id = $request->parent_id ?? null;
            $category->theme_id = activeThemeData()->id;
            $category->slug = $request->slug;
            $category->is_show_home = $request->is_show_home;
            $category->extra_fields = $request->extra_fields ?? [];

            if ($request->hasFile('file_url')) {
                $file = $request->file('file_url');

                $destinationPath = 'uploads/category';
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                $file_name = uniqid().'.webp';
                $fullPath = $destinationPath . '/' . $file_name;

                $img = Image::make($request->file('file_url')->getRealPath());
                $img->encode('webp', 80)->save($fullPath);
                $category->file_url = $fullPath;
            }

            $category->save();

            return redirect()->back()->with('success', 'Category added successfully!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // Update category
    public function update(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|exists:categories,id',
                'category_name' => 'required|string|max:255',
                'status' => 'required|boolean',
                'file_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ]);

            $category = Category::findOrFail($request->id);

            // ---------- FIX EXTRA FIELDS ----------
            $oldExtra = $category->extra_fields ?? [];
            $newExtra = $request->extra_fields ?? [];

            foreach ($oldExtra as $key => $val) {
                if (isset($newExtra[$key])) {
                    $oldExtra[$key] = $newExtra[$key];
                }
            }
            $category->extra_fields = $oldExtra;

            $category->category_name = $request->category_name;
            $category->status = $request->status;
            $category->theme_id = activeThemeData()->id;
            $category->slug = $request->slug;
            $category->parent_id = $request->parent_id ?? null;
            $category->is_show_home = $request->is_show_home;

            if ($request->hasFile('file_url')) {
                if ($category->file_url && File::exists(public_path($category->file_url))) {
                    File::delete(public_path($category->file_url));
                }
                $destinationPath = 'uploads/category';
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                $file_name = uniqid().'.webp';
                $fullPath = $destinationPath . '/' . $file_name;

                $img = Image::make($request->file('file_url')->getRealPath());
                $img->encode('webp', 80)->save($fullPath);
                $category->file_url = $fullPath;

            }

            $category->save();

            return redirect()->back()->with('success', 'Category updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // Delete category with children
    public function delete($id)
    {
        $category = Category::findOrFail($id);

        if ($category->file_url && File::exists(public_path($category->file_url))) {
            File::delete(public_path($category->file_url));
        }

        $this->deleteChildren($category);
        $category->delete();

        return redirect()->back()->with('success', 'Category deleted successfully!');
    }

    // Recursive delete helper
    protected function deleteChildren($category)
    {
        foreach ($category->children as $child) {
            if ($child->file_url && File::exists(public_path($child->file_url))) {
                File::delete(public_path($child->file_url));
            }
            $this->deleteChildren($child);
            $child->delete();
        }
    }

    public function slugCheck(Request $request)
    {
        $id = $request->id ?? null;

        $exists = Category::where('slug', $request->slug)
            ->where('theme_id', activeThemeData()->id)
            ->when($id, function ($q) use ($id) {
                $q->where('id', '!=', $id);
            })
            ->exists();

        return response()->json(['exists' => $exists]);
    }

    public function slugUpdate(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'slug' => 'required|unique:categories,slug,' . $request->id,
        ]);

        $cat = Category::find($request->id);

        if (! $cat) {
            return response()->json(['status' => false, 'msg' => 'Category not found']);
        }

        $cat->slug = $request->slug;
        $cat->save();

        return response()->json(['status' => true, 'msg' => 'Slug updated']);
    }

    public function updatePosition(Request $request)
    {
        // dd($request->all());
        $category = Category::findOrFail($request->id);
        $category->update(['position' => $request->position]);

        return response()->json(['success' => true]);
    }
}
