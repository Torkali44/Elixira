<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financial Report – Elixira Admin</title>
    @include('partials.favicon')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f8f9fa; }
        .report-header {
            background: linear-gradient(135deg, #0d2b45 0%, #1a5276 100%);
            color: white;
            padding: 2rem;
            border-radius: 12px;
            margin-bottom: 2rem;
        }
        .report-header h1 { font-size: 1.8rem; font-weight: 700; margin: 0; }
        .report-header p { margin: 0.25rem 0 0; opacity: 0.8; font-size: 0.9rem; }
        .income-row { background: rgba(40, 167, 69, 0.05); }
        .expense-row { background: rgba(220, 53, 69, 0.05); }
        @media print {
            .d-print-none { display: none !important; }
            body { background: white !important; }
            .report-header { background: #0d2b45 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .table { font-size: 0.8rem; }
            .badge { border: 1px solid #333 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .income-row, .expense-row { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
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
                <h1>💰 Financial Report (Incoming & Outgoing)</h1>
                <p>Generated on {{ now()->format('d M Y, H:i') }} · Elixira Admin</p>
            </div>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center p-3" style="border-left: 5px solid #28a745;">
                <div class="fw-bold fs-5 text-success">﷼ {{ number_format($totalRevenue, 2) }}</div>
                <div class="text-muted small">Total Incoming Revenue</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center p-3" style="border-left: 5px solid #dc3545;">
                <div class="fw-bold fs-5 text-danger">﷼ {{ number_format($totalVendorPayouts, 2) }}</div>
                <div class="text-muted small">Est. Vendor Payouts (Outgoing)</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center p-3" style="border-left: 5px solid #007bff;">
                <div class="fw-bold fs-5 text-primary">﷼ {{ number_format($totalRevenue - $totalVendorPayouts, 2) }}</div>
                <div class="text-muted small">Net Margin (Est.)</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center p-3" style="border-left: 5px solid #ffc107;">
                <div class="fw-bold fs-5 text-warning">{{ $cancelledOrdersCount }}</div>
                <div class="text-muted small">Cancelled Orders</div>
            </div>
        </div>
    </div>

    {{-- Detailed Breakdown --}}
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm p-3" style="border-left: 5px solid #007bff; border-radius: 10px;">
                <h6 class="text-muted fw-bold mb-2">📦 Product Sales Breakdown</h6>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-bold fs-5 text-primary">﷼ {{ number_format($productRevenue, 2) }}</div>
                        <div class="text-muted small">Total Product Revenue</div>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold fs-5">{{ number_format($productsSold) }}</div>
                        <div class="text-muted small">Products Sold</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm p-3" style="border-left: 5px solid #fd7e14; border-radius: 10px;">
                <h6 class="text-muted fw-bold mb-2">🎁 Package Sales Breakdown</h6>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-bold fs-5 text-warning" style="color: #fd7e14 !important;">﷼ {{ number_format($packageRevenue, 2) }}</div>
                        <div class="text-muted small">Total Package Revenue</div>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold fs-5">{{ number_format($packagesSold) }}</div>
                        <div class="text-muted small">Packages Sold</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Revenue by Month --}}
    @if($revenueByMonth->count() > 0)
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; overflow: hidden;">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold">📅 Monthly Revenue Breakdown</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Month</th>
                        <th class="text-center">Orders</th>
                        <th class="text-end">Revenue (﷼)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($revenueByMonth as $row)
                    <tr class="income-row">
                        <td class="fw-bold">{{ $row->month }}</td>
                        <td class="text-center">{{ $row->order_count }}</td>
                        <td class="text-end fw-bold text-success">﷼ {{ number_format($row->revenue, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <td colspan="2" class="text-end fw-bold">Total:</td>
                        <td class="text-end fw-bold text-success">﷼ {{ number_format($revenueByMonth->sum('revenue'), 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    @endif

    {{-- Vendor Payouts Table --}}
    @if($vendorPayouts->count() > 0)
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; overflow: hidden;">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold">🏪 Estimated Vendor Payouts (Outgoing)</h5>
            <small class="text-muted">Based on order items sold per vendor brand</small>
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Brand</th>
                        <th>Vendor Owner</th>
                        <th class="text-center">Items Sold</th>
                        <th class="text-end">Est. Payout (﷼)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vendorPayouts as $vp)
                    <tr class="expense-row">
                        <td class="fw-bold">{{ $vp->brand_name }}</td>
                        <td>{{ $vp->vendor_name }}</td>
                        <td class="text-center">{{ $vp->items_sold }}</td>
                        <td class="text-end fw-bold text-danger">﷼ {{ number_format($vp->total_payout, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <td colspan="3" class="text-end fw-bold">Total Outgoing:</td>
                        <td class="text-end fw-bold text-danger">﷼ {{ number_format($vendorPayouts->sum('total_payout'), 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    @endif

    <div class="d-print-none text-center mt-4">
        <button onclick="window.print()" class="btn btn-dark px-5">🖨 Print / Save as PDF</button>
    </div>
</body>
</html>
