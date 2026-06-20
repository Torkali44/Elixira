<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
</head>
<body style="margin:0;padding:24px;background:#ffffff;font-family:Arial,Helvetica,sans-serif;color:#222222;font-size:15px;line-height:1.6;">
    <p style="margin:0 0 16px;">{{ __('app.auth.verify_otp_email_greeting', ['name' => $user->name]) }}</p>

    <p style="margin:0 0 16px;">{{ __('app.auth.verify_otp_email_line') }}</p>

    <p style="margin:0 0 20px;font-size:28px;font-weight:700;letter-spacing:6px;color:#111111;">{{ $code }}</p>

    <p style="margin:0 0 16px;">{{ __('app.auth.verify_otp_email_expiry', ['minutes' => $minutes]) }}</p>

    <p style="margin:0 0 24px;">{{ __('app.auth.verify_otp_email_ignore') }}</p>

    <p style="margin:0;color:#666666;">{{ config('app.name') }}</p>
</body>
</html>
