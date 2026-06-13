@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">{{ __('admin.orders.title') }}</h2>
        <div class="text-muted">
            <i class="fas fa-calendar-alt me-1"></i> {{ now()->format('F d, Y') }}
        </div>
    </div>

    {{-- Statistics Section --}}
    <div class="row g-3 mb-4">

        <div class="col">
            <div class="card shadow-sm border-0 bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">{{ __('admin.orders.total_orders') }}</h6>
                            <h3 class="mb-0">{{ $stats['total'] }}</h3>
                        </div>
                        <div class="fs-1 opacity-25">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card shadow-sm border-0 bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">{{ __('admin.orders.delivered') }}</h6>
                            <h3 class="mb-0">{{ $stats['delivered'] }}</h3>
                        </div>
                        <div class="fs-1 opacity-25">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card shadow-sm border-0 bg-warning text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">{{ __('admin.orders.pending') }}</h6>
                            <h3 class="mb-0">{{ $stats['pending'] }}</h3>
                        </div>
                        <div class="fs-1 opacity-25">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card shadow-sm border-0 bg-danger text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">{{ __('admin.orders.cancelled') }}</h6>
                            <h3 class="mb-0">{{ $stats['cancelled'] }}</h3>
                        </div>
                        <div class="fs-1 opacity-25">
                            <i class="fas fa-times-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card shadow-sm border-0 bg-info text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">{{ __('admin.orders.cancel_rate') }}</h6>
                            <h3 class="mb-0">
                                {{ number_format($stats['cancellation_rate'], 1) }}%
                            </h3>
                        </div>
                        <div class="fs-1 opacity-25">
                            <i class="fas fa-percentage"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-light p-3 me-3 text-primary">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">{{ __('admin.orders.avg_delivery_time') }}</h6>
                        <h4 class="mb-0">{{ $stats['avg_execution_time'] }} {{ __('admin.orders.hours') }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-light p-3 me-3 text-warning">
                        <i class="fas fa-bolt fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">{{ __('admin.orders.peak_hour') }}</h6>
                        <h4 class="mb-0">{{ $stats['peak_hour'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-light p-3 me-3 text-info">
                        <i class="fas fa-calendar-day fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">{{ __('admin.orders.most_active_day') }}</h6>
                        <h4 class="mb-0">{{ $stats['peak_day'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        $badge = function ($status) {
            return match ($status) {
                'pending' => 'warning',
                'confirmed' => 'info',
                'preparing' => 'primary',
                'ready' => 'success',
                'delivered' => 'success',
                'cancelled' => 'danger',
                default => 'secondary',
            };
        };
        $label = function ($status) {
            return match ($status) {
                'pending' => __('admin.orders.status_pending'),
                'confirmed' => __('admin.orders.status_confirmed'),
                'preparing' => __('admin.orders.status_preparing'),
                'ready' => __('admin.orders.status_ready'),
                'delivered' => __('admin.orders.status_delivered'),
                'cancelled' => __('admin.orders.status_cancelled'),
                default => $status,
            };
        };
    @endphp


    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-6">
                    <label for="search" class="form-label">{{ __('admin.orders.search') }}</label>
                    <input type="text" id="search" name="search" class="form-control"
                        placeholder="{{ __('admin.orders.search_placeholder') }}" value="{{ request('search') }}">
                </div>
                 <div class="col-md-2">
                <label for="role" class="form-label">{{ __('admin.orders.role') }}</label>
                <select id="role" name="role" class="form-select">
                    <option value="">{{ __('admin.orders.all_roles') }}</option>
                    <option value="admin" @selected(request('role') === 'admin')>{{ __('admin.orders.admin') }}</option>
                    <option value="user" @selected(request('role') === 'user')>{{ __('admin.orders.user') }}</option>
                    <option value="guest" @selected(request('role') === 'guest')>{{ __('admin.orders.guest') }}</option>
                </select>
            </div>
            
                <div class="col-md-2">
                    <label for="status" class="form-label">{{ __('admin.orders.status') }}</label>
                    <select id="status" name="status" class="form-select">
                        <option value="">{{ __('admin.orders.all_statuses') }}</option>
                        <option value="pending" @selected(request('status') === 'pending')>{{ __('admin.orders.status_pending') }}</option>
                        <option value="confirmed" @selected(request('status') === 'confirmed')>{{ __('admin.orders.status_confirmed') }}</option>
                        <option value="preparing" @selected(request('status') === 'preparing')>{{ __('admin.orders.status_preparing') }}</option>
                        <option value="ready" @selected(request('status') === 'ready')>{{ __('admin.orders.status_ready') }}</option>
                        <option value="delivered" @selected(request('status') === 'delivered')>{{ __('admin.orders.status_delivered') }}</option>
                        <option value="cancelled" @selected(request('status') === 'cancelled')>{{ __('admin.orders.status_cancelled') }}</option>
                    </select>
                </div>

                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">{{ __('admin.orders.filter') }}</button>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">{{ __('admin.orders.reset') }}</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('admin.orders.col_id') }}</th>
                            <th>{{ __('admin.orders.col_customer') }}</th>
                            <th>{{ __('admin.orders.phone') }}</th>
                            <th>{{ __('admin.orders.role') }}</th>
                            <th>{{ __('admin.orders.code') }}</th>
                            <th>{{ __('admin.orders.address') }}</th>
                            <th>{{ __('admin.orders.col_total') }}</th>
                            <th>{{ __('admin.orders.col_date') }}</th>
                            <th>{{ __('admin.orders.col_status') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td class="fw-bold">#{{ $order->id }}</td>
                                <td>{{ $order->customer_name }}</td>
                                <td>
                                    <x-phone-flag :phone="$order->customer_phone" />
                                </td>
                                <td>{{ $order->user ? ($order->user->role === 'admin' ? __('admin.orders.admin') : __('admin.orders.user')) : __('admin.orders.guest') }}</td>
                                <td>{{ $order->user_code ?? '-' }}</td>
                                <td>{{ $order->address ?? '-' }}</td>
                                <td>﷼ {{ number_format($order->total_amount, 2) }}</td>
                                <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <span class="badge bg-{{ $badge($order->status) }}">{{ $label($order->status) }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order->id) }}"
                                        class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i> {{ __('admin.orders.details') }}</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">{{ __('admin.orders.empty') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            {{ $orders->links() }}
        </div>
    </div>
@endsection