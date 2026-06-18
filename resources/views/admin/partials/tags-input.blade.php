@php
    $tagValue = old('tags', $selectedTags ?? '');
    $suggestions = $tagSuggestions ?? [];
@endphp

<div class="mb-3">
    <label for="tags" class="form-label fw-semibold">{{ __('admin.tags.label') }}</label>
    <input type="text"
           id="tags"
           name="tags"
           class="form-control @error('tags') is-invalid @enderror"
           value="{{ $tagValue }}"
           placeholder="{{ __('admin.tags.placeholder') }}">
    <div class="form-text">{{ __('admin.tags.hint') }}</div>
    @error('tags') <div class="invalid-feedback">{{ $message }}</div> @enderror

    @if($suggestions !== [])
        <div class="d-flex flex-wrap gap-2 mt-2">
            @foreach($suggestions as $suggestion)
                <button type="button"
                        class="btn btn-sm btn-outline-secondary js-tag-suggestion"
                        data-tag="{{ $suggestion }}">
                    {{ $suggestion }}
                </button>
            @endforeach
        </div>
    @endif
</div>

@once
    @push('scripts')
        <script>
            document.querySelectorAll('.js-tag-suggestion').forEach((button) => {
                button.addEventListener('click', () => {
                    const input = document.getElementById('tags');
                    if (!input) return;

                    const tag = button.dataset.tag;
                    const current = input.value.split(/[,;]+/).map((part) => part.trim()).filter(Boolean);

                    if (current.includes(tag)) {
                        return;
                    }

                    input.value = current.length ? `${input.value.replace(/\s*,\s*$/, '')}, ${tag}` : tag;
                    input.focus();
                });
            });
        </script>
    @endpush
@endonce
