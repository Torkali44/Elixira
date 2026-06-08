<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders Report – Elixira Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f8f9fa; }
        .report-header {
            background: linear-gradient(135deg, #13252D 0%, #1e4057 100%);
            color: white;
            padding: 2rem;
            border-radius: 12px;
            margin-bottom: 2rem;
        }
        .report-header h1 { font-size: 1.8rem; font-weight: 700; margin: 0; }
        .report-header p { margin: 0.25rem 0 0; opacity: 0.8; font-size: 0.9rem; }
        .stat-card { border-radius: 12px; border: none; }
        .print-btn { display: inline-block; }
        @media print {
            .d-print-none { display: none !important; }
            body { background: white !important; }
            .report-header { background: #13252D !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .table { font-size: 0.8rem; }
            .badge { border: 1px solid #333 !important; color: #333 !important; background: transparent !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .stat-card { border: 1px solid #ddd !important; box-shadow: none !important; }
        }
    </style>
</head>
<body class="p-4">

    <div class="d-print-none mb-3 d-flex gap-2">
        <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-secondary btn-sm">← Back to Reports</a>
        <button onclick="window.print()" class="btn btn-primary btn-sm">🖨 Print / Save PDF</button>
    </div>

    <div class="report-header">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h1>📦 Orders Report</h1>
                <p>Generated on {{ now()->format('d M Y, H:i') }} · Elixira Admin</p>
            </div>
            <div class="text-end">
                <div style="font-size: 2rem; font-weight: 700;">{{ $totalOrders }}</div>
                <div style="opacity: 0.8; font-size: 0.85rem;">Total Orders</div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        @foreach($ordersByStatus as $s)
        <div class="col-6 col-md-2">
            <div class="card stat-card shadow-sm text-center p-3">
                <div class="fw-bold fs-4">{{ $s->count }}</div>
                <div class="text-muted small text-uppercase">{{ ucfirst($s->status) }}</div>
            </div>
        </div>
        @endforeach
        <div class="col-6 col-md-3">
            <div class="card stat-card shadow-sm text-center p-3" style="border-left: 4px solid #28a745;">
                <div class="fw-bold fs-5 text-success">﷼ {{ number_format($totalRevenue, 2) }}</div>
                <div class="text-muted small">Total Revenue (Non-cancelled)</div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px; overflow: hidden;">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold">All Orders — Detailed Table</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#ID</th>
                            <th>Date</th>
                            <th>Customer Name</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Status</th>
                            <th class="text-end">Amount (﷼)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($allOrders as $order)
                        <tr>
                            <td class="fw-bold">#{{ $order->id }}</td>
                            <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                            <td>{{ $order->customer_name }}</td>
                            <td>{{ $order->customer_phone }}</td>
                            <td>{{ $order->address }}</td>
                            <td>
                                <span class="badge
                                    @if($order->status == 'delivered' || $order->status == 'completed') bg-success
                                    @elseif($order->status == 'cancelled') bg-danger
                                    @elseif($order->status == 'pending') bg-warning text-dark
                                    @else bg-info text-dark @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="text-end fw-bold">{{ number_format($order->total_amount, 2) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center py-4 text-muted">No orders found.</td></tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-dark">
                        <tr>
                            <td colspan="6" class="text-end fw-bold">Total Revenue (Non-cancelled):</td>
                            <td class="text-end fw-bold">﷼ {{ number_format($totalRevenue, 2) }}</td>
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
