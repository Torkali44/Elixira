@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">{{ __('admin.avatar_options_admin.add_avatar') }}</h2>
    <a href="{{ route('admin.avatar-options.index') }}" class="btn btn-secondary">{{ __('admin.avatar_options_admin.back') }}</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.avatar-options.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('admin.avatar-options.partials.form', ['avatarOption' => null])
            <button class="btn btn-primary">{{ __('admin.avatar_options_admin.save') }}</button>
        </form>
    </div>
</div>
@endsection
