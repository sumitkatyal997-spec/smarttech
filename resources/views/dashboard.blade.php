@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-semibold">Dashboard</h1>
        <div class="flex gap-2">
            <a class="px-3 py-2 rounded bg-white border hover:bg-gray-50" href="{{ route('purchases.create') }}">New Purchase</a>
            <a class="px-3 py-2 rounded bg-gray-900 text-white hover:bg-gray-800" href="{{ route('sales.create') }}">New Sale</a>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white border rounded p-4">
            <div class="text-sm text-gray-600">Products</div>
            <div class="text-2xl font-semibold">{{ $productCount }}</div>
        </div>
        <div class="bg-white border rounded p-4">
            <div class="text-sm text-gray-600">Purchases</div>
            <div class="text-2xl font-semibold">{{ $purchaseCount }}</div>
        </div>
        <div class="bg-white border rounded p-4">
            <div class="text-sm text-gray-600">Sales</div>
            <div class="text-2xl font-semibold">{{ $saleCount }}</div>
        </div>
    </div>

    <div class="bg-white border rounded">
        <div class="px-4 py-3 border-b flex items-center justify-between">
            <h2 class="font-semibold">Products (quick view)</h2>
            <a class="text-sm hover:underline" href="{{ route('products.index') }}">View all</a>
        </div>
        <div class="p-4 overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="text-left text-gray-600">
                    <tr>
                        <th class="py-2 pr-4">Name</th>
                        <th class="py-2 pr-4">Tracking</th>
                        <th class="py-2 pr-4">On hand</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recentProducts as $product)
                        <tr class="border-t">
                            <td class="py-2 pr-4">
                                <a class="hover:underline" href="{{ route('products.show', $product) }}">{{ $product->name }}</a>
                            </td>
                            <td class="py-2 pr-4">{{ $product->tracking }}</td>
                            <td class="py-2 pr-4">{{ $product->onHand() }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

