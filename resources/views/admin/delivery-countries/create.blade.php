@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h2 class="mb-0">{{ __('admin.delivery_zones.create_country') }}</h2>
    <a href="{{ route('admin.delivery-countries.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i> {{ __('admin.delivery_zones.back') }}</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.delivery-countries.store') }}" method="POST">
            @csrf
            @include('admin.delivery-countries._form')
            <button type="submit" class="btn btn-primary">{{ __('admin.common.save') }}</button>
        </form>
    </div>
</div>
@endsection
