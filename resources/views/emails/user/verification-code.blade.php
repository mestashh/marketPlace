<x-mail::message>
    # Email Verification

    Your verification code:

    <x-mail::panel>
        {{ $code }}
    </x-mail::panel>

    This code will expire in 10 minutes.

    If you didn’t request this — just ignore this email.

    Thanks,
    {{ config('app.name') }}
</x-mail::message>
