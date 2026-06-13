@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h2 class="mb-1 fw-bold">{{ __('admin.blogs_page.title') }}</h2>
        <p class="text-muted mb-0">{{ __('admin.blogs_page.subtitle') }}</p>
    </div>
    <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary"><i class="fas fa-plus me-2"></i> {{ __('admin.blogs_page.new_blog') }}</a>
</div>

<div class="card border-0 shadow-sm" style="border-radius: 16px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4" style="width: 5%;">#</th>
                        <th style="width: 10%;">{{ __('admin.blogs_page.col_image') }}</th>
                        <th style="width: 30%;">{{ __('admin.blogs_page.col_title') }}</th>
                        <th style="width: 15%;">{{ __('admin.blogs_page.col_slug') }}</th>
                        <th style="width: 12%;">{{ __('admin.blogs_page.col_published') }}</th>
                        <th style="width: 10%;">{{ __('admin.blogs_page.col_status') }}</th>
                        <th class="text-end pe-4" style="width: 8%;">{{ __('admin.blogs_page.col_actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($blogs as $blog)
                    <tr>
                        <td class="ps-4 text-muted">{{ $loop->iteration + ($blogs->currentPage() - 1) * $blogs->perPage() }}</td>
                        <td>
                            @if($blog->image)
                                <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title_en }}" width="60" height="42" class="rounded shadow-sm border" style="object-fit: cover;">
                            @else
                                <div class="bg-light rounded border d-flex align-items-center justify-content-center text-muted" style="width: 60px; height: 42px;">
                                    <i class="fas fa-image"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="fw-bold text-dark mb-1">{{ Str::limit($blog->title_en, 50) }}</div>
                            <small class="text-muted d-block" dir="rtl" style="text-align: right;">{{ Str::limit($blog->title_ar, 50) }}</small>
                        </td>
                        <td><code style="font-size: 0.8rem;">{{ $blog->slug }}</code></td>
                        <td>
                            @if($blog->published_at)
                                <small class="text-dark">{{ $blog->published_at->format('M d, Y') }}</small>
                            @else
                                <small class="text-muted">—</small>
                            @endif
                        </td>
                        <td>
                            @if($blog->is_published)
                                <span class="badge bg-success rounded-pill px-3 py-1"><i class="fas fa-check-circle me-1"></i> {{ __('admin.blogs_page.status_published') }}</span>
                            @else
                                <span class="badge bg-secondary rounded-pill px-3 py-1"><i class="fas fa-eye-slash me-1"></i> {{ __('admin.blogs_page.status_draft') }}</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group">
                                <a href="{{ route('blogs.show', $blog->slug) }}" target="_blank" class="btn btn-sm btn-outline-info" title="{{ __('admin.blogs_page.view_site') }}"><i class="fas fa-external-link-alt"></i></a>
                                <a href="{{ route('admin.blogs.edit', $blog->id) }}" class="btn btn-sm btn-outline-primary" title="{{ __('admin.blogs_page.edit') }}"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('admin.blogs.destroy', $blog->id) }}" method="POST" class="d-inline" data-confirm="{{ __('admin.blogs_page.delete_confirm') }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="{{ __('admin.blogs_page.delete') }}"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="fas fa-newspaper d-block mb-3" style="font-size: 2.5rem; opacity: 0.3;"></i>
                            {{ __('admin.blogs_page.empty') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4">
    {{ $blogs->links() }}
</div>
@endsection
