@extends('layouts.app')

@section('content')
    <div class="flex items-start justify-between mb-4">
        <div>
            <h1 class="text-2xl font-semibold">Sale #{{ $sale->id }}</h1>
            <div class="text-sm text-gray-600">
                {{ $sale->sold_at?->format('Y-m-d') }}
                · Customer: {{ $sale->customer?->name ?: '—' }}
                · Invoice: {{ $sale->invoice_no ?: '—' }}
                · Total: <span class="font-semibold">{{ number_format((float) $sale->total, 2) }}</span>
            </div>
        </div>
        <a class="text-sm hover:underline" href="{{ route('sales.index') }}">Back</a>
    </div>

    <div class="bg-white border rounded overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="text-left text-gray-600">
                <tr>
                    <th class="py-2 px-4">Product</th>
                    <th class="py-2 px-4">Qty</th>
                    <th class="py-2 px-4">Unit price</th>
                    <th class="py-2 px-4">Line total</th>
                    <th class="py-2 px-4">Serial (if any)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sale->items as $item)
                    <tr class="border-t align-top">
                        <td class="py-2 px-4">{{ $item->product->name }}</td>
                        <td class="py-2 px-4">{{ $item->qty }}</td>
                        <td class="py-2 px-4">{{ number_format((float) $item->unit_price, 2) }}</td>
                        <td class="py-2 px-4">{{ number_format((float) $item->line_total, 2) }}</td>
                        <td class="py-2 px-4">{{ $item->productUnit?->serial_number }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

