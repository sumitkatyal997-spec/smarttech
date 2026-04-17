@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-semibold">Purchases</h1>
        <a class="px-3 py-2 rounded bg-gray-900 text-white hover:bg-gray-800" href="{{ route('purchases.create') }}">New Purchase</a>
    </div>

    <div class="bg-white border rounded overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="text-left text-gray-600">
                <tr>
                    <th class="py-2 px-4">Date</th>
                    <th class="py-2 px-4">Supplier</th>
                    <th class="py-2 px-4">Reference</th>
                    <th class="py-2 px-4">Total</th>
                    <th class="py-2 px-4">By</th>
                    <th class="py-2 px-4 w-32">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($purchases as $purchase)
                    <tr class="border-t">
                        <td class="py-2 px-4">{{ $purchase->purchased_at?->format('Y-m-d') }}</td>
                        <td class="py-2 px-4">{{ $purchase->supplier?->name }}</td>
                        <td class="py-2 px-4">{{ $purchase->reference }}</td>
                        <td class="py-2 px-4">{{ number_format((float) $purchase->total, 2) }}</td>
                        <td class="py-2 px-4">{{ $purchase->creator?->name }}</td>
                        <td class="py-2 px-4">
                            <a class="px-3 py-1.5 rounded border bg-white hover:bg-gray-50" href="{{ route('purchases.show', $purchase) }}">View</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $purchases->links() }}
    </div>
@endsection

