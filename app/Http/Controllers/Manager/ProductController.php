<?php

namespace App\Http\Controllers\Manager;

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

        return view('manager.products.index', compact(
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

        return view('manager.products.create', compact('categories'));
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
            'sku.required' => 'Vui lòng nhập SKU.',
            'sku.unique' => 'SKU đã tồn tại.',
            'price.required' => 'Vui lòng nhập giá bán.',
            'cost_price.required' => 'Vui lòng nhập giá vốn.',
            'low_stock_threshold.required' => 'Vui lòng nhập ngưỡng cảnh báo tồn kho.',
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

                // Sản phẩm mới bắt đầu với tồn kho bằng 0
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
            ->route('manager.products.index')
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

        return view('manager.products.edit', compact(
            'product',
            'categories'
        ));
    }

    // Cập nhật sản phẩm
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
            'name.required' => 'Vui lòng nhập tên sản phẩm.',
            'name.unique' => 'Tên sản phẩm đã tồn tại.',
            'sku.required' => 'Vui lòng nhập SKU.',
            'sku.unique' => 'SKU đã tồn tại.',
            'price.required' => 'Vui lòng nhập giá bán.',
            'cost_price.required' => 'Vui lòng nhập giá vốn.',
            'low_stock_threshold.required' => 'Vui lòng nhập ngưỡng cảnh báo tồn kho.',
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

                // Chỉ tạo inventory nếu chưa có.
                // Không ghi đè số lượng tồn thực tế hoặc đang giữ chỗ.
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
            ->route('manager.products.index')
            ->with('success', 'Cập nhật sản phẩm thành công.');
    }

    // Ngừng kinh doanh hoặc khôi phục sản phẩm
    public function toggleStatus(Product $product)
    {
        if ($product->status === 'active') {
            $product->status = 'discontinued';
            $message = 'Đã chuyển sản phẩm sang trạng thái ngừng kinh doanh.';
        } else {
            $product->status = 'active';
            $message = 'Đã đưa sản phẩm trở lại kinh doanh.';
        }

        $product->save();

        return redirect()
            ->route('manager.products.index')
            ->with('success', $message);
    }

    // Xóa sản phẩm nếu chưa phát sinh giao dịch
    public function destroy(Product $product)
    {
        $hasOrders = DB::table('order_details')
            ->where('product_id', $product->id)
            ->exists();

        $hasStockReceipts = DB::table('stock_receipt_details')
            ->where('product_id', $product->id)
            ->exists();

        $hasInventoryTransactions = DB::table('inventory_transactions')
            ->where('product_id', $product->id)
            ->exists();

        $hasReservations = DB::table('stock_reservation_details')
            ->where('product_id', $product->id)
            ->exists();

        $inventory = $product->inventory;

        $hasStock = $inventory && (
            $inventory->on_hand_qty > 0 ||
            $inventory->reserved_qty > 0
        );

        if (
            $hasOrders ||
            $hasStockReceipts ||
            $hasInventoryTransactions ||
            $hasReservations ||
            $hasStock
        ) {
            return back()->with(
                'error',
                'Không thể xóa sản phẩm đã phát sinh giao dịch hoặc có tồn kho. Hãy sử dụng chức năng ngừng kinh doanh.'
            );
        }

        $productImage = $product->image;

        DB::transaction(function () use ($product) {
            // Xóa dòng tồn kho trước khi xóa sản phẩm
            $product->inventory()->delete();

            $product->delete();
        });

        if ($productImage) {
            Storage::disk('public')->delete($productImage);
        }

        return redirect()
            ->route('manager.products.index')
            ->with('success', 'Đã xóa sản phẩm thành công.');
    }
}
