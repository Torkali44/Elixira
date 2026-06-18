@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h2 class="mb-0">Add Package</h2>
    <a href="{{ route('admin.packages.index') }}" class="btn btn-secondary">Back</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.packages.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('admin.packages.partials.form', ['package' => null, 'items' => $items])
            <button type="submit" class="btn btn-primary">Save Package</button>
        </form>
    </div>
</div>
@endsection
