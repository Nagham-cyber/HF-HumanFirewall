<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminCategoryController extends Controller
{
    /**
     * قائمة الفئات
     */
    public function index()
    {
        $categories = DB::table('categories')->orderBy('order')->orderBy('name')->get();

        // إحصائيات لكل فئة
        foreach ($categories as $cat) {
            $cat->scenarios_count = DB::table('scenarios')->where('category', $cat->slug)->count();
            $cat->tasks_count = DB::table('cyber_tasks')->where('section', $cat->slug)->count();
        }

        $stats = [
            'total' => count($categories),
            'total_scenarios' => DB::table('scenarios')->count(),
            'total_tasks' => DB::table('cyber_tasks')->count(),
        ];

        return view('admin.categories.index', compact('categories', 'stats'));
    }

    /**
     * صفحة إضافة فئة
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * حفظ فئة جديدة
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'required|string|max:100|unique:categories,slug|regex:/^[a-z0-9_\-]+$/',
            'icon' => 'required|string|max:50',
            'color' => 'required|string|max:50',
            'description' => 'nullable|string',
            'type' => 'required|in:scenario,task,both',
        ]);

        $maxOrder = DB::table('categories')->max('order') ?? 0;

        DB::table('categories')->insert([
            'name' => $request->name,
            'slug' => $request->slug,
            'icon' => $request->icon,
            'color' => $request->color,
            'description' => $request->description,
            'type' => $request->type,
            'order' => $maxOrder + 1,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully!');
    }

    /**
     * صفحة تعديل فئة
     */
    public function edit($id)
    {
        $category = DB::table('categories')->find($id);

        if (!$category) {
            return redirect()->route('admin.categories.index')->with('error', 'Category not found.');
        }

        return view('admin.categories.edit', compact('category'));
    }

    /**
     * تحديث فئة
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'required|string|max:100|unique:categories,slug,' . $id . '|regex:/^[a-z0-9_\-]+$/',
            'icon' => 'required|string|max:50',
            'color' => 'required|string|max:50',
            'description' => 'nullable|string',
            'type' => 'required|in:scenario,task,both',
        ]);

        DB::table('categories')
            ->where('id', $id)
            ->update([
                'name' => $request->name,
                'slug' => $request->slug,
                'icon' => $request->icon,
                'color' => $request->color,
                'description' => $request->description,
                'type' => $request->type,
                'is_active' => $request->has('is_active'),
                'updated_at' => now(),
            ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully!');
    }

    /**
     * حذف فئة
     */
    public function destroy($id)
    {
        $category = DB::table('categories')->find($id);

        if (!$category) {
            return back()->with('error', 'Category not found.');
        }

        $scenariosCount = DB::table('scenarios')->where('category', $category->slug)->count();
        $tasksCount = DB::table('cyber_tasks')->where('section', $category->slug)->count();

        if ($scenariosCount > 0 || $tasksCount > 0) {
            return back()->with('error', "Cannot delete: {$scenariosCount} scenarios and {$tasksCount} tasks use this category.");
        }

        DB::table('categories')->where('id', $id)->delete();

        return back()->with('success', 'Category deleted successfully!');
    }
}