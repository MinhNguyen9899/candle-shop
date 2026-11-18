<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Hiển thị giỏ hàng.
     */
    public function index()
    {
        // Lấy giỏ hàng từ session.
        $cart = session()->get('cart_products', []);

        // Lấy thông tin sản phẩm, danh mục và tồn kho.
        $products = Product::with(['category', 'inventory'])
            ->whereIn('id', array_keys($cart))
            ->get()
            ->keyBy('id');

        $items = [];

        foreach ($cart as $productId => $quantity) {

            $product = $products->get($productId);

            // Xóa sản phẩm không tồn tại hoặc đã ngừng kinh doanh.
            if (!$product || $product->status !== 'active') {
                unset($cart[$productId]);
                continue;
            }

            // Số lượng tồn kho còn có thể bán.
            $availableQty = $product->inventory
                ? max(0, (int) $product->inventory->available_qty)
                : 0;

            $quantity = (int) $quantity;

            // Thông tin sản phẩm trong giỏ hàng.
            $items[] = [
                'product' => $product,
                'quantity' => $quantity,
                'available_qty' => $availableQty,
                'subtotal' => (float) $product->price * $quantity,
            ];
        }

        // Cập nhật lại giỏ hàng sau khi loại bỏ sản phẩm không hợp lệ.
        session()->put('cart_products', $cart);

        // Tính tổng tiền.
        $total = collect($items)->sum('subtotal');

        $cartCount = count($items);

        return view('customer.cart.index', compact(
            'items',
            'total',
            'cartCount'
        ));
    }

    /**
     * Thêm sản phẩm vào giỏ hàng.
     */
    public function add(Request $request, Product $product)
    {
        // Kiểm tra trạng thái kinh doanh.
        if ($product->status !== 'active') {
            return back()->with(
                'error',
                'Sản phẩm không còn kinh doanh.'
            );
        }

        // Kiểm tra dữ liệu số lượng.
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:100',
        ]);

        // Lấy tồn kho mới nhất.
        $inventory = $product->inventory()->first();

        $availableQty = $inventory
            ? max(0, (int) $inventory->available_qty)
            : 0;

        // Lấy giỏ hàng hiện tại.
        $cart = session()->get('cart_products', []);

        // Sử dụng product_id làm khóa.
        $productId = $product->id;

        $currentQuantity = (int) ($cart[$productId] ?? 0);

        $newQuantity = $currentQuantity + (int) $validated['quantity'];

        // Kiểm tra tồn kho khả dụng.
        if ($newQuantity > $availableQty) {
            return back()->with(
                'error',
                'Số lượng tồn kho khả dụng không đủ. Hiện chỉ còn '
                . $availableQty . ' sản phẩm.'
            );
        }

        // Giới hạn tổng số lượng trong giỏ.
        if ($newQuantity > 100) {
            return back()->with(
                'error',
                'Mỗi sản phẩm chỉ được đặt tối đa 100 sản phẩm.'
            );
        }

        // Lưu số lượng mới vào giỏ hàng.
        $cart[$productId] = $newQuantity;

        session()->put('cart_products', $cart);

        return redirect()
            ->route('customer.cart.index')
            ->with('success', 'Đã thêm sản phẩm vào giỏ hàng.');
    }

    /**
     * Cập nhật số lượng sản phẩm trong giỏ hàng.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:100',
        ]);

        $cart = session()->get('cart_products', []);
        $productId = $product->id;

        if (!isset($cart[$productId])) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Sản phẩm không có trong giỏ hàng.',
                ], 404);
            }

            return back()->with('error', 'Sản phẩm không có trong giỏ hàng.');
        }

        if ($product->status !== 'active') {
            unset($cart[$productId]);
            session()->put('cart_products', $cart);

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Sản phẩm đã ngừng kinh doanh.',
                ], 422);
            }

            return back()->with('error', 'Sản phẩm đã ngừng kinh doanh.');
        }

        $inventory = $product->inventory()->first();

        $availableQty = $inventory
            ? max(0, (int) $inventory->available_qty)
            : 0;

        $quantity = (int) $validated['quantity'];

        if ($quantity > $availableQty) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Số lượng tồn kho khả dụng không đủ. Hiện chỉ còn '
                        . $availableQty . ' sản phẩm.',
                ], 422);
            }

            return back()->with(
                'error',
                'Số lượng tồn kho khả dụng không đủ. Hiện chỉ còn '
                . $availableQty . ' sản phẩm.'
            );
        }

        // Lưu số lượng mới vào Session.
        $cart[$productId] = $quantity;
        session()->put('cart_products', $cart);

        // Tính lại tổng tiền từ dữ liệu sản phẩm trong database.
        $cartProducts = Product::whereIn('id', array_keys($cart))->get();

        $total = $cartProducts->sum(function ($item) use ($cart) {
            return (float) $item->price * (int) ($cart[$item->id] ?? 0);
        });

        // Trả dữ liệu JSON cho JavaScript.
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'product_id' => $productId,
                'quantity' => $quantity,
                'subtotal' => (float) $product->price * $quantity,
                'total' => $total,
                'item_count' => array_sum($cart),
            ]);
        }

        return back()->with('success', 'Đã cập nhật giỏ hàng.');
    }

    /**
     * Xóa sản phẩm khỏi giỏ hàng.
     */
    public function remove(Product $product)
    {
        $cart = session()->get('cart_products', []);

        // Xóa sản phẩm theo product_id.
        unset($cart[$product->id]);

        session()->put('cart_products', $cart);

        return back()->with(
            'success',
            'Đã xóa sản phẩm khỏi giỏ hàng.'
        );
    }
}
