<x-mail::message>
    # Admin Joined to Conversation

    <x-mail::panel>
        Admin joined to conversation {{ $conversation->id }}
    </x-mail::panel>

    Thanks,
    {{ config('app.name') }}
</x-mail::message>
