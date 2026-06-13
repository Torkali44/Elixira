<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Support\UserNotifier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $vendorProfile = $user->vendorProfile;

        if (! $vendorProfile || ! $vendorProfile->brand) {
            return redirect()->route('home')->with('error', 'Brand not found.');
        }

        $brandId = $vendorProfile->brand->id;
        $vendorItemIds = Item::where('brand_id', $brandId)->pluck('id');

        // Product Stats
        $stats = [
            'total_items' => Item::where('brand_id', $brandId)->count(),
            'pending_items' => Item::where('brand_id', $brandId)->where('status', 'pending')->count(),
            'revision_items' => Item::where('brand_id', $brandId)->where('status', 'rejected_with_notes')->count(),
            'approved_items' => Item::where('brand_id', $brandId)->where('status', 'approved')->count(),
            'rejected_items' => Item::where('brand_id', $brandId)->where('status', 'rejected')->count(),
        ];

        // Orders & Revenue Stats
        $orderItems = OrderItem::whereIn('item_id', $vendorItemIds);
        $orderIds = OrderItem::whereIn('item_id', $vendorItemIds)->pluck('order_id')->unique();

        $stats['total_orders'] = $orderIds->count();
        $stats['total_revenue'] = OrderItem::whereIn('item_id', $vendorItemIds)
            ->selectRaw('SUM(price * quantity) as total')
            ->value('total') ?? 0;
        $stats['items_sold'] = OrderItem::whereIn('item_id', $vendorItemIds)->sum('quantity');

        // Unique Customers
        $stats['unique_customers'] = Order::whereIn('id', $orderIds)->distinct('user_id')->count('user_id');

        // Recent Orders (for vendor's products)
        $recentOrders = Order::whereIn('id', $orderIds)
            ->with(['user', 'orderItems' => function ($q) use ($vendorItemIds) {
                $q->whereIn('item_id', $vendorItemIds)->with('item');
            }])
            ->latest()
            ->take(10)
            ->get();

        // Top Selling Products
        $topProducts = OrderItem::whereIn('item_id', $vendorItemIds)
            ->select('item_id', DB::raw('SUM(quantity) as total_sold'), DB::raw('SUM(price * quantity) as total_revenue'))
            ->groupBy('item_id')
            ->orderByDesc('total_sold')
            ->take(5)
            ->with('item')
            ->get();

        // Top Customers
        $topCustomers = Order::whereIn('orders.id', $orderIds)
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->whereIn('order_items.item_id', $vendorItemIds)
            ->select('orders.user_id', DB::raw('COUNT(DISTINCT orders.id) as order_count'), DB::raw('SUM(order_items.price * order_items.quantity) as total_spent'))
            ->groupBy('orders.user_id')
            ->orderByDesc('total_spent')
            ->take(5)
            ->get()
            ->map(function ($row) {
                $row->user = User::find($row->user_id);

                return $row;
            })->filter(fn ($row) => $row->user !== null);

        // Monthly Sales (Last 6 months)
        $monthlySales = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $month = $date->format('M Y');
            $revenue = OrderItem::whereIn('item_id', $vendorItemIds)
                ->whereHas('order', function ($q) use ($date) {
                    $q->whereMonth('created_at', $date->month)
                        ->whereYear('created_at', $date->year);
                })
                ->selectRaw('SUM(price * quantity) as total')
                ->value('total') ?? 0;
            $monthlySales[] = ['month' => $date->format('M'), 'revenue' => round($revenue, 2)];
        }

        // Low Stock Items
        $lowStockItems = Item::where('brand_id', $brandId)
            ->where('status', 'approved')
            ->where('stock', '<=', 10)
            ->orderBy('stock')
            ->take(5)
            ->get();

        return view('vendor.dashboard', compact(
            'stats', 'recentOrders', 'topProducts', 'topCustomers', 'monthlySales', 'lowStockItems'
        ));
    }

    public function orders()
    {
        $user = auth()->user();
        $vendorProfile = $user->vendorProfile;

        if (! $vendorProfile || ! $vendorProfile->brand) {
            return redirect()->route('home')->with('error', 'Brand not found.');
        }

        $brandId = $vendorProfile->brand->id;
        $vendorItemIds = Item::where('brand_id', $brandId)->pluck('id');
        $orderIds = OrderItem::whereIn('item_id', $vendorItemIds)->pluck('order_id')->unique();

        $orders = Order::whereIn('id', $orderIds)
            ->with(['user', 'orderItems' => function ($q) use ($vendorItemIds) {
                $q->whereIn('item_id', $vendorItemIds)->with('item');
            }])
            ->latest()
            ->paginate(20);

        return view('vendor.orders', compact('orders', 'vendorItemIds'));
    }

    public function showOrder(Order $order)
    {
        $user = auth()->user();
        $vendorProfile = $user->vendorProfile;

        if (! $vendorProfile || ! $vendorProfile->brand) {
            abort(403);
        }

        $brandId = $vendorProfile->brand->id;
        $vendorItemIds = Item::where('brand_id', $brandId)->pluck('id')->toArray();

        // Check if the order contains at least one of this vendor's items
        $hasVendorItems = $order->orderItems()->whereIn('item_id', $vendorItemIds)->exists();
        if (! $hasVendorItems) {
            abort(403);
        }

        // Eager load only the vendor's items
        $order->load(['user', 'orderItems' => function ($q) use ($vendorItemIds) {
            $q->whereIn('item_id', $vendorItemIds)->with('item');
        }]);

        return view('vendor.orders.show', compact('order', 'vendorItemIds'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $user = auth()->user();
        $vendorProfile = $user->vendorProfile;

        if (! $vendorProfile || ! $vendorProfile->brand) {
            abort(403);
        }

        $brandId = $vendorProfile->brand->id;
        $vendorItemIds = Item::where('brand_id', $brandId)->pluck('id')->toArray();

        $hasVendorItems = $order->orderItems()->whereIn('item_id', $vendorItemIds)->exists();
        if (! $hasVendorItems) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,ready,delivered,cancelled',
        ]);

        $order->update(['status' => $request->status]);

        try {
            if ($order->user) {
                UserNotifier::send($order->user->id, 'order_status_updated', [
                    'order' => (string) $order->id,
                    'status' => $request->status,
                ], route('profile.orders.show', $order->id));
            }
        } catch (\Throwable $e) {
            \Log::error('Order status update notification failed: '.$e->getMessage());
        }

        return redirect()->back()->with('success', 'The order status has been successfully updated.');
    }
}
