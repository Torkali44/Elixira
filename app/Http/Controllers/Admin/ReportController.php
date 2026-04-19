<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Item;
use App\Models\User;
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
            ->map(function($sold) {
                $item = Item::find($sold->item_id);
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
}
