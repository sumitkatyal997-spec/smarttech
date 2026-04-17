@extends('layouts.app')

@section('content')
    <div class="flex items-start justify-between mb-4">
        <div>
            <h1 class="text-2xl font-semibold">{{ $product->name }}</h1>
            <div class="text-sm text-gray-600">
                SKU: {{ $product->sku ?: '—' }} · Tracking: {{ $product->tracking }} · On hand: <span class="font-semibold">{{ $onHand }}</span>
            </div>
        </div>
        <div class="flex gap-2">
            <a class="px-3 py-2 rounded border bg-white hover:bg-gray-50" href="{{ route('products.edit', $product) }}">Edit</a>
            <a class="px-3 py-2 rounded bg-gray-900 text-white hover:bg-gray-800" href="{{ route('purchases.create') }}">Stock In</a>
            <a class="px-3 py-2 rounded bg-gray-900 text-white hover:bg-gray-800" href="{{ route('sales.create') }}">Sell</a>
        </div>
    </div>

    @if ($product->tracking === 'serial')
        <div class="bg-white border rounded">
            <div class="px-4 py-3 border-b flex items-center justify-between">
                <h2 class="font-semibold">Serialized Units</h2>
                <div class="text-sm text-gray-600">In stock: {{ $product->units()->where('status','in_stock')->count() }}</div>
            </div>
            <div class="p-4 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="text-left text-gray-600">
                        <tr>
                            <th class="py-2 pr-4">Serial</th>
                            <th class="py-2 pr-4">Status</th>
                            <th class="py-2 pr-4">Location</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($serialUnits as $unit)
                            <tr class="border-t">
                                <td class="py-2 pr-4">{{ $unit->serial_number }}</td>
                                <td class="py-2 pr-4">{{ $unit->status }}</td>
                                <td class="py-2 pr-4">{{ $unit->location }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t">
                {{ $serialUnits->links() }}
            </div>
        </div>
    @else
        <div class="bg-white border rounded p-4">
            <div class="text-sm text-gray-600">This product is tracked by quantity. Stock changes come from Purchases and Sales.</div>
        </div>
    @endif
@endsection

