@extends('layouts.admin')

@section('content')
    <style>
        /* Dark/Light mode support for cards */
        .card {
            background-color: var(--theme-card-bg) !important;
            color: var(--theme-text);
        }

        h2, h3, h4, h5 {
            color: var(--theme-text);
        }

        .text-muted {
            color: var(--theme-text-muted) !important;
        }

        /* Report Cards Grid */
        .reports-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }

        .report-card {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 180px;
            border-radius: 12px;
            border: none;
            text-decoration: none !important;
            color: inherit !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            padding: 2rem 1rem;
        }

        .report-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
        }

        .report-card i {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .report-card .card-name {
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
            text-align: center;
        }

        .report-card .card-description {
            font-size: 0.85rem;
            text-align: center;
            opacity: 0.8;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .reports-grid {
                grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .reports-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }

            .report-card {
                min-height: 160px;
                padding: 1.5rem 1rem;
            }

            .report-card i {
                font-size: 2rem;
                margin-bottom: 0.8rem;
            }

            .report-card .card-name {
                font-size: 0.95rem;
            }
        }

        @media (max-width: 480px) {
            .reports-grid {
                grid-template-columns: 1fr;
            }
        }

        @media print {
            body.print-orders-only * {
                visibility: hidden;
            }

            body.print-orders-only #print-orders-report,
            body.print-orders-only #print-orders-report * {
                visibility: visible;
            }

            body.print-orders-only #print-orders-report {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                margin: 0;
                padding: 0;
            }

            body.print-orders-only #print-orders-report .card {
                border: 1px solid #ddd !important;
                box-shadow: none !important;
            }

            body.print-orders-only {
                background: white !important;
                color: black !important;
            }

            body.print-orders-only .table-responsive {
                overflow: visible !important;
            }

            body.print-orders-only .badge {
                border: 1px solid #000;
                color: #000 !important;
                background: transparent !important;
            }
        }
    </style>
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h2 class="mb-1">{{ __('admin.reports.title') }}</h2>
            <p class="text-muted">{{ __('admin.reports.subtitle') }}</p>
        </div>
        <div class="d-print-none">
            <button type="button" onclick="printOrdersReport()" class="btn btn-primary"><i class="fas fa-print me-2"></i> {{ __('admin.reports.print_button') }}</button>
        </div>
    </div>

    {{-- Printable Report Quick-Access Cards --}}
    <div class="mb-3 d-print-none">
        <h5 class="fw-bold text-muted text-uppercase mb-4" style="font-size: 0.8rem; letter-spacing: 1px;">
            <i class="fas fa-file-pdf me-2 text-danger"></i> {{ __('admin.reports.pdf_section_title') }}
        </h5>
    </div>

    <div class="reports-grid d-print-none">
        <!-- Orders Card -->
        <a href="{{ route('admin.reports.orders') }}" target="_blank" class="report-card" style="border-left: 5px solid #007bff;">
            <i class="fas fa-shopping-bag" style="color: #007bff;"></i>
            <div class="card-name">{{ __('admin.reports.orders.name') }}</div>
            <div class="card-description">{{ __('admin.reports.orders.description') }}</div>
        </a>

        <!-- Products Card -->
        <a href="{{ route('admin.reports.products') }}" target="_blank" class="report-card" style="border-left: 5px solid #28a745;">
            <i class="fas fa-boxes" style="color: #28a745;"></i>
            <div class="card-name">{{ __('admin.reports.products.name') }}</div>
            <div class="card-description">{{ __('admin.reports.products.description') }}</div>
        </a>

        <!-- Vendors Card -->
        <a href="{{ route('admin.reports.vendors') }}" target="_blank" class="report-card" style="border-left: 5px solid #6f42c1;">
            <i class="fas fa-store" style="color: #6f42c1;"></i>
            <div class="card-name">{{ __('admin.reports.vendors.name') }}</div>
            <div class="card-description">{{ __('admin.reports.vendors.description') }}</div>
        </a>

        <!-- Brands Card -->
        <a href="{{ route('admin.reports.brands') }}" target="_blank" class="report-card" style="border-left: 5px solid #fd7e14;">
            <i class="fas fa-tags" style="color: #fd7e14;"></i>
            <div class="card-name">{{ __('admin.reports.brands.name') }}</div>
            <div class="card-description">{{ __('admin.reports.brands.description') }}</div>
        </a>

        <!-- Financials Card -->
        <a href="{{ route('admin.reports.financials') }}" target="_blank" class="report-card" style="border-left: 5px solid #17a2b8;">
            <i class="fas fa-coins" style="color: #17a2b8;"></i>
            <div class="card-name">{{ __('admin.reports.financials.name') }}</div>
            <div class="card-description">{{ __('admin.reports.financials.description') }}</div>
        </a>
    </div>


    <div class="row g-4 mb-5">
        <!-- Revenue Card -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 5px solid #28a745 !important;">
                <div class="card-body">
                    <small class="text-muted text-uppercase fw-bold">{{ __('admin.reports.revenue.title') }}</small>
                    <h3 class="mt-2">﷼ {{ number_format($totalRevenue, 2) }}</h3>
                    <p class="mb-0 text-success small"><i class="fas fa-wallet me-1"></i> {{ __('admin.reports.revenue.description') }}</p>
                </div>
            </div>
        </div>
        <!-- Orders Card -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 5px solid #007bff !important;">
                <div class="card-body">
                    <small class="text-muted text-uppercase fw-bold">{{ __('admin.reports.total_orders.title') }}</small>
                    <h3 class="mt-2">{{ $totalOrders }}</h3>
                    <p class="mb-0 text-muted small"><i class="fas fa-shopping-bag me-1"></i> {{ __('admin.reports.total_orders.description') }}</p>
                </div>
            </div>
        </div>
        <!-- Users Card -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 5px solid #ffc107 !important;">
                <div class="card-body">
                    <small class="text-muted text-uppercase fw-bold">{{ __('admin.reports.total_users.title') }}</small>
                    <h3 class="mt-2">{{ $totalUsers }}</h3>
                    <p class="mb-0 text-muted small"><i class="fas fa-users me-1"></i> {{ __('admin.reports.total_users.description') }}</p>
                </div>
            </div>
        </div>
        <!-- Stock Card -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 5px solid #dc3545 !important;">
                <div class="card-body">
                    <small class="text-muted text-uppercase fw-bold">{{ __('admin.reports.out_of_stock.title') }}</small>
                    <h3 class="mt-2">{{ $outOfStock->count() }}</h3>
                    <p class="mb-0 text-danger small"><i class="fas fa-exclamation-triangle me-1"></i> {{ __('admin.reports.out_of_stock.description') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Top Products -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-crown text-warning me-2"></i> Top Selling Products</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th class="text-center">Sold</th>
                                    <th class="text-center">Stock</th>
                                    <th class="text-end">Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topProducts as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($item->image)
                                                    <img src="{{ asset('storage/' . $item->image) }}" class="rounded me-2" alt=""
                                                        width="40">
                                                @endif
                                                <span class="fw-bold">{{ $item->name }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $item->category->name }}</td>
                                        <td class="text-center"><span
                                                class="badge bg-light text-dark px-3">{{ $item->total_sold }}</span></td>
                                        <td class="text-center">
                                            <span
                                                class="badge {{ $item->stock > 0 ? 'bg-success' : 'bg-danger' }}">{{ $item->stock }}</span>
                                        </td>
                                        <td class="text-end fw-bold">﷼ {{ number_format($item->total_sold * $item->price, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Orders Breakdown -->
            <!-- <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0"><i class="fas fa-tasks me-2"></i> Orders by Status</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            @foreach($ordersByStatus as $status)
                            <div class="col-6 col-md-3 mb-3">
                                <div class="p-3 bg-light rounded">
                                    <h4 class="mb-0">{{ $status->count }}</h4>
                                    <small class="text-muted text-uppercase">{{ $status->status }}</small>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div> -->
        </div>

        <!-- Right Column (Alerts & Recent) -->
        <div class="col-lg-4">
            <!-- Stock Alerts -->
            @if($lowStock->count() > 0 || $outOfStock->count() > 0)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-danger text-white py-3">
                        <h5 class="mb-0"><i class="fas fa-bell me-2"></i> Inventory Alerts</h5>
                    </div>
                    <div class="card-body">
                        @foreach($outOfStock as $item)
                            <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                                <div>
                                    <strong class="text-danger">{{ $item->name }}</strong><br>
                                    <small class="text-muted">Out of stock</small>
                                </div>
                                <a href="{{ route('admin.items.edit', $item->id) }}"
                                    class="btn btn-sm btn-outline-primary">Update</a>
                            </div>
                        @endforeach

                        @foreach($lowStock as $item)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <strong class="text-warning">{{ $item->name }}</strong><br>
                                    <small class="text-muted">Low stock: {{ $item->stock }} left</small>
                                </div>
                                <a href="{{ route('admin.items.edit', $item->id) }}"
                                    class="btn btn-sm btn-outline-primary">Update</a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- All Users -->
            <div class="card border-0 shadow-sm" style="max-height: 500px; overflow-y: auto;">
                <div class="card-header bg-white py-3 sticky-top">
                    <h5 class="mb-0"><i class="fas fa-users me-2"></i> All Users</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @foreach($allUsers as $user)
                            <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                <div>
                                    <div class="fw-bold">{{ $user->name }}
                                        {!! $user->role === 'admin' ? '<span class="badge bg-primary ms-1">Admin</span>' : '' !!}
                                    </div>
                                    <small class="text-muted">{{ $user->email }}</small>
                                </div>
                                <div class="d-flex align-items-center">
                                    <small
                                        class="text-muted me-3 d-none d-md-block">{{ $user->created_at->format('Y-m-d') }}</small>
                                    @if($user->role !== 'admin')
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                            data-confirm="Are you sure you want to delete this user?">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger d-print-none"><i
                                                    class="fas fa-trash"></i></button>
                                        </form>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <!-- Detailed Orders Table (Great for Printing) -->
        <div class="col-12 mt-5" id="print-orders-report">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-table me-2"></i> Detailed Orders & Revenue History</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Order ID</th>
                                    <th>Date</th>
                                    <th>Customer Name</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                    <th class="text-end">Total Amount (Profit)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($allOrders as $order)
                                    <tr>
                                        <td>#{{ $order->id }}</td>
                                        <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                                        <td>{{ $order->customer_name }}</td>
                                        <td><x-phone-flag :phone="$order->customer_phone" /></td>
                                        <td>
                                            @if($order->status == 'completed')
                                                <span class="badge bg-success">Completed</span>
                                            @elseif($order->status == 'cancelled')
                                                <span class="badge bg-danger">Cancelled</span>
                                            @else
                                                <span class="badge bg-warning text-dark">{{ ucfirst($order->status) }}</span>
                                            @endif
                                        </td>
                                        <td class="text-end fw-bold">﷼ {{ number_format($order->total_amount, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">No orders found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="table-light fw-bold">
                                <tr>
                                    <td colspan="5" class="text-end">Total Revenue (Completed/Pending):</td>
                                    <td class="text-end text-success">﷼ {{ number_format($totalRevenue, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function printOrdersReport() {
            document.body.classList.add('print-orders-only');
            window.print();
            window.addEventListener('afterprint', function cleanup() {
                document.body.classList.remove('print-orders-only');
                window.removeEventListener('afterprint', cleanup);
            });
        }
    </script>
@endsection