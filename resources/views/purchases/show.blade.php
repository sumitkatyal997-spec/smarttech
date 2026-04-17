@extends('layouts.app')

@section('content')
    <div class="flex items-start justify-between mb-4">
        <div>
            <h1 class="text-2xl font-semibold">Purchase #{{ $purchase->id }}</h1>
            <div class="text-sm text-gray-600">
                {{ $purchase->purchased_at?->format('Y-m-d') }}
                · Supplier: {{ $purchase->supplier?->name ?: '—' }}
                · Reference: {{ $purchase->reference ?: '—' }}
                · Total: <span class="font-semibold">{{ number_format((float) $purchase->total, 2) }}</span>
            </div>
        </div>
        <a class="text-sm hover:underline" href="{{ route('purchases.index') }}">Back</a>
    </div>

    <div class="bg-white border rounded overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="text-left text-gray-600">
                <tr>
                    <th class="py-2 px-4">Product</th>
                    <th class="py-2 px-4">Qty</th>
                    <th class="py-2 px-4">Unit cost</th>
                    <th class="py-2 px-4">Line total</th>
                    <th class="py-2 px-4">Serials</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($purchase->items as $item)
                    <tr class="border-t align-top">
                        <td class="py-2 px-4">{{ $item->product->name }}</td>
                        <td class="py-2 px-4">{{ $item->qty }}</td>
                        <td class="py-2 px-4">{{ number_format((float) $item->unit_cost, 2) }}</td>
                        <td class="py-2 px-4">{{ number_format((float) $item->line_total, 2) }}</td>
                        <td class="py-2 px-4 whitespace-pre-wrap text-xs text-gray-700">
                            {{ $item->serials_text }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

