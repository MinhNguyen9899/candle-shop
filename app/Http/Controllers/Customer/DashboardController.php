<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;

class DashboardController extends Controller
{
    /**
     * Hiển thị trang chủ dành cho khách hàng.
     */
    public function index()
    {
        // Lấy 4 sản phẩm nổi bật đang hoạt động
        $featuredProducts = Product::with([
            'category',
            'inventory',
        ])
            ->where('status', 'active')
            ->latest()
            ->take(4)
            ->get();

        // Lấy các danh mục đang hoạt động
        $categories = Category::where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('customer.dashboard', compact(
            'featuredProducts',
            'categories'
        ));
    }
}