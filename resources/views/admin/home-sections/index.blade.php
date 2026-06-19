@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h2 class="mb-0">{{ __('admin.home_sections_page.title') }}</h2>
        <p class="text-muted small mb-0">{{ __('admin.home_sections_page.subtitle') }}</p>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('admin.home_sections_page.order') }}</th>
                        <th>{{ __('admin.home_sections_page.label') }}</th>
                        <th>{{ __('admin.home_sections_page.slug') }}</th>
                        <th>{{ __('admin.home_sections_page.template') }}</th>
                        <th>{{ __('admin.home_sections_page.visible') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sections as $section)
                    <tr>
                        <td>{{ $section->sort_order }}</td>
                        <td>{{ $section->admin_label ?? '-' }}</td>
                        <td><code>{{ $section->slug }}</code></td>
                        <td><span class="badge bg-secondary">{{ $section->template }}</span></td>
                        <td>
                            @if($section->is_active)
                                <span class="badge bg-success">{{ __('admin.home_sections_page.yes') }}</span>
                            @else
                                <span class="badge bg-secondary">{{ __('admin.home_sections_page.no') }}</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.home-sections.edit', $section) }}" class="btn btn-sm btn-outline-primary">{{ __('admin.home_sections_page.edit') }}</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
