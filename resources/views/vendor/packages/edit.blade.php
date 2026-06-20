@extends('layouts.vendor')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h2 class="mb-0">{{ __('admin.packages_page.edit') }}</h2>
    <a href="{{ route('vendor.packages.index') }}" class="btn btn-secondary">{{ __('admin.packages_page.back') }}</a>
</div>
<div class="card shadow-sm"><div class="card-body">
    <form action="{{ route('vendor.packages.update', $package) }}" method="POST" enctype="multipart/form-data" novalidate>
        @csrf @method('PUT')
        @include('admin.packages.partials.form', ['package' => $package, 'items' => $items, 'vendorMode' => true])
        <button type="submit" class="btn btn-primary">{{ __('admin.packages_page.update') }}</button>
    </form>
</div></div>
@endsection
