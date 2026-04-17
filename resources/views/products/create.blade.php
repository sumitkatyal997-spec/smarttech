@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-semibold">New Product</h1>
        <a class="text-sm hover:underline" href="{{ route('products.index') }}">Back</a>
    </div>

    <div class="bg-white border rounded p-4 max-w-2xl">
        <form method="POST" action="{{ route('products.store') }}" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Name</label>
                    <input name="name" value="{{ old('name') }}" class="w-full rounded border px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">SKU (optional)</label>
                    <input name="sku" value="{{ old('sku') }}" class="w-full rounded border px-3 py-2">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Category</label>
                    <select name="category_id" class="w-full rounded border px-3 py-2">
                        <option value="">—</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Tracking</label>
                    <select name="tracking" class="w-full rounded border px-3 py-2" required>
                        <option value="qty" @selected(old('tracking') === 'qty')>Quantity</option>
                        <option value="serial" @selected(old('tracking') === 'serial')>Serialized (per unit)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Reorder level</label>
                    <input name="reorder_level" type="number" min="0" value="{{ old('reorder_level', 0) }}" class="w-full rounded border px-3 py-2">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Notes</label>
                <textarea name="notes" rows="3" class="w-full rounded border px-3 py-2">{{ old('notes') }}</textarea>
            </div>

            <button class="px-4 py-2 rounded bg-gray-900 text-white hover:bg-gray-800" type="submit">Create</button>
        </form>
    </div>
@endsection

