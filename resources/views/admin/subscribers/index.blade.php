@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1 fw-bold">Newsletter Subscribers</h2>
        <p class="text-muted mb-0">Manage newsletter subscriptions and send bulk emails.</p>
    </div>
</div>

<form id="bulkEmailForm" action="{{ route('admin.subscribers.sendMail') }}" method="POST">
    @csrf

    <div class="row">
        <!-- Subscribers List -->
        <div class="col-lg-7 mb-4">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold m-0 text-dark">Subscribers List ({{ $subscribers->total() }})</h5>
                    <div class="form-check m-0">
                        <input class="form-check-input" type="checkbox" id="selectAll">
                        <label class="form-check-label fw-bold text-primary" for="selectAll" style="cursor: pointer;">
                            Select All
                        </label>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4" style="width: 50px;"></th>
                                    <th>Email Address</th>
                                    <th>Subscribed On</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($subscribers as $sub)
                                <tr>
                                    <td class="ps-4">
                                        <input class="form-check-input subscriber-checkbox" type="checkbox" name="subscribers[]" value="{{ $sub->id }}" data-email="{{ $sub->email }}" id="sub_{{ $sub->id }}">
                                    </td>
                                    <td>
                                        <label for="sub_{{ $sub->id }}" class="fw-medium mb-0" style="cursor: pointer;">{{ $sub->email }}</label>
                                    </td>
                                    <td class="text-muted small">
                                        {{ $sub->created_at->format('M d, Y h:i A') }}
                                    </td>
                                    <td class="text-end pe-4">
                                        <button type="submit" form="deleteForm{{ $sub->id }}" class="btn btn-sm btn-outline-danger" title="Remove Subscriber">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="fas fa-envelope-open-text d-block mb-3" style="font-size: 2.5rem; opacity: 0.3;"></i>
                                        No subscribers found.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 py-3">
                    {{ $subscribers->links() }}
                </div>
            </div>
        </div>

        <!-- Compose Email Section -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm sticky-lg-top" style="border-radius: 16px; top: 20px; z-index: 100;">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold m-0 text-dark">Compose Bulk Email</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Selected Recipients</label>
                        <div class="p-2 border rounded bg-light text-muted small" id="recipientCount">
                            0 subscribers selected.
                        </div>
                        <div class="form-text text-danger" id="selectionError" style="display: none;">
                            Please select at least one subscriber from the list.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="subject" class="form-label fw-bold">Email Subject</label>
                        <input type="text" id="subject" name="subject" class="form-control" placeholder="e.g. Welcome to Elixira's Whisper" required>
                    </div>

                    <div class="mb-3">
                        <label for="content" class="form-label fw-bold">Email Content (Plain Text)</label>
                        <textarea id="content" name="content" class="form-control" rows="10" placeholder="Type your message here..." required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100" id="sendBtn">
                        <i class="fas fa-paper-plane me-2"></i> Send Bulk Email
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- External Delete Forms to avoid nesting form tags -->
@foreach($subscribers as $sub)
<form id="deleteForm{{ $sub->id }}" action="{{ route('admin.subscribers.destroy', $sub->id) }}" method="POST" data-confirm="Are you sure you want to delete {{ $sub->email }} from the subscriber list?">
    @csrf
    @method('DELETE')
</form>
@endforeach

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.subscriber-checkbox');
        const recipientCount = document.getElementById('recipientCount');
        const selectionError = document.getElementById('selectionError');
        const bulkForm = document.getElementById('bulkEmailForm');

        function updateRecipientCount() {
            const checkedCount = document.querySelectorAll('.subscriber-checkbox:checked').length;
            recipientCount.textContent = `${checkedCount} subscriber(s) selected.`;
            if (checkedCount > 0) {
                selectionError.style.display = 'none';
            }
        }

        selectAll.addEventListener('change', function() {
            checkboxes.forEach(cb => {
                cb.checked = selectAll.checked;
            });
            updateRecipientCount();
        });

        checkboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                if (!this.checked) {
                    selectAll.checked = false;
                } else {
                    const allChecked = document.querySelectorAll('.subscriber-checkbox:checked').length === checkboxes.length;
                    selectAll.checked = allChecked;
                }
                updateRecipientCount();
            });
        });

        bulkForm.addEventListener('submit', function(event) {
            event.preventDefault();
            const checkedCount = document.querySelectorAll('.subscriber-checkbox:checked').length;
            if (checkedCount === 0) {
                selectionError.style.display = 'block';
                selectionError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            }

            const subject = document.getElementById('subject').value.trim();
            const content = document.getElementById('content').value.trim();

            if (!subject || !content) {
                alert('Please fill in both Subject and Content fields.');
                return;
            }

            // Get emails
            const emails = Array.from(document.querySelectorAll('.subscriber-checkbox:checked'))
                                .map(cb => cb.getAttribute('data-email'));

            const emailStr = emails.join(';'); // Semicolon is preferred by Outlook
            const subjectEncoded = encodeURIComponent(subject);
            const bodyEncoded = encodeURIComponent(content);

            window.location.href = `mailto:?bcc=${emailStr}&subject=${subjectEncoded}&body=${bodyEncoded}`;
        });
    });
</script>
@endpush
