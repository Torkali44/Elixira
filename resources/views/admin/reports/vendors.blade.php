<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendors Report – Elixira Admin</title>
    @include('partials.favicon')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f8f9fa; }
        .report-header {
            background: linear-gradient(135deg, #3a1a4a 0%, #6b2fa0 100%);
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
            .report-header { background: #3a1a4a !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .table { font-size: 0.8rem; }
            .badge { border: 1px solid #333 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
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
                <h1>🏪 Vendors Report</h1>
                <p>Generated on {{ now()->format('d M Y, H:i') }} · Elixira Admin</p>
            </div>
            <div class="text-end">
                <div style="font-size: 2rem; font-weight: 700;">{{ $allVendors->count() }}</div>
                <div style="opacity: 0.8; font-size: 0.85rem;">Total Vendor Profiles</div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center p-3">
                <div class="fw-bold fs-4 text-success">{{ $allVendors->where('status', 'approved')->count() }}</div>
                <div class="text-muted small">Approved</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center p-3">
                <div class="fw-bold fs-4 text-warning">{{ $allVendors->where('status', 'pending')->count() }}</div>
                <div class="text-muted small">Pending</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center p-3">
                <div class="fw-bold fs-4 text-danger">{{ $allVendors->whereIn('status', ['rejected', 'rejected_with_notes'])->count() }}</div>
                <div class="text-muted small">Rejected</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center p-3">
                <div class="fw-bold fs-4 text-secondary">{{ $allVendors->where('status', 'draft')->count() }}</div>
                <div class="text-muted small">Draft</div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; overflow: hidden;">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold">📋 All Vendor Profiles — Detailed Table</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#ID</th>
                            <th>Brand Name</th>
                            <th>Vendor (User)</th>
                            <th>Email</th>
                            <th>Countries</th>
                            <th class="text-center">Status</th>
                            <th>Applied At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($allVendors as $vendor)
                        <tr>
                            <td>{{ $vendor->id }}</td>
                            <td class="fw-bold">{{ $vendor->brand_name }}</td>
                            <td>{{ $vendor->user->name ?? '—' }}</td>
                            <td>{{ $vendor->user->email ?? '—' }}</td>
                            <td>
                                @if($vendor->service_countries)
                                    @php $countries = is_array($vendor->service_countries) ? $vendor->service_countries : json_decode($vendor->service_countries, true); @endphp
                                    {{ implode(', ', array_slice((array)$countries, 0, 3)) }}{{ count((array)$countries) > 3 ? '...' : '' }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge
                                    @if($vendor->status === 'approved') bg-success
                                    @elseif($vendor->status === 'pending') bg-warning text-dark
                                    @elseif($vendor->status === 'draft') bg-secondary
                                    @else bg-danger @endif">
                                    {{ ucfirst(str_replace('_', ' ', $vendor->status)) }}
                                </span>
                            </td>
                            <td>{{ $vendor->created_at->format('Y-m-d') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center py-4 text-muted">No vendors found.</td></tr>
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
