@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Add Avatar</h2>
    <a href="{{ route('admin.avatar-options.index') }}" class="btn btn-secondary">Back</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.avatar-options.store') }}" method="POST">
            @csrf
            @include('admin.avatar-options.partials.form', ['avatarOption' => null])
            <button class="btn btn-primary">Save Avatar</button>
        </form>
    </div>
</div>
@endsection
