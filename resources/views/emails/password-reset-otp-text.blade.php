{{ __('app.auth.password_reset_email_greeting', ['name' => $user->name]) }}

{{ __('app.auth.password_reset_email_line') }}

{{ $code }}

{{ __('app.auth.password_reset_email_account', ['email' => $user->email]) }}

{{ __('app.auth.password_reset_email_expiry', ['minutes' => $minutes]) }}

{{ __('app.auth.password_reset_email_ignore') }}

{{ __('app.auth.password_reset_email_signature', ['app' => config('app.name')]) }}
