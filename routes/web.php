
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Manager\CategoryController;
use App\Http\Controllers\Manager\ProductController;
use App\Http\Controllers\Manager\InventoryController;
use App\Http\Controllers\Manager\StockReceiptController;
use App\Http\Controllers\Manager\InventoryTransactionController;
use App\Http\Controllers\Manager\ExpenseController;
use App\Http\Controllers\Manager\ReportController;
use App\Http\Controllers\Manager\DashboardController as ManagerDashboardController;
use App\Http\Controllers\Manager\CustomerController;
use App\Http\Controllers\Manager\OrderController as ManagerOrderController;
use App\Http\Controllers\Customer\ProductController as CustomerProductController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Employee\OrderController;
use App\Http\Controllers\Employee\InventoryController as EmployeeInventoryController;
use App\Http\Controllers\Employee\ProductController as EmployeeProductController;
use App\Http\Controllers\Employee\StockReceiptController as EmployeeStockReceiptController;
use App\Http\Controllers\Employee\InventoryTransactionController as EmployeeInventoryTransactionController;
use App\Models\Category;
use App\Models\Product;


// TRANG CHỦ
Route::get('/', function () {

    // Lấy danh mục đang hoạt động
    $categories = Category::where('status', 'active')
        ->orderBy('name')
        ->get();

    // Lấy 8 sản phẩm mới nhất đang kinh doanh
    $products = Product::with(['category', 'inventory'])
        ->where('status', 'active')
        ->latest()
        ->take(8)
        ->get();

    return view('home', compact('categories', 'products'));

})->name('home');

// DANH SÁCH VÀ CHI TIẾT SẢN PHẨM CÔNG KHAI

Route::get('/products', [CustomerProductController::class, 'index'])
    ->name('customer.products.index');

Route::get('/products/{product:slug}', [CustomerProductController::class, 'show'])
    ->name('customer.products.show');


// ĐIỀU HƯỚNG SAU KHI ĐĂNG NHẬP THEO VAI TRÒ

Route::get('/dashboard', function () {
    $user = auth()->user();

    return match ($user->role) {
        'customer' => redirect()->route('customer.dashboard'),
        'employee' => redirect()->route('employee.dashboard'),
        'manager'  => redirect()->route('manager.dashboard'),
        default    => abort(403, 'Vai trò tài khoản không hợp lệ.'),
    };
})->middleware('auth')->name('dashboard');




// KHU VỰC KHÁCH HÀNG

Route::middleware(['auth', 'role:customer'])
    ->prefix('customer')
    ->name('customer.')
    ->group(function (){

    // Trang tổng quan khách hàng
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])
        ->name('dashboard');

    
    
    // GIỎ HÀNG

    Route::get('/cart', [CartController::class, 'index'])
        ->name('cart.index');

    Route::post('/cart/{product}/add', [CartController::class, 'add'])
        ->name('cart.add');

    Route::patch('/cart/{product}', [CartController::class, 'update'])
        ->name('cart.update');

    Route::delete('/cart/{product}', [CartController::class, 'remove'])
        ->name('cart.remove');

    // Chức năng đặt hàng COD
    Route::get('/checkout', [CheckoutController::class, 'index'])
        ->name('checkout.index');

    Route::post('/checkout', [CheckoutController::class, 'store'])
        ->name('checkout.store');

    // Lịch sử đơn hàng
    Route::get('/orders', [CustomerOrderController::class, 'index'])
        ->name('orders.index');

    // Chi tiết đơn hàng
    Route::get('/orders/{id}', [CustomerOrderController::class, 'show'])
        ->name('orders.show');

    
    // Hủy đơn hàng
    Route::patch('/orders/{id}/cancel', [CustomerOrderController::class, 'cancel'])
        ->name('orders.cancel');

});

// KHU VỰC NHÂN VIÊN

Route::middleware(['auth', 'role:employee'])
    ->prefix('employee')
    ->name('employee.')
    ->group(function (){

    // Trang tổng quan nhân viên
        Route::get('/dashboard', function () {
            return view('employee.dashboard');
        })->name('dashboard');

        // Quản lý sản phẩm
        Route::get('/products', [EmployeeProductController::class, 'index'])
            ->name('products.index');

        // Form thêm sản phẩm
        Route::get('/products/create', [EmployeeProductController::class, 'create'])
            ->name('products.create');

        // Lưu sản phẩm mới
        Route::post('/products', [EmployeeProductController::class, 'store'])
            ->name('products.store');

        // Form chỉnh sửa sản phẩm
        Route::get('/products/{product}/edit', [EmployeeProductController::class, 'edit'])
            ->name('products.edit');

        // Cập nhật sản phẩm
        Route::put('/products/{product}', [EmployeeProductController::class, 'update'])
            ->name('products.update');

        // Quản lý đơn hàng
        Route::get('/orders', [OrderController::class, 'index'])
            ->name('orders.index');

        // Chi tiết đơn hàng
        Route::get('/orders/{order}', [OrderController::class, 'show'])
            ->name('orders.show');

        // Xác nhận đơn hàng
        Route::post('/orders/{order}/confirm', [OrderController::class, 'confirm'])
            ->name('orders.confirm');

        // Hủy đơn hàng
        Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])
            ->name('orders.cancel');

        // Cập nhật trạng thái đơn hàng
        Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])
            ->name('orders.update-status');

        // Xác nhận thanh toán
        Route::post('/orders/{order}/mark-paid', [OrderController::class, 'markPaid'])
            ->name('orders.mark-paid');

        // Xem tồn kho
        Route::get('/inventory', [EmployeeInventoryController::class, 'index'])
            ->name('inventory.index');
        
        // LỊCH SỬ GIAO DỊCH KHO

        // Danh sách giao dịch kho
        Route::get('/inventory-transactions', [EmployeeInventoryTransactionController::class, 'index'])
            ->name('inventory-transactions.index');

        // Chi tiết giao dịch kho
        Route::get('/inventory-transactions/{transaction}', [EmployeeInventoryTransactionController::class, 'show'])
            ->name('inventory-transactions.show');

        // QUẢN LÝ PHIẾU NHẬP KHO

        // Danh sách phiếu nhập
        Route::get('/stock-receipts', [EmployeeStockReceiptController::class, 'index'])
            ->name('stock-receipts.index');

        // Form tạo phiếu nhập
        Route::get('/stock-receipts/create', [EmployeeStockReceiptController::class, 'create'])
            ->name('stock-receipts.create');

        // Lưu phiếu nhập và cập nhật tồn kho
        Route::post('/stock-receipts', [EmployeeStockReceiptController::class, 'store'])
            ->name('stock-receipts.store');

        // Chi tiết phiếu nhập
        Route::get('/stock-receipts/{stockReceipt}', [EmployeeStockReceiptController::class, 'show'])
            ->name('stock-receipts.show');
});


