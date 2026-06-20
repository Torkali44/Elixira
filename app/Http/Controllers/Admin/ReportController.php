<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Item;
use App\Models\Order;
use App\Models\User;
use App\Models\VendorProfile;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        // 1. Orders Stats
        $totalOrders = Order::count();
        $ordersByStatus = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total_amount');

        // 2. Products Stats (Stock)
        $topProducts = DB::table('order_items')
            ->select('item_id', DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('item_id')
            ->orderBy('total_sold', 'desc')
            ->take(5)
            ->get()
            ->map(function ($sold) {
                $item = Item::with('category')->find($sold->item_id);
                if ($item) {
                    $item->total_sold = $sold->total_sold;
                }

                return $item;
            })->filter();

        $outOfStock = Item::where('stock', '<=', 0)->get();
        $lowStock = Item::where('stock', '>', 0)->where('stock', '<', 5)->get();

        // 3. User Stats
        $totalUsers = User::count();
        $allUsers = User::latest()->get();

        // 4. All Orders for Detailed Report
        $allOrders = Order::latest()->get();

        return view('admin.reports.index', compact(
            'totalOrders', 'ordersByStatus', 'totalRevenue',
            'topProducts', 'outOfStock', 'lowStock',
            'totalUsers', 'allUsers', 'allOrders'
        ));
    }

    /** Printable: Orders Report */
    public function orders()
    {
        $totalOrders = Order::count();
        $ordersByStatus = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total_amount');
        $allOrders = Order::latest()->get();

        return view('admin.reports.orders', compact(
            'totalOrders', 'ordersByStatus', 'totalRevenue', 'allOrders'
        ));
    }

    /** Printable: Products Report */
    public function products()
    {
        $topProducts = DB::table('order_items')
            ->select('item_id', DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('item_id')
            ->orderBy('total_sold', 'desc')
            ->take(5)
            ->get()
            ->map(function ($sold) {
                $item = Item::with('category')->find($sold->item_id);
                if ($item) {
                    $item->total_sold = $sold->total_sold;
                }

                return $item;
            })->filter();

        $outOfStock = Item::where('stock', '<=', 0)->get();
        $lowStock = Item::where('stock', '>', 0)->where('stock', '<', 5)->get();
        $allProducts = Item::with(['category', 'brand'])->latest()->get();

        return view('admin.reports.products', compact(
            'topProducts', 'outOfStock', 'lowStock', 'allProducts'
        ));
    }

    /** Printable: Vendors Report */
    public function vendors()
    {
        $allVendors = VendorProfile::with('user')->latest()->get();

        return view('admin.reports.vendors', compact('allVendors'));
    }

    /** Printable: Brands Report */
    public function brands()
    {
        $allBrands = Brand::with(['vendorProfile.user', 'items'])->latest()->get();

        return view('admin.reports.brands', compact('allBrands'));
    }

    /** Printable: Financials Report (Incoming & Outgoing) */
    public function financials()
    {
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total_amount');
        $cancelledOrdersCount = Order::where('status', 'cancelled')->count();

        // Monthly revenue breakdown
        $revenueByMonth = Order::where('status', '!=', 'cancelled')
            ->selectRaw("strftime('%Y-%m', created_at) as month, COUNT(*) as order_count, SUM(total_amount) as revenue")
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->take(12)
            ->get();

        // 1. Item sales per brand (outgoing payout)
        $itemSales = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('items', 'order_items.item_id', '=', 'items.id')
            ->join('brands', 'items.brand_id', '=', 'brands.id')
            ->join('vendor_profiles', 'brands.vendor_profile_id', '=', 'vendor_profiles.id')
            ->join('users', 'vendor_profiles.user_id', '=', 'users.id')
            ->where('orders.status', '!=', 'cancelled')
            ->select(
                'brands.id as brand_id',
                'brands.name as brand_name',
                'users.name as vendor_name',
                DB::raw('SUM(order_items.quantity) as items_sold'),
                DB::raw('SUM(order_items.price * order_items.quantity) as total_payout')
            )
            ->groupBy('brands.id', 'brands.name', 'users.name')
            ->get();

        // 2. Package sales per brand (outgoing payout)
        $packageSales = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('packages', 'order_items.package_id', '=', 'packages.id')
            ->join('brands', 'packages.brand_id', '=', 'brands.id')
            ->join('vendor_profiles', 'brands.vendor_profile_id', '=', 'vendor_profiles.id')
            ->join('users', 'vendor_profiles.user_id', '=', 'users.id')
            ->where('orders.status', '!=', 'cancelled')
            ->select(
                'brands.id as brand_id',
                'brands.name as brand_name',
                'users.name as vendor_name',
                DB::raw('SUM(order_items.quantity) as items_sold'),
                DB::raw('SUM(order_items.price * order_items.quantity) as total_payout')
            )
            ->groupBy('brands.id', 'brands.name', 'users.name')
            ->get();

        // Combine item and package sales in PHP
        $combined = [];
        foreach ($itemSales as $sale) {
            $brandId = $sale->brand_id;
            $combined[$brandId] = [
                'brand_name' => $sale->brand_name,
                'vendor_name' => $sale->vendor_name,
                'items_sold' => (int) $sale->items_sold,
                'total_payout' => (float) $sale->total_payout,
            ];
        }

        foreach ($packageSales as $sale) {
            $brandId = $sale->brand_id;
            if (isset($combined[$brandId])) {
                $combined[$brandId]['items_sold'] += (int) $sale->items_sold;
                $combined[$brandId]['total_payout'] += (float) $sale->total_payout;
            } else {
                $combined[$brandId] = [
                    'brand_name' => $sale->brand_name,
                    'vendor_name' => $sale->vendor_name,
                    'items_sold' => (int) $sale->items_sold,
                    'total_payout' => (float) $sale->total_payout,
                ];
            }
        }

        $vendorPayouts = collect(array_map(fn($item) => (object) $item, $combined))
            ->sortByDesc('total_payout');

        $totalVendorPayouts = $vendorPayouts->sum('total_payout');

        // Detailed product/package breakdown
        $productRevenue = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', '!=', 'cancelled')
            ->whereNotNull('order_items.item_id')
            ->sum(DB::raw('order_items.price * order_items.quantity'));

        $packageRevenue = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', '!=', 'cancelled')
            ->whereNotNull('order_items.package_id')
            ->sum(DB::raw('order_items.price * order_items.quantity'));

        $productsSold = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', '!=', 'cancelled')
            ->whereNotNull('order_items.item_id')
            ->sum('order_items.quantity');

        $packagesSold = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', '!=', 'cancelled')
            ->whereNotNull('order_items.package_id')
            ->sum('order_items.quantity');

        return view('admin.reports.financials', compact(
            'totalRevenue', 'cancelledOrdersCount',
            'revenueByMonth', 'vendorPayouts', 'totalVendorPayouts',
            'productRevenue', 'packageRevenue', 'productsSold', 'packagesSold'
        ));
    }
}
