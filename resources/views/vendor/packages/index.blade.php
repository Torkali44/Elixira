@extends('layouts.vendor')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h2 class="mb-0">{{ __('admin.packages_page.title') }}</h2>
    <a href="{{ route('vendor.packages.create') }}" class="btn btn-primary">{{ __('admin.packages_page.add') }}</a>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>{{ __('admin.packages_page.name') }}</th>
                    <th>{{ __('admin.packages_page.items_count') }}</th>
                    <th>{{ __('admin.packages_page.price') }}</th>
                    <th>{{ __('admin.packages_page.status') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($packages as $package)
                    <tr>
                        <td>{{ $package->local_name }}</td>
                        <td>{{ $package->items_count }}</td>
                        <td>﷼ {{ number_format($package->price, 2) }}</td>
                        <td>
                            @if($package->status === 'approved')
                                <span class="badge bg-success">{{ __('admin.items.status_approved') }}</span>
                            @elseif($package->status === 'pending')
                                <span class="badge bg-warning text-dark">{{ __('admin.items.status_pending') }}</span>
                            @elseif($package->status === 'rejected_with_notes')
                                <span class="badge bg-danger">{{ __('admin.items.status_rejected_notes') }}</span>
                            @elseif($package->status === 'rejected')
                                <span class="badge bg-danger">{{ __('admin.items.status_rejected') }}</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('vendor.packages.edit', $package) }}" class="btn btn-sm btn-outline-primary">{{ __('admin.home_sections_page.edit') }}</a>
                            <form action="{{ route('vendor.packages.destroy', $package) }}" method="POST" class="d-inline" onsubmit="return confirm(@json(__('admin.packages_page.confirm_delete')))">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">{{ __('admin.packages_page.delete') }}</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">{{ __('admin.packages_page.empty') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
{{ $packages->links() }}
@endsection
