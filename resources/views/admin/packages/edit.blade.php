@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h2 class="mb-0">Edit Package</h2>
    <a href="{{ route('admin.packages.index') }}" class="btn btn-secondary">Back</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.packages.update', $package) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.packages.partials.form', ['package' => $package, 'items' => $items])
            <button type="submit" class="btn btn-primary">Update Package</button>
        </form>
    </div>
</div>
@endsection
