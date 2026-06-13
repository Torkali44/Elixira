@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h2 class="mb-0">{{ __('admin.categories_page.title') }}</h2>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary"><i class="fas fa-plus me-2"></i> {{ __('admin.categories_page.add_category') }}</a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>{{ __('admin.categories_page.image') }}</th>
                        <th>{{ __('admin.categories_page.name') }}</th>
                        <th>{{ __('admin.categories_page.description') }}</th>
                        <th>{{ __('admin.categories_page.products') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @if($category->image)
                                <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" width="50" class="rounded">
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="fw-bold">{{ $category->name }}</td>
                        <td>{{ Str::limit($category->description, 50) }}</td>
                        <td><span class="badge bg-info rounded-pill">{{ $category->items->count() }}</span></td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="d-inline" data-confirm="{{ __('admin.categories_page.delete_confirm') }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">{{ __('admin.categories_page.empty') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
