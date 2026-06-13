@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h2 class="mb-1 fw-bold">{{ __('admin.faqs_page.title') }}</h2>
        <p class="text-muted mb-0">{{ __('admin.faqs_page.subtitle') }}</p>
    </div>
    <a href="{{ route('admin.faqs.create') }}" class="btn btn-primary"><i class="fas fa-plus me-2"></i> {{ __('admin.faqs_page.add_faq') }}</a>
</div>

<div class="card border-0 shadow-sm" style="border-radius: 16px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4" style="width: 5%;">#</th>
                        <th style="width: 35%;">{{ __('admin.faqs_page.col_question') }}</th>
                        <th style="width: 35%;">{{ __('admin.faqs_page.col_answer') }}</th>
                        <th style="width: 10%;">{{ __('admin.faqs_page.col_order') }}</th>
                        <th style="width: 10%;">{{ __('admin.faqs_page.col_status') }}</th>
                        <th class="text-end pe-4" style="width: 5%;">{{ __('admin.faqs_page.col_actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($faqs as $faq)
                    <tr>
                        <td class="ps-4 text-muted">{{ $loop->iteration + ($faqs->currentPage() - 1) * $faqs->perPage() }}</td>
                        <td>
                            <div class="fw-bold text-dark mb-1">{{ $faq->question_en }}</div>
                            <small class="text-muted d-block" dir="rtl" style="text-align: right;">{{ $faq->question_ar }}</small>
                        </td>
                        <td>
                            <div class="text-secondary small mb-1" style="max-width: 350px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                {{ strip_tags($faq->answer_en) }}
                            </div>
                            <small class="text-muted d-block" dir="rtl" style="max-width: 350px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; text-align: right;">
                                {{ strip_tags($faq->answer_ar) }}
                            </small>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border px-3 py-1.5">{{ $faq->sort_order }}</span>
                        </td>
                        <td>
                            @if($faq->is_published)
                                <span class="badge bg-success rounded-pill px-3 py-1.5"><i class="fas fa-check-circle me-1"></i> {{ __('admin.faqs_page.status_published') }}</span>
                            @else
                                <span class="badge bg-secondary rounded-pill px-3 py-1.5"><i class="fas fa-eye-slash me-1"></i> {{ __('admin.faqs_page.status_draft') }}</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group">
                                <a href="{{ route('admin.faqs.edit', $faq->id) }}" class="btn btn-sm btn-outline-primary" title="{{ __('admin.faqs_page.edit_faq') }}"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('admin.faqs.destroy', $faq->id) }}" method="POST" class="d-inline" data-confirm="{{ __('admin.faqs_page.delete_confirm') }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="{{ __('admin.faqs_page.delete_confirm') }}"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fas fa-question-circle d-block mb-3" style="font-size: 2.5rem; opacity: 0.3;"></i>
                            {{ __('admin.faqs_page.empty') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4">
    {{ $faqs->links() }}
</div>
@endsection
