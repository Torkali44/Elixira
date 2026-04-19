@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h2 class="mb-0">Homepage sections</h2>
        <p class="text-muted small mb-0">Edit each block of the storefront homepage. Slugs are fixed for the theme; toggle visibility or reorder with sort order.</p>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Order</th>
                        <th>Label</th>
                        <th>Slug</th>
                        <th>Template</th>
                        <th>Visible</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sections as $section)
                    <tr>
                        <td>{{ $section->sort_order }}</td>
                        <td>{{ $section->admin_label ?? '—' }}</td>
                        <td><code>{{ $section->slug }}</code></td>
                        <td><span class="badge bg-secondary">{{ $section->template }}</span></td>
                        <td>
                            @if($section->is_active)
                                <span class="badge bg-success">Yes</span>
                            @else
                                <span class="badge bg-secondary">No</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.home-sections.edit', $section) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
