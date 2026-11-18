<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\StockReservation;
use App\Models\StockReservationDetail;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    // Đọc dữ liệu tỉnh/thành phố và phường/xã từ file JSON
    private function getLocationData(): array
    {
        $path = public_path('data/vietnam-addresses.json');

        if (!file_exists($path)) {
            abort(500, 'Không tìm thấy dữ liệu địa chỉ. Vui lòng kiểm tra file public/data/vietnam-addresses.json.');
        }

        $data = json_decode(file_get_contents($path), true);

        if (
            !is_array($data)
            || !isset($data['data']['province'])
            || !isset($data['data']['ward'])
        ) {
            abort(500, 'File dữ liệu địa chỉ không đúng định dạng.');
        }

        return $data['data'];
    }

    // Tách địa chỉ mặc định thành số nhà, tỉnh/thành phố và phường/xã
    private function parseDefaultAddress(?string $address, array $locationData): array
    {
        $result = [
            'street_address' => '',
            'province_id' => '',
            'ward_id' => '',
        ];

        if (!$address) {
            return $result;
        }

        $address = trim($address);

        foreach ($locationData['province'] as $province) {
            $provinceName = trim($province['name_local']);

            foreach ($locationData['ward'] as $ward) {
                if ((string) ($ward['parent_id'] ?? '') !== (string) $province['id']) {
                    continue;
                }

                $wardName = trim($ward['name_local']);

                $pattern = '/,\s*'
                    . preg_quote($wardName, '/')
                    . '\s*,\s*'
                    . preg_quote($provinceName, '/')
                    . '\s*$/iu';

                if (preg_match($pattern, $address, $matches, PREG_OFFSET_CAPTURE)) {
                    $street = trim(substr($address, 0, $matches[0][1]));

                    return [
                        'street_address' => $street,
                        'province_id' => (string) $province['id'],
                        'ward_id' => (string) $ward['id'],
                    ];
                }
            }
        }

        // Nếu không nhận diện được tỉnh và phường/xã,
        // vẫn điền địa chỉ đã lưu vào ô số nhà, tên đường.
        $result['street_address'] = $address;

        return $result;
    }

    // Hiển thị trang xác nhận đặt hàng
    public function index()
    {
        // Sử dụng giỏ hàng theo product_id
        $cart = session()->get('cart_products', []);

        if (empty($cart)) {
            return redirect()
                ->route('customer.cart.index')
                ->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        // Sắp xếp ID sản phẩm để khóa tồn kho theo thứ tự thống nhất.
        ksort($cart, SORT_NUMERIC);

        $products = Product::with(['category', 'inventory'])
            ->whereIn('id', array_keys($cart))
            ->get()
            ->keyBy('id');

        $items = [];
        $subtotal = 0;

        foreach ($cart as $productId => $quantity) {
            $product = $products->get($productId);

            if (!$product || $product->status !== 'active') {
                return redirect()
                    ->route('customer.cart.index')
                    ->with('error', 'Có sản phẩm không còn kinh doanh.');
            }

            $availableQty = $product->inventory
                ? $product->inventory->available_qty
                : 0;

            if ($quantity > $availableQty) {
                return redirect()
                    ->route('customer.cart.index')
                    ->with('error', 'Số lượng sản phẩm trong giỏ vượt quá tồn kho.');
            }

            $lineSubtotal = $product->price * $quantity;
            $subtotal += $lineSubtotal;

            $items[] = [
                'product' => $product,
                'quantity' => $quantity,
                'subtotal' => $lineSubtotal,
            ];
        }

        $shippingFee = 0;
        $total = $subtotal + $shippingFee;

        // Lấy dữ liệu địa chỉ để hiển thị dropdown
        $locationData = $this->getLocationData();

        $provinces = $locationData['province'];
        $wards = $locationData['ward'];

        // Lấy địa chỉ giao hàng mặc định của khách hàng
        $defaultAddress = $this->parseDefaultAddress(
            auth()->user()->default_shipping_address,
            $locationData
        );

        return view('customer.checkout.index', compact(
            'items',
            'subtotal',
            'shippingFee',
            'total',
            'provinces',
            'wards',
            'defaultAddress'
        ));
    }

    // Xử lý đặt hàng COD
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',

            'street_address' => 'required|string|max:500',
            'province_id' => 'required|string|max:50',
            'ward_id' => 'required|string|max:50',

            'note' => 'nullable|string|max:1000',
            'save_default_address' => 'nullable|boolean',
        ], [
            'customer_name.required' => 'Vui lòng nhập tên người nhận.',
            'customer_phone.required' => 'Vui lòng nhập số điện thoại.',
            'street_address.required' => 'Vui lòng nhập số nhà và tên đường.',
            'province_id.required' => 'Vui lòng chọn tỉnh/thành phố.',
            'ward_id.required' => 'Vui lòng chọn phường/xã.',
        ]);

        // Kiểm tra tỉnh/thành phố và phường/xã có trong dữ liệu
        $locationData = $this->getLocationData();

        $province = collect($locationData['province'])
            ->firstWhere('id', $validated['province_id']);

        $ward = collect($locationData['ward'])
            ->firstWhere('id', $validated['ward_id']);

        if (!$province) {
            throw ValidationException::withMessages([
                'province_id' => 'Tỉnh/thành phố được chọn không hợp lệ.',
            ]);
        }

        if (
            !$ward
            || (string) ($ward['parent_id'] ?? '') !== (string) $province['id']
        ) {
            throw ValidationException::withMessages([
                'ward_id' => 'Phường/xã không thuộc tỉnh/thành phố đã chọn.',
            ]);
        }

        // Ghép địa chỉ thành một chuỗi để lưu vào orders.shipping_address
        $validated['shipping_address'] = implode(', ', [
            trim($validated['street_address']),
            $ward['name_local'],
            $province['name_local'],
        ]);

        $saveDefaultAddress = $request->boolean('save_default_address');

        // Lấy giỏ hàng theo product_id
        $cart = session()->get('cart_products', []);

        if (empty($cart)) {
            return redirect()
                ->route('customer.cart.index')
                ->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        $order = DB::transaction(function () use (
            $cart,
            $validated,
            $saveDefaultAddress
        ) {
            $checkedItems = [];
            $subtotal = 0;

            // Khóa và kiểm tra từng sản phẩm để tránh đặt vượt tồn kho
            foreach ($cart as $productId => $quantity) {

                $product = Product::where('id', $productId)
                    ->lockForUpdate()
                    ->first();

                if (!$product || $product->status !== 'active') {
                    throw ValidationException::withMessages([
                        'cart' => 'Có sản phẩm không còn kinh doanh.',
                    ]);
                }

                if (
                    filter_var($quantity, FILTER_VALIDATE_INT) === false
                    || (int) $quantity < 1
                    || (int) $quantity > 100
                ) {
                    throw ValidationException::withMessages([
                        'cart' => 'Số lượng sản phẩm không hợp lệ.',
                    ]);
                }

                $quantity = (int) $quantity;

                // Khóa dòng tồn kho của sản phẩm
                $inventory = Inventory::where('product_id', $product->id)
                    ->lockForUpdate()
                    ->first();

                if (!$inventory) {
                    throw ValidationException::withMessages([
                        'cart' => 'Sản phẩm ' . $product->name
                            . ' chưa được thiết lập tồn kho.',
                    ]);
                }

                $availableQty = max(
                    0,
                    $inventory->on_hand_qty - $inventory->reserved_qty
                );

                if ($quantity > $availableQty) {
                    throw ValidationException::withMessages([
                        'cart' => 'Sản phẩm ' . $product->name
                            . ' không đủ số lượng. Hiện còn '
                            . $availableQty . ' sản phẩm.',
                    ]);
                }

                $lineSubtotal = $product->price * $quantity;
                $subtotal += $lineSubtotal;

                $checkedItems[] = [
                    'product' => $product,
                    'inventory' => $inventory,
                    'quantity' => $quantity,
                    'subtotal' => $lineSubtotal,
                ];
            }

            $shippingFee = 0;
            $total = $subtotal + $shippingFee;

            // Tạo đơn hàng
            $order = Order::create([
                'order_code' => 'DH'
                    . now()->format('YmdHis')
                    . random_int(100, 999),

                'customer_id' => auth()->id(),
                'staff_id' => null,

                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'shipping_address' => $validated['shipping_address'],

                'subtotal' => $subtotal,
                'shipping_fee' => $shippingFee,
                'total_amount' => $total,

                'payment_method' => 'cod',
                'payment_status' => 'unpaid',
                'order_status' => 'pending',

                'note' => $validated['note'] ?? null,
            ]);

            // Cập nhật địa chỉ mặc định nếu khách hàng đã tích chọn
            if ($saveDefaultAddress) {
                $customer = auth()->user();

                $customer->default_shipping_address = $validated['shipping_address'];
                $customer->save();
            }

            // Tạo chi tiết đơn hàng
            foreach ($checkedItems as $item) {
                $product = $item['product'];
                $quantity = $item['quantity'];

                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,

                    // Lưu thông tin sản phẩm tại thời điểm đặt hàng
                    'product_name' => $product->name,
                    'sku' => $product->sku,

                    'quantity' => $quantity,
                    'unit_price' => $product->price,
                    'unit_cost_snapshot' => $product->cost_price,
                    'subtotal' => $item['subtotal'],
                ]);
            }

            // Tạo phiếu giữ hàng
            $reservation = StockReservation::create([
                'order_id' => $order->id,
                'status' => 'reserved',
                'reserved_at' => now(),
            ]);

            // Tạo chi tiết giữ hàng và tăng số lượng đang giữ
            foreach ($checkedItems as $item) {
                $quantity = $item['quantity'];

                StockReservationDetail::create([
                    'stock_reservation_id' => $reservation->id,
                    'product_id' => $item['product']->id,
                    'quantity' => $quantity,
                ]);

                // Chỉ tăng reserved_qty, chưa trừ hàng thực tế
                $item['inventory']->reserved_qty += $quantity;
                $item['inventory']->save();
            }

            return $order;
        }, 3);

        // Chỉ xóa giỏ hàng sau khi giao dịch thành công
        session()->forget('cart_products');

        return redirect()
            ->route('customer.cart.index')
            ->with(
                'success',
                'Đặt hàng thành công! Mã đơn hàng của bạn: '
                . $order->order_code
            );
    }
}
