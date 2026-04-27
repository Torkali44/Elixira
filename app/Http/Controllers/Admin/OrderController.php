<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $peaks = $this->orderPeakStats();
        $stats['peak_hour'] = $peaks['peak_hour'];
        $stats['peak_day'] = $peaks['peak_day'];

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

    /**
     * Peak hour/day use MySQL-only functions in the original form; Laravel Cloud often uses PostgreSQL.
     */
    private function orderPeakStats(): array
    {
        $driver = DB::connection()->getDriverName();

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            $peakHour = Order::query()
                ->selectRaw('HOUR(created_at) as hour, COUNT(*) as cnt')
                ->groupByRaw('HOUR(created_at)')
                ->orderByDesc('cnt')
                ->first();

            $peakDay = Order::query()
                ->selectRaw('DAYNAME(created_at) as day, COUNT(*) as cnt')
                ->groupByRaw('DAYNAME(created_at)')
                ->orderByDesc('cnt')
                ->first();
        } elseif ($driver === 'pgsql') {
            $peakHour = Order::query()
                ->selectRaw('EXTRACT(HOUR FROM created_at)::int as hour, COUNT(*) as cnt')
                ->groupByRaw('EXTRACT(HOUR FROM created_at)')
                ->orderByDesc('cnt')
                ->first();

            $peakDay = Order::query()
                ->selectRaw("TRIM(TO_CHAR(created_at, 'Day')) as day, COUNT(*) as cnt")
                ->groupByRaw("TRIM(TO_CHAR(created_at, 'Day'))")
                ->orderByDesc('cnt')
                ->first();
        } else {
            $dayExpr = "CASE CAST(strftime('%w', created_at) AS INTEGER) "
                ."WHEN 0 THEN 'Sunday' WHEN 1 THEN 'Monday' WHEN 2 THEN 'Tuesday' WHEN 3 THEN 'Wednesday' "
                ."WHEN 4 THEN 'Thursday' WHEN 5 THEN 'Friday' WHEN 6 THEN 'Saturday' END";

            $peakHour = Order::query()
                ->selectRaw("CAST(strftime('%H', created_at) AS INTEGER) as hour, COUNT(*) as cnt")
                ->groupByRaw("strftime('%H', created_at)")
                ->orderByDesc('cnt')
                ->first();

            $peakDay = Order::query()
                ->selectRaw("{$dayExpr} as day, COUNT(*) as cnt")
                ->groupByRaw($dayExpr)
                ->orderByDesc('cnt')
                ->first();
        }

        return [
            'peak_hour' => $peakHour ? $peakHour->hour . ':00' : 'N/A',
            'peak_day' => $peakDay ? $peakDay->day : 'N/A',
        ];
    }
}