// KHU VỰC QUẢN LÝ

Route::middleware(['auth', 'role:manager'])
    ->prefix('manager')
    ->name('manager.')
    ->group(function () {

        // Dashboard quản lý
        Route::get('/dashboard', [ManagerDashboardController::class, 'index'])
            ->name('dashboard');

        // QUẢN LÝ KHÁCH HÀNG

        Route::get('/customers', [CustomerController::class, 'index'])
            ->name('customers.index');

        Route::get('/customers/{customer}', [CustomerController::class, 'show'])
            ->name('customers.show');


        // Quản lý danh mục sản phẩm
        Route::get('/categories', [CategoryController::class, 'index'])
            ->name('categories.index');

        Route::get('/categories/create', [CategoryController::class, 'create'])
            ->name('categories.create');

        Route::post('/categories', [CategoryController::class, 'store'])
            ->name('categories.store');

        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])
            ->name('categories.edit');

        Route::put('/categories/{category}', [CategoryController::class, 'update'])
            ->name('categories.update');

        Route::patch('/categories/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])
            ->name('categories.toggle-status');

        // Quản lý sản phẩm
        Route::get('/products', [ProductController::class, 'index'])
            ->name('products.index');

        Route::get('/products/create', [ProductController::class, 'create'])
            ->name('products.create');

        Route::post('/products', [ProductController::class, 'store'])
            ->name('products.store');

        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])
            ->name('products.edit');

        Route::put('/products/{product}', [ProductController::class, 'update'])
            ->name('products.update');

        Route::patch('/products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])
            ->name('products.toggle-status');

        // Quản lý tồn kho
        Route::get('/inventory', [
            InventoryController::class,
            'index'
        ])->name('inventory.index');

        // Quản lý phiếu nhập kho
        Route::get('/stock-receipts', [StockReceiptController::class, 'index'])
            ->name('stock-receipts.index');

        Route::get('/stock-receipts/create', [StockReceiptController::class, 'create'])
            ->name('stock-receipts.create');

        Route::post('/stock-receipts', [StockReceiptController::class, 'store'])
            ->name('stock-receipts.store');

        Route::get('/stock-receipts/{stockReceipt}', [StockReceiptController::class, 'show'])
            ->name('stock-receipts.show');

        // Lịch sử giao dịch kho
        Route::get('/inventory-transactions', [InventoryTransactionController::class, 'index'])
            ->name('inventory-transactions.index'); 
            
        // Xem chi tiết giao dịch kho
        Route::get('/inventory-transactions/{transaction}', [InventoryTransactionController::class, 'show'])
            ->name('inventory-transactions.show');

        // Quản lý chi phí
        Route::resource('expenses', ExpenseController::class)
            ->except(['show']);

        // Báo cáo doanh thu và lợi nhuận
        Route::get('/reports', [ReportController::class, 'index'])
            ->name('reports.index');

        // Xuất báo cáo doanh thu
        Route::get('/reports/export', [ReportController::class, 'export'])
            ->name('reports.export');

        Route::delete('/products/{product}', [ProductController::class, 'destroy'])
            ->name('products.destroy');

        // Khóa / mở khóa tài khoản khách hàng
        Route::patch('/customers/{customer}/toggle-status', [
            CustomerController::class,
            'toggleStatus'
        ])
            ->name('customers.toggle-status');

        // Quản lý đơn hàng
        Route::get('/orders', [ManagerOrderController::class, 'index'])
            ->name('orders.index');

        Route::get('/orders/{order}', [ManagerOrderController::class, 'show'])
            ->name('orders.show');
        });     

// QUẢN LÝ HỒ SƠ CÁ NHÂN

Route::middleware('auth')->group(function () {

    // Trang thông tin cá nhân và địa chỉ nhận hàng
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    // Cập nhật thông tin cá nhân
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    // Trang đổi mật khẩu riêng
    Route::get('/profile/password', [ProfileController::class, 'editPassword'])
        ->name('profile.password');

});


// CÁC ROUTE ĐĂNG NHẬP, ĐĂNG KÝ CỦA LARAVEL BREEZE

require __DIR__ . '/auth.php';
