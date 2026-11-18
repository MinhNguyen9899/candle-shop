<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    // Hiển thị danh sách sản phẩm
    public function index(Request $request)
    {
        $query = Product::with(['category', 'inventory']);

        if ($request->filled('keyword')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->keyword . '%')
                    ->orWhere('sku', 'like', '%' . $request->keyword . '%');
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->status === 'active') {
            $query->where('status', 'active');
        }

        if ($request->status === 'discontinued') {
            $query->whereIn('status', ['discontinued', 'inactive']);
        }

        $products = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('employee.products.index', compact(
            'products',
            'categories'
        ));
    }

    // Hiển thị form thêm sản phẩm
    public function create()
    {
        $categories = Category::where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('employee.products.create', compact('categories'));
    }

    // Lưu sản phẩm mới
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:200', 'unique:products,name'],
            'sku' => ['required', 'string', 'max:100', 'unique:products,sku'],
            'price' => ['required', 'numeric', 'min:0'],
            'cost_price' => ['required', 'numeric', 'min:0'],
            'low_stock_threshold' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ], [
            'category_id.required' => 'Vui lòng chọn danh mục.',
            'category_id.exists' => 'Danh mục không tồn tại.',
            'name.required' => 'Vui lòng nhập tên sản phẩm.',
            'name.unique' => 'Tên sản phẩm đã tồn tại.',
            'name.max' => 'Tên sản phẩm không được vượt quá 200 ký tự.',
            'sku.required' => 'Vui lòng nhập SKU.',
            'sku.unique' => 'SKU đã tồn tại. Vui lòng sử dụng SKU khác.',
            'price.required' => 'Vui lòng nhập giá bán.',
            'price.numeric' => 'Giá bán phải là số.',
            'cost_price.required' => 'Vui lòng nhập giá vốn.',
            'cost_price.numeric' => 'Giá vốn phải là số.',
            'low_stock_threshold.required' => 'Vui lòng nhập ngưỡng cảnh báo tồn kho.',
            'low_stock_threshold.integer' => 'Ngưỡng cảnh báo phải là số nguyên.',
            'low_stock_threshold.min' => 'Ngưỡng cảnh báo không được nhỏ hơn 0.',
            'image.image' => 'File tải lên phải là hình ảnh.',
            'image.mimes' => 'Ảnh phải có định dạng JPG, JPEG, PNG hoặc WEBP.',
            'image.max' => 'Dung lượng ảnh không được vượt quá 5MB.',
        ]);

        $slug = Str::slug($validated['name']);
        $baseSlug = $slug;
        $count = 1;

        while (Product::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $count++;
        }

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        try {
            DB::transaction(function () use ($validated, $slug, $imagePath) {
                $product = Product::create([
                    'category_id' => $validated['category_id'],
                    'name' => $validated['name'],
                    'slug' => $slug,
                    'sku' => $validated['sku'],
                    'price' => $validated['price'],
                    'cost_price' => $validated['cost_price'],
                    'low_stock_threshold' => $validated['low_stock_threshold'],
                    'description' => $validated['description'] ?? null,
                    'image' => $imagePath,
                    'status' => 'active',
                ]);

                // Sản phẩm mới có tồn kho ban đầu bằng 0
                $product->inventory()->create([
                    'on_hand_qty' => 0,
                    'reserved_qty' => 0,
                ]);
            });
        } catch (\Throwable $e) {
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }

            throw $e;
        }

        return redirect()
            ->route('employee.products.index')
            ->with('success', 'Thêm sản phẩm thành công.');
    }

    // Hiển thị form chỉnh sửa sản phẩm
    public function edit(Product $product)
    {
        $categories = Category::where('status', 'active')
            ->orWhere('id', $product->category_id)
            ->orderBy('name')
            ->get();

        $product->load('inventory');

        return view('employee.products.edit', compact(
            'product',
            'categories'
        ));
    }

    // Cập nhật thông tin sản phẩm
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => [
                'required',
                'string',
                'max:200',
                Rule::unique('products', 'name')->ignore($product->id),
            ],
            'sku' => [
                'required',
                'string',
                'max:100',
                Rule::unique('products', 'sku')->ignore($product->id),
            ],
            'price' => ['required', 'numeric', 'min:0'],
            'cost_price' => ['required', 'numeric', 'min:0'],
            'low_stock_threshold' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ], [
            'category_id.required' => 'Vui lòng chọn danh mục.',
            'category_id.exists' => 'Danh mục không tồn tại.',
            'name.required' => 'Vui lòng nhập tên sản phẩm.',
            'name.unique' => 'Tên sản phẩm đã tồn tại.',
            'sku.required' => 'Vui lòng nhập SKU.',
            'sku.unique' => 'SKU đã tồn tại. Vui lòng sử dụng SKU khác.',
            'price.required' => 'Vui lòng nhập giá bán.',
            'cost_price.required' => 'Vui lòng nhập giá vốn.',
            'low_stock_threshold.required' => 'Vui lòng nhập ngưỡng cảnh báo tồn kho.',
            'image.image' => 'File tải lên phải là hình ảnh.',
            'image.mimes' => 'Ảnh phải có định dạng JPG, JPEG, PNG hoặc WEBP.',
            'image.max' => 'Ảnh sản phẩm không được vượt quá 5MB.',
        ]);

        $oldImage = $product->image;
        $newImage = null;

        if ($request->hasFile('image')) {
            $newImage = $request->file('image')->store('products', 'public');
        }

        try {
            DB::transaction(function () use (
                $product,
                $validated,
                $newImage
            ) {
                $product->category_id = $validated['category_id'];
                $product->name = $validated['name'];
                $product->sku = $validated['sku'];
                $product->price = $validated['price'];
                $product->cost_price = $validated['cost_price'];
                $product->low_stock_threshold = $validated['low_stock_threshold'];
                $product->description = $validated['description'] ?? null;

                if ($product->isDirty('name')) {
                    $baseSlug = Str::slug($validated['name']);
                    $slug = $baseSlug;
                    $counter = 1;

                    while (
                        Product::where('slug', $slug)
                            ->where('id', '!=', $product->id)
                            ->exists()
                    ) {
                        $slug = $baseSlug . '-' . $counter++;
                    }

                    $product->slug = $slug;
                }

                if ($newImage) {
                    $product->image = $newImage;
                }

                $product->save();

                // Chỉ tạo inventory nếu chưa có
                // Không thay đổi tồn thực tế và số lượng đang giữ chỗ
                $product->inventory()->firstOrCreate([], [
                    'on_hand_qty' => 0,
                    'reserved_qty' => 0,
                ]);
            });
        } catch (\Throwable $e) {
            if ($newImage) {
                Storage::disk('public')->delete($newImage);
            }

            throw $e;
        }

        // Chỉ xóa ảnh cũ sau khi cập nhật thành công
        if ($newImage && $oldImage) {
            Storage::disk('public')->delete($oldImage);
        }

        return redirect()
            ->route('employee.products.index')
            ->with('success', 'Cập nhật sản phẩm thành công.');
    }

    // Nhân viên không có quyền xóa sản phẩm
    public function destroy(Product $product)
    {
        abort(403, 'Bạn không có quyền xóa sản phẩm. Vui lòng liên hệ Quản lý.');
    }
}
