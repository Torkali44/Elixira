@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h2 class="mb-0">{{ __('admin.packages_page.add') }}</h2>
    <a href="{{ route('admin.packages.index') }}" class="btn btn-secondary">{{ __('admin.packages_page.back') }}</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.packages.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('admin.packages.partials.form', ['package' => null, 'items' => $items])
            <button type="submit" class="btn btn-primary">{{ __('admin.packages_page.save') }}</button>
        </form>
    </div>
</div>
@endsection
