<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Sales Report – Elixira Vendor</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f8f9fa; }
        .report-header {
            background: linear-gradient(135deg, #0b161c 0%, #13252D 100%);
            color: white;
            padding: 2rem;
            border-radius: 12px;
            margin-bottom: 2rem;
        }
        .report-header h1 { font-size: 1.8rem; font-weight: 700; margin: 0; }
        .report-header p { margin: 0.25rem 0 0; opacity: 0.8; font-size: 0.9rem; }
        .stat-card { border-radius: 12px; border: none; }
        @media print {
            .d-print-none { display: none !important; }
            body { background: white !important; }
            .report-header { background: #13252D !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .table { font-size: 0.8rem; }
            .badge { border: 1px solid #333 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body class="p-4">

    <div class="d-print-none mb-3 d-flex gap-2">
        <a href="{{ route('vendor.dashboard') }}" class="btn btn-outline-secondary btn-sm">← Back to Dashboard</a>
        <button onclick="window.print()" class="btn btn-dark btn-sm">🖨 Print / Save PDF</button>
    </div>

    <div class="report-header">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <h1>📊 Sales & Orders Report</h1>
                <p>Brand: <strong>{{ $brandName }}</strong> · Generated on {{ now()->format('d M Y, H:i') }}</p>
            </div>
            <div class="text-end">
                <div style="font-size: 2rem; font-weight: 700;">﷼ {{ number_format($stats['total_revenue'], 2) }}</div>
                <div style="opacity: 0.8; font-size: 0.85rem;">Total Revenue</div>
            </div>
        </div>
    </div>

    {{-- Summary Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card stat-card shadow-sm text-center p-3" style="border-left: 4px solid #28a745;">
                <div class="fw-bold fs-4 text-success">{{ $stats['total_orders'] }}</div>
                <div class="text-muted small">Total Orders</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card shadow-sm text-center p-3" style="border-left: 4px solid #007bff;">
                <div class="fw-bold fs-4 text-primary">{{ $stats['items_sold'] }}</div>
                <div class="text-muted small">Items Sold</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card shadow-sm text-center p-3" style="border-left: 4px solid #ffc107;">
                <div class="fw-bold fs-4 text-warning">{{ $stats['unique_customers'] }}</div>
                <div class="text-muted small">Unique Customers</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card shadow-sm text-center p-3" style="border-left: 4px solid #17a2b8;">
                <div class="fw-bold fs-4 text-info">{{ $stats['approved_items'] }}</div>
                <div class="text-muted small">Active Products</div>
            </div>
        </div>
    </div>

    {{-- Top Products --}}
    @if($topProducts->count() > 0)
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; overflow: hidden;">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold">⭐ Top Selling Products</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Rank</th>
                        <th>Product</th>
                        <th class="text-center">Units Sold</th>
                        <th class="text-end">Revenue (﷼)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topProducts as $i => $tp)
                    <tr>
                        <td class="fw-bold text-muted">#{{ $i + 1 }}</td>
                        <td class="fw-bold">{{ $tp->item->name ?? 'Unknown' }}</td>
                        <td class="text-center"><span class="badge bg-success">{{ $tp->total_sold }}</span></td>
                        <td class="text-end fw-bold">﷼ {{ number_format($tp->total_revenue, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- All Orders Table --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; overflow: hidden;">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold">📦 All Orders Containing Your Products</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Order #</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Products</th>
                            <th class="text-center">Status</th>
                            <th class="text-end">Your Revenue (﷼)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($allOrders as $order)
                        @php
                            $vendorTotal = $order->orderItems->sum(fn($oi) => $oi->price * $oi->quantity);
                        @endphp
                        <tr>
                            <td class="fw-bold">#{{ $order->id }}</td>
                            <td>{{ $order->created_at->format('Y-m-d') }}</td>
                            <td>{{ $order->customer_name }}</td>
                            <td>
                                @foreach($order->orderItems as $oi)
                                    <div class="small">{{ $oi->item->name ?? 'N/A' }} ×{{ $oi->quantity }}</div>
                                @endforeach
                            </td>
                            <td class="text-center">
                                <span class="badge
                                    @if($order->status === 'delivered') bg-success
                                    @elseif($order->status === 'cancelled') bg-danger
                                    @elseif($order->status === 'pending') bg-warning text-dark
                                    @else bg-info text-dark @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="text-end fw-bold">﷼ {{ number_format($vendorTotal, 2) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center py-4 text-muted">No orders found for your products.</td></tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-dark">
                        <tr>
                            <td colspan="5" class="text-end fw-bold">Total Revenue:</td>
                            <td class="text-end fw-bold">﷼ {{ number_format($stats['total_revenue'], 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="d-print-none text-center mt-4">
        <button onclick="window.print()" class="btn btn-dark px-5">🖨 Print / Save as PDF</button>
    </div>
</body>
</html>
