@component('mail::message')
# Two-Factor Authentication Code
Hello,
Your 2FA code is **{{ $two_factor_code }}**. It expires in 10 minutes.
If you did not request this, please ignore this email.
Thanks,
{{ config('app.name') }}
@endcomponent
