<?php

namespace App\Http\Controllers;

use App\Theme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ThemeController extends Controller
{
    public function index()
    {
        $data = Theme::paginate(10);

        return view('backEnd.admin.themes.index', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'path' => 'required|string|max:255|unique:themes,path',
        ]);

        // if image is uploaded
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('uploads'), $imageName);
            $url = 'uploads/'.$imageName;
        } else {
            $url = null;
        }

        $theme = Theme::create([
            'name' => $request->name,
            'path' => strtolower($request->path),
            'image' => $url,
        ]);

        // Generate controller, view folder & assets folder
        $this->generateThemeStructure($request->path);

        return back()->with('success', 'Theme created successfully!');
    }

    // Status toggle
    public function status(Request $request, $id)
    {
        $theme = Theme::findOrFail($id);
        $theme->update(['status' => ! $theme->status]);

        return back()->with('success', 'Status Updated Successfully');
    }

    // Delete theme
    public function destroy($id)
    {
        $theme = Theme::findOrFail($id);

        if ($theme->is_active == 1) {
            return back()->with('error', 'Cannot delete active theme.');
        }

        // Delete controller, view, assets
        $this->deleteThemeStructure($theme->path);

        $theme->delete();

        return back()->with('success', 'Theme deleted successfully!');
    }

    // Activate/deactivate theme
    public function activate($id, $status)
    {
        DB::transaction(function () use ($id, $status) {
            $theme = Theme::findOrFail($id);

            if ($status == 1) {
                // Activate this theme
                $theme->update(['is_active' => 1]);

                // Deactivate all others
                Theme::where('id', '!=', $id)->update(['is_active' => 0]);
            } else {
                $theme->update(['is_active' => 0]);
            }
        });

        return back()->with('success', 'Theme activation status updated successfully!');
    }

    protected function generateThemeStructure($path)
    {
        $controllerName = ucfirst(str_replace('-', '', $path)).'Controller';
        $controllerDir = app_path('Http/Controllers/Theme');
        $controllerPath = $controllerDir."/{$controllerName}.php";
        $viewPath = resource_path("views/frontEnd/{$path}");
        $assetPath = public_path("frontEnd/{$path}");

        // Create controller directory if not exists
        if (! file_exists($controllerDir)) {
            mkdir($controllerDir, 0755, true);
        }

        // Create controller
        if (! file_exists($controllerPath)) {
            $stub = <<<PHP
<?php

namespace App\Http\Controllers\Theme;

use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Http\Request;

class {$controllerName} extends Controller
{
    public function index()
    {
        \$products = Product::paginate(20);
        return view('frontEnd.{$path}.index', compact('products'));
    }

    public function product(\$id)
    {
        \$product = Product::findOrFail(\$id);
        return view('frontend.{$path}.product', compact('product'));
    }

    // Order process (demo purpose)
    public function order(Request \$request)
    {
        return response()->json(\$request->all());
    }
}
PHP;
            file_put_contents($controllerPath, $stub);
        }

        // Create view folder and sample blade files
        if (! file_exists($viewPath)) {
            mkdir($viewPath, 0755, true);
            file_put_contents($viewPath.'/index.blade.php', "<h1>{$path} Demo Home</h1>");
            file_put_contents($viewPath.'/product.blade.php', "<h1>{$path} Product Page</h1>");
        }

        // Create public asset folder
        if (! file_exists($assetPath)) {
            mkdir($assetPath, 0755, true);
        }
    }

    protected function deleteThemeStructure($path)
    {
        $controllerName = ucfirst(str_replace('-', '', $path)).'Controller';
        $controllerPath = app_path("Http/Controllers/Theme/{$controllerName}.php");
        $viewPath = resource_path("views/frontEnd/{$path}");
        $assetPath = public_path("frontEnd/{$path}");

        // Delete controller
        if (file_exists($controllerPath)) {
            unlink($controllerPath);
        }

        // Delete view folder
        if (file_exists($viewPath)) {
            $this->deleteFolder($viewPath);
        }

        // Delete asset folder
        if (file_exists($assetPath)) {
            $this->deleteFolder($assetPath);
        }
    }

    /**
     * Recursive delete folder
     */
    protected function deleteFolder($dir)
    {
        if (! is_dir($dir)) {
            return;
        }

        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = "$dir/$file";
            if (is_dir($path)) {
                $this->deleteFolder($path);
            } else {
                unlink($path);
            }
        }
        rmdir($dir);
    }
}
