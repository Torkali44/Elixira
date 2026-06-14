@extends('layouts.admin')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
        <i class="fas fa-arrow-left"></i> {{ __('admin.common.back') }}
    </a>
    <h2 class="mb-0">{{ $contactMessage->subject }}</h2>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <p><strong>{{ __('admin.contact_messages.col_name') }}:</strong> {{ $contactMessage->name }}</p>
        <p><strong>{{ __('admin.contact_messages.col_email') }}:</strong> {{ $contactMessage->email }}</p>
        <p><strong>{{ __('admin.contact_messages.col_reason') }}:</strong> {{ $contactMessage->reason }}</p>
        <p><strong>{{ __('admin.contact_messages.col_date') }}:</strong> {{ $contactMessage->created_at->format('Y-m-d H:i') }}</p>
        <hr>
        <p style="white-space: pre-wrap;">{{ $contactMessage->message }}</p>
    </div>
</div>

<form action="{{ route('admin.contact-messages.destroy', $contactMessage) }}" method="POST" class="mt-3" data-confirm="{{ __('admin.contact_messages.confirm_delete') }}">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-outline-danger">{{ __('admin.common.delete') }}</button>
</form>
@endsection
