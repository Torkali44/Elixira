{{ __('app.auth.verify_otp_email_greeting', ['name' => $user->name]) }}

{{ __('app.auth.verify_otp_email_line') }}

{{ __('app.auth.verify_otp_email_account', ['email' => $user->email]) }}

{{ $code }}

{{ __('app.auth.verify_otp_email_expiry', ['minutes' => $minutes]) }}

{{ __('app.auth.verify_otp_email_ignore') }}

{{ __('app.auth.verify_otp_email_signature', ['app' => config('app.name')]) }}
