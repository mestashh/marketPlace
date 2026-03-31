<x-mail::message>
    # New order was created

    You have a new order:

    <x-mail::panel>
        Order ID: {{ $shopOrder->order->id }}

        Subtotal: {{ $shopOrder->subtotal_price }}

        Status: {{ $shopOrder->status }}
    </x-mail::panel>

    Thanks,
    {{ config('app.name') }}
</x-mail::message>
