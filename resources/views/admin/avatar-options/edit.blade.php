@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Edit Avatar</h2>
    <a href="{{ route('admin.avatar-options.index') }}" class="btn btn-secondary">Back</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.avatar-options.update', $avatarOption) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.avatar-options.partials.form', ['avatarOption' => $avatarOption])
            <button class="btn btn-primary">Update Avatar</button>
        </form>
    </div>
</div>
@endsection
