<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    // Hiển thị danh sách danh mục
    public function index(Request $request)
    {
        $query = Category::query();

        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $categories = $query
            ->withCount('products')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('manager.categories.index', compact('categories'));
    }

    // Hiển thị form thêm danh mục
    public function create()
    {
        return view('manager.categories.create');
    }

    // Lưu danh mục mới
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150', 'unique:categories,name'],
            'description' => ['nullable', 'string'],
        ], [
            'name.required' => 'Vui lòng nhập tên danh mục.',
            'name.unique' => 'Tên danh mục đã tồn tại.',
            'name.max' => 'Tên danh mục không được vượt quá 150 ký tự.',
        ]);

        $slug = Str::slug($validated['name']);
        $originalSlug = $slug;
        $count = 1;

        while (Category::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        Category::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
            'status' => 'active',
        ]);

        return redirect()
            ->route('manager.categories.index')
            ->with('success', 'Thêm danh mục thành công.');
    }

    // Hiển thị form chỉnh sửa danh mục
    public function edit(Category $category)
    {
        return view('manager.categories.edit', compact('category'));
    }

    // Cập nhật danh mục
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:150',
                Rule::unique('categories', 'name')->ignore($category->id),
            ],
            'description' => ['nullable', 'string'],
        ], [
            'name.required' => 'Vui lòng nhập tên danh mục.',
            'name.unique' => 'Tên danh mục đã tồn tại.',
            'name.max' => 'Tên danh mục không được vượt quá 150 ký tự.',
        ]);

        if ($category->name !== $validated['name']) {
            $slug = Str::slug($validated['name']);
            $originalSlug = $slug;
            $count = 1;

            while (
                Category::where('slug', $slug)
                    ->where('id', '!=', $category->id)
                    ->exists()
            ) {
                $slug = $originalSlug . '-' . $count;
                $count++;
            }
        } else {
            $slug = $category->slug;
        }

        $category->update([
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()
            ->route('manager.categories.index')
            ->with('success', 'Cập nhật danh mục thành công.');
    }

    // Ẩn hoặc hiện danh mục
    public function toggleStatus(Category $category)
    {
        if ($category->status === 'active') {
            $category->status = 'inactive';
        } else {
            $category->status = 'active';
        }

        $category->save();

        return redirect()
            ->route('manager.categories.index')
            ->with('success', 'Cập nhật trạng thái danh mục thành công.');
    }
}