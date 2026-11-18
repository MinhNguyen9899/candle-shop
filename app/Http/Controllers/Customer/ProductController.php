<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Hiển thị danh sách sản phẩm dành cho khách hàng.
     */
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');
        $categoryId = $request->input('category_id');

        // Lấy sản phẩm đang kinh doanh và thông tin tồn kho.
        $products = Product::with([
            'category',
            'inventory',
        ])
            ->where('status', 'active')

            // Tìm kiếm theo tên sản phẩm hoặc mã SKU.
            ->when($keyword, function ($query) use ($keyword) {
                $query->where(function ($subQuery) use ($keyword) {
                    $subQuery->where('name', 'like', '%' . $keyword . '%')
                        ->orWhere('sku', 'like', '%' . $keyword . '%');
                });
            })

            // Lọc theo danh mục.
            ->when($categoryId, function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            })

            // Ưu tiên sản phẩm mới nhất.
            ->latest()
            ->paginate(12)
            ->withQueryString();

        // Chỉ hiển thị danh mục đang hoạt động.
        $categories = Category::where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('customer.products.index', compact(
            'products',
            'categories',
            'keyword',
            'categoryId'
        ));
    }

    /**
     * Hiển thị chi tiết một sản phẩm.
     */
    public function show(Product $product)
    {
        // Không cho phép xem sản phẩm đã ngừng kinh doanh.
        abort_unless($product->status === 'active', 404);

        // Lấy danh mục và tồn kho của sản phẩm.
        $product->load([
            'category',
            'inventory',
        ]);

        return view('customer.products.show', compact('product'));
    }
}
