@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-semibold">Products</h1>
        <a class="px-3 py-2 rounded bg-gray-900 text-white hover:bg-gray-800" href="{{ route('products.create') }}">New Product</a>
    </div>

    <div class="bg-white border rounded overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="text-left text-gray-600">
                <tr>
                    <th class="py-2 px-4">Name</th>
                    <th class="py-2 px-4">SKU</th>
                    <th class="py-2 px-4">Category</th>
                    <th class="py-2 px-4">Tracking</th>
                    <th class="py-2 px-4">On hand</th>
                    <th class="py-2 px-4 w-56">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr class="border-t">
                        <td class="py-2 px-4">
                            <a class="hover:underline" href="{{ route('products.show', $product) }}">{{ $product->name }}</a>
                        </td>
                        <td class="py-2 px-4">{{ $product->sku }}</td>
                        <td class="py-2 px-4">{{ $product->category?->name }}</td>
                        <td class="py-2 px-4">{{ $product->tracking }}</td>
                        <td class="py-2 px-4">{{ $product->onHand() }}</td>
                        <td class="py-2 px-4 flex gap-2">
                            <a class="px-3 py-1.5 rounded border bg-white hover:bg-gray-50" href="{{ route('products.edit', $product) }}">Edit</a>
                            <form method="POST" action="{{ route('products.destroy', $product) }}" onsubmit="return confirm('Delete this product?');">
                                @csrf
                                @method('DELETE')
                                <button class="px-3 py-1.5 rounded border border-red-200 text-red-700 bg-red-50 hover:bg-red-100" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $products->links() }}
    </div>
@endsection

