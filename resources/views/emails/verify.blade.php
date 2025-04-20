@component('mail::message')

Hello {{ $user->first_name }},
Please click the button below to verify your email address:
@component('mail::button', ['url' => url("/verify-email/" . $user->verification_token)])
Verify Email
@endcomponent
Thanks,
{{ config('app.name') }}
@endcomponent

