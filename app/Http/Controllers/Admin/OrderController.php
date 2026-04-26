<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhere('user_code', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(20)->withQueryString();

        // Calculate statistics
        $stats = [
            'total' => Order::count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'pending' => Order::where('status', 'pending')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        $stats['cancellation_rate'] = $stats['total'] > 0 ? ($stats['cancelled'] / $stats['total']) * 100 : 0;

        // Average execution time for delivered orders (in hours)
        $avgExecutionTime = Order::where('status', 'delivered')
            ->whereNotNull('updated_at')
            ->get()
            ->avg(function($order) {
                return $order->created_at->diffInHours($order->updated_at);
            });
        
        $stats['avg_execution_time'] = round($avgExecutionTime ?? 0, 1);

        // Peak activity period (Hour of the day)
        $peakHour = Order::selectRaw('HOUR(created_at) as hour, COUNT(*) as count')
            ->groupBy('hour')
            ->orderByDesc('count')
            ->first();
        
        $stats['peak_hour'] = $peakHour ? $peakHour->hour . ':00' : 'N/A';

        // Most active day
        $peakDay = Order::selectRaw('DAYNAME(created_at) as day, COUNT(*) as count')
            ->groupBy('day')
            ->orderByDesc('count')
            ->first();
        
        $stats['peak_day'] = $peakDay ? $peakDay->day : 'N/A';

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    public function show(Order $order)
    {
        $order->load('orderItems.item');
        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,ready,delivered,cancelled',
        ]);

        $order->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'The order status has been successfully updated.');
    }
}
