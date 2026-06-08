<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Report – Elixira Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f8f9fa; }
        .report-header {
            background: linear-gradient(135deg, #1a4a3a 0%, #2d7a5f 100%);
            color: white;
            padding: 2rem;
            border-radius: 12px;
            margin-bottom: 2rem;
        }
        .report-header h1 { font-size: 1.8rem; font-weight: 700; margin: 0; }
        .report-header p { margin: 0.25rem 0 0; opacity: 0.8; font-size: 0.9rem; }
        @media print {
            .d-print-none { display: none !important; }
            body { background: white !important; }
            .report-header { background: #1a4a3a !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .table { font-size: 0.8rem; }
            .badge { border: 1px solid #333 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body class="p-4">

    <div class="d-print-none mb-3 d-flex gap-2">
        <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-secondary btn-sm">← Back to Reports</a>
        <button onclick="window.print()" class="btn btn-success btn-sm">🖨 Print / Save PDF</button>
    </div>

    <div class="report-header">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h1>🛍 Products Report</h1>
                <p>Generated on {{ now()->format('d M Y, H:i') }} · Elixira Admin</p>
            </div>
            <div class="text-end">
                <div style="font-size: 2rem; font-weight: 700;">{{ $allProducts->count() }}</div>
                <div style="opacity: 0.8; font-size: 0.85rem;">Total Products</div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center p-3">
                <div class="fw-bold fs-4 text-success">{{ $allProducts->where('stock', '>', 0)->count() }}</div>
                <div class="text-muted small">In Stock</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center p-3">
                <div class="fw-bold fs-4 text-danger">{{ $outOfStock->count() }}</div>
                <div class="text-muted small">Out of Stock</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center p-3">
                <div class="fw-bold fs-4 text-warning">{{ $lowStock->count() }}</div>
                <div class="text-muted small">Low Stock (≤ 5)</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center p-3">
                <div class="fw-bold fs-4 text-primary">{{ $allProducts->where('status', 'pending')->count() }}</div>
                <div class="text-muted small">Pending Approval</div>
            </div>
        </div>
    </div>

    @if($topProducts->count() > 0)
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; overflow: hidden;">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold">⭐ Top 5 Best Selling Products</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered align-middle mb-0">
                <thead class="table-warning">
                    <tr>
                        <th>Rank</th>
                        <th>Product Name</th>
                        <th>Category</th>
                        <th class="text-center">Units Sold</th>
                        <th class="text-center">Stock Left</th>
                        <th class="text-end">Revenue (﷼)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topProducts as $i => $item)
                    <tr>
                        <td class="fw-bold text-muted">#{{ $i + 1 }}</td>
                        <td class="fw-bold">{{ $item->name }}</td>
                        <td>{{ $item->category->name ?? 'N/A' }}</td>
                        <td class="text-center"><span class="badge bg-success">{{ $item->total_sold }}</span></td>
                        <td class="text-center">
                            <span class="badge {{ $item->stock > 0 ? 'bg-info text-dark' : 'bg-danger' }}">{{ $item->stock }}</span>
                        </td>
                        <td class="text-end fw-bold">{{ number_format($item->total_sold * $item->price, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; overflow: hidden;">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold">📋 All Products — Detailed Table</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#ID</th>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Brand</th>
                            <th class="text-center">Stock</th>
                            <th class="text-center">Status</th>
                            <th class="text-end">Price (﷼)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($allProducts as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td class="fw-bold">{{ $product->name }}</td>
                            <td>{{ $product->category->name ?? '—' }}</td>
                            <td>{{ $product->brand->name ?? '—' }}</td>
                            <td class="text-center">
                                <span class="badge {{ $product->stock <= 0 ? 'bg-danger' : ($product->stock <= 5 ? 'bg-warning text-dark' : 'bg-success') }}">
                                    {{ $product->stock }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge
                                    @if($product->status === 'approved') bg-success
                                    @elseif($product->status === 'pending') bg-warning text-dark
                                    @elseif($product->status === 'rejected' || $product->status === 'rejected_with_notes') bg-danger
                                    @else bg-secondary @endif">
                                    {{ ucfirst(str_replace('_', ' ', $product->status)) }}
                                </span>
                            </td>
                            <td class="text-end">{{ number_format($product->price, 2) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center py-4 text-muted">No products found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="d-print-none text-center mt-4">
        <button onclick="window.print()" class="btn btn-dark px-5">🖨 Print / Save as PDF</button>
    </div>
</body>
</html>
