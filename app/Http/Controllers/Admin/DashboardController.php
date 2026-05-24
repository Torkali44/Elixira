<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Brand;
use App\Models\VendorProfile;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $categoriesCount = Category::count();
        $itemsCount = Item::count();
        
        $itemsBreakdown = [
            'approved' => Item::where('status', 'approved')->count(),
            'pending' => Item::where('status', 'pending')->count(),
            'rejected' => Item::whereIn('status', ['rejected', 'rejected_with_notes'])->count(),
        ];

        $ordersCount = Order::count();
        $pendingOrdersCount = Order::where('status', 'pending')->count();
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total_amount');
        
        $usersCount = User::count();
        $suspendedUsersCount = User::where('is_suspended', true)->count();
        $adminsCount = User::where('role', 'admin')->count();
        $vendorsCount = User::where('role', 'vendor')->count();

        // Top Selling Vendors (Brands)
        $topVendors = Brand::join('items', 'brands.id', '=', 'items.brand_id')
            ->join('order_items', 'items.id', '=', 'order_items.item_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('vendor_profiles', 'brands.vendor_profile_id', '=', 'vendor_profiles.id')
            ->join('users', 'vendor_profiles.user_id', '=', 'users.id')
            ->where('orders.status', '!=', 'cancelled')
            ->select(
                'brands.id as brand_id',
                'brands.name as brand_name',
                'brands.logo as brand_logo',
                'users.name as vendor_name',
                'users.email as vendor_email',
                DB::raw('SUM(order_items.quantity) as total_units_sold'),
                DB::raw('SUM(order_items.price * order_items.quantity) as total_sales')
            )
            ->groupBy('brands.id', 'brands.name', 'brands.logo', 'users.name', 'users.email')
            ->orderByDesc('total_sales')
            ->take(5)
            ->get();

        // All vendors list with details
        $vendorsList = Brand::with('vendorProfile.user')
            ->withCount('items')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($brand) {
                // Calculate total sales for this brand
                $sales = OrderItem::whereHas('item', function($q) use ($brand) {
                    $q->where('brand_id', $brand->id);
                })->whereHas('order', function($q) {
                    $q->where('status', '!=', 'cancelled');
                })->selectRaw('SUM(price * quantity) as total')->value('total') ?? 0;
                
                $brand->total_sales = $sales;
                return $brand;
            });

        return view('admin.dashboard', compact(
            'categoriesCount',
            'itemsCount',
            'itemsBreakdown',
            'ordersCount',
            'pendingOrdersCount',
            'totalRevenue',
            'usersCount',
            'suspendedUsersCount',
            'adminsCount',
            'vendorsCount',
            'topVendors',
            'vendorsList'
        ));
    }
}
