<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brands Report – Elixira Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f8f9fa; }
        .report-header {
            background: linear-gradient(135deg, #4a2a1a 0%, #c0641a 100%);
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
            .report-header { background: #4a2a1a !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .table { font-size: 0.8rem; }
            .badge { border: 1px solid #333 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body class="p-4">

    <div class="d-print-none mb-3 d-flex gap-2">
        <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-secondary btn-sm">← Back to Reports</a>
        <button onclick="window.print()" class="btn btn-warning btn-sm">🖨 Print / Save PDF</button>
    </div>

    <div class="report-header">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h1>🏷 Brands Report</h1>
                <p>Generated on {{ now()->format('d M Y, H:i') }} · Elixira Admin</p>
            </div>
            <div class="text-end">
                <div style="font-size: 2rem; font-weight: 700;">{{ $allBrands->count() }}</div>
                <div style="opacity: 0.8; font-size: 0.85rem;">Total Brands</div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4">
            <div class="card border-0 shadow-sm text-center p-3">
                <div class="fw-bold fs-4 text-success">{{ $allBrands->where('is_active', true)->count() }}</div>
                <div class="text-muted small">Active Brands</div>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="card border-0 shadow-sm text-center p-3">
                <div class="fw-bold fs-4 text-danger">{{ $allBrands->where('is_active', false)->count() }}</div>
                <div class="text-muted small">Inactive Brands</div>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="card border-0 shadow-sm text-center p-3">
                <div class="fw-bold fs-4 text-primary">{{ $allBrands->sum(fn($b) => $b->items->count()) }}</div>
                <div class="text-muted small">Total Products Listed</div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; overflow: hidden;">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold">📋 All Brands — Detailed Table</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#ID</th>
                            <th>Brand Name</th>
                            <th>Vendor Owner</th>
                            <th>Instagram</th>
                            <th class="text-center">Products</th>
                            <th class="text-center">Status</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($allBrands as $brand)
                        <tr>
                            <td>{{ $brand->id }}</td>
                            <td class="fw-bold">{{ $brand->name }}</td>
                            <td>{{ $brand->vendorProfile->user->name ?? '—' }}</td>
                            <td>
                                @if($brand->instagram_link)
                                    <a href="{{ $brand->instagram_link }}" target="_blank" class="text-decoration-none small">
                                        📸 Instagram
                                    </a>
                                @else
                                    —
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info text-dark">{{ $brand->items->count() }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $brand->is_active ? 'bg-success' : 'bg-danger' }}">
                                    {{ $brand->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>{{ $brand->created_at->format('Y-m-d') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center py-4 text-muted">No brands found.</td></tr>
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
