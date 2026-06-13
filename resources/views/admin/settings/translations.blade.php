@extends('layouts.admin')

@section('content')
    <style>
        .translation-toolbar {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .translation-section-btn {
            padding: 0.6rem 1.2rem;
            border-radius: 999px;
            border: 1px solid var(--theme-border);
            background: var(--theme-bg-secondary);
            color: var(--theme-text);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .translation-section-btn.active,
        .translation-section-btn:hover {
            background: var(--primary-color, #b7d7d0);
            color: var(--secondary-color, #13252d);
            border-color: transparent;
        }

        .translation-lang-tabs {
            display: flex;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .translation-lang-tab {
            padding: 0.65rem 1.4rem;
            border: 2px solid var(--theme-border);
            background: var(--theme-bg-secondary);
            color: var(--theme-text);
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
        }

        .translation-lang-tab.active {
            background: #007bff;
            border-color: #007bff;
            color: #fff;
        }

        .translation-panel {
            display: none;
        }

        .translation-panel.active {
            display: block;
        }

        .translation-field {
            margin-bottom: 1rem;
        }

        .translation-field label {
            display: block;
            margin-bottom: 0.35rem;
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--theme-text-muted);
            font-family: monospace;
        }

        .translation-field input,
        .translation-field textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--theme-border);
            border-radius: 8px;
            background: var(--theme-input-bg);
            color: var(--theme-text);
        }

        @media (max-width: 768px) {
            .translation-toolbar,
            .translation-lang-tabs {
                flex-direction: column;
            }

            .translation-section-btn,
            .translation-lang-tab {
                width: 100%;
                text-align: center;
            }
        }
    </style>

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h2 class="mb-1">{{ __('admin.translations_page.title') }}</h2>
            <p class="text-muted mb-0">{{ __('admin.translations_page.subtitle') }}</p>
        </div>
    </div>

    <div class="translation-toolbar">
        @foreach($sections as $key => $section)
            <a href="{{ route('admin.settings.translations', ['section' => $key]) }}"
                class="translation-section-btn {{ $activeSection === $key ? 'active' : '' }}">
                {{ app()->getLocale() === 'ar' ? $section['label_ar'] : $section['label'] }}
            </a>
        @endforeach
    </div>

    <div class="translation-lang-tabs">
        <button type="button" class="translation-lang-tab active" data-lang-panel="en">🇬🇧 English</button>
        <button type="button" class="translation-lang-tab" data-lang-panel="ar">🇸🇦 العربية</button>
    </div>

    @foreach(['en', 'ar'] as $lang)
        <div id="panel-{{ $lang }}" class="translation-panel {{ $lang === 'en' ? 'active' : '' }}">
            <form action="{{ route('admin.settings.translations.update') }}" method="POST">
                @csrf
                <input type="hidden" name="language" value="{{ $lang }}">
                <input type="hidden" name="section" value="{{ $activeSection }}">

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        @forelse($flatTranslations[$lang] as $key => $value)
                            <div class="translation-field">
                                <label for="{{ $lang }}_{{ md5($key) }}">{{ $key }}</label>
                                @if(strlen($value) > 80)
                                    <textarea id="{{ $lang }}_{{ md5($key) }}" name="translations[{{ $key }}]" rows="3">{{ $value }}</textarea>
                                @else
                                    <input type="text" id="{{ $lang }}_{{ md5($key) }}" name="translations[{{ $key }}]" value="{{ $value }}">
                                @endif
                            </div>
                        @empty
                            <p class="text-muted mb-0">{{ __('app.no_results') }}</p>
                        @endforelse
                    </div>
                </div>

                @if($flatTranslations[$lang] !== [])
                    <button type="submit" class="btn btn-success px-4">
                        <i class="fas fa-save me-2"></i>
                        {{ $lang === 'ar' ? __('admin.translations_page.save_ar') : __('admin.translations_page.save_en') }}
                    </button>
                @endif
            </form>
        </div>
    @endforeach

    <script>
        document.querySelectorAll('.translation-lang-tab').forEach((tab) => {
            tab.addEventListener('click', function () {
                document.querySelectorAll('.translation-lang-tab').forEach(el => el.classList.remove('active'));
                document.querySelectorAll('.translation-panel').forEach(el => el.classList.remove('active'));
                tab.classList.add('active');
                document.getElementById('panel-' + tab.dataset.langPanel).classList.add('active');
            });
        });
    </script>
@endsection
