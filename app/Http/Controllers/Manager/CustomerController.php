<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    // Danh sách khách hàng
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');

        $customers = User::query()
            ->where('role', 'customer')
            ->withCount('customerOrders')
            ->withSum([
                'customerOrders as total_spent' => function ($query) {
                    $query->where('order_status', 'completed');
                }
            ], 'total_amount')
            ->when($keyword, function ($query) use ($keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%")
                        ->orWhere('email', 'like', "%{$keyword}%")
                        ->orWhere('phone', 'like', "%{$keyword}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $summary = [
            'total' => User::where('role', 'customer')->count(),
            'active' => User::where('role', 'customer')
                ->where('status', 'active')->count(),
            'inactive' => User::where('role', 'customer')
                ->where('status', 'inactive')->count(),
        ];

        return view('manager.customers.index', compact(
            'customers',
            'keyword',
            'summary'
        ));
    }

    // Chi tiết khách hàng
    public function show(User $customer)
    {
        abort_unless($customer->role === 'customer', 404);

        $customer->load([
            'customerOrders' => function ($query) {
                $query->with('details')
                    ->latest()
                    ->take(20);
            }
        ]);

        $totalOrders = $customer->customerOrders()->count();

        $completedOrders = $customer->customerOrders()
            ->where('order_status', 'completed')
            ->count();

        $totalSpent = $customer->customerOrders()
            ->where('order_status', 'completed')
            ->sum('total_amount');

        return view('manager.customers.show', compact(
            'customer',
            'totalOrders',
            'completedOrders',
            'totalSpent'
        ));
    }

    // Khóa hoặc mở khóa tài khoản khách hàng
    public function toggleStatus(User $customer)
    {
        // Chỉ được thao tác với tài khoản khách hàng
        abort_unless($customer->role === 'customer', 404);

        $newStatus = $customer->status === 'active'
            ? 'inactive'
            : 'active';

        $customer->update([
            'status' => $newStatus,
        ]);

        return redirect()
            ->route('manager.customers.index')
            ->with(
                'success',
                $newStatus === 'active'
                    ? 'Đã mở khóa tài khoản khách hàng thành công.'
                    : 'Đã khóa tài khoản khách hàng thành công.'
            );
    }
}