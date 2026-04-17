@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-semibold">New Purchase (Stock In)</h1>
        <a class="text-sm hover:underline" href="{{ route('purchases.index') }}">Back</a>
    </div>

    <div class="bg-white border rounded p-4">
        <form method="POST" action="{{ route('purchases.store') }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Date</label>
                    <input name="purchased_at" type="date" value="{{ old('purchased_at', now()->format('Y-m-d')) }}" class="w-full rounded border px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Supplier</label>
                    <select name="supplier_id" class="w-full rounded border px-3 py-2">
                        <option value="">—</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" @selected(old('supplier_id') == $supplier->id)>{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Reference</label>
                    <input name="reference" value="{{ old('reference') }}" class="w-full rounded border px-3 py-2" placeholder="Invoice / PO #">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Notes</label>
                <textarea name="notes" rows="2" class="w-full rounded border px-3 py-2">{{ old('notes') }}</textarea>
            </div>

            <div class="border rounded">
                <div class="px-4 py-3 border-b flex items-center justify-between">
                    <h2 class="font-semibold">Items</h2>
                    <button type="button" class="px-3 py-1.5 rounded border bg-white hover:bg-gray-50" id="add-row">Add row</button>
                </div>
                <div class="p-4 overflow-x-auto">
                    <table class="min-w-full text-sm" id="items-table">
                        <thead class="text-left text-gray-600">
                            <tr>
                                <th class="py-2 pr-4">Product</th>
                                <th class="py-2 pr-4 w-28">Qty</th>
                                <th class="py-2 pr-4 w-40">Unit cost</th>
                                <th class="py-2 pr-4">Serials (only for serialized products)</th>
                                <th class="py-2 pr-4 w-16"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @for ($i = 0; $i < 3; $i++)
                                <tr class="border-t align-top">
                                    <td class="py-2 pr-4">
                                        <select name="items[{{ $i }}][product_id]" class="w-72 rounded border px-3 py-2">
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->tracking }})</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="py-2 pr-4">
                                        <input name="items[{{ $i }}][qty]" type="number" min="1" class="w-24 rounded border px-3 py-2" value="1">
                                    </td>
                                    <td class="py-2 pr-4">
                                        <input name="items[{{ $i }}][unit_cost]" type="number" step="0.01" min="0" class="w-32 rounded border px-3 py-2" value="0">
                                    </td>
                                    <td class="py-2 pr-4">
                                        <textarea name="items[{{ $i }}][serials]" rows="2" class="w-96 rounded border px-3 py-2" placeholder="One per line (or comma separated)"></textarea>
                                    </td>
                                    <td class="py-2 pr-4">
                                        <button type="button" class="text-red-700 hover:underline remove-row">Remove</button>
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
                <div class="px-4 py-3 border-t text-sm text-gray-600">
                    For serialized products, **Qty is ignored** and we use the number of serials provided.
                </div>
            </div>

            <button class="px-4 py-2 rounded bg-gray-900 text-white hover:bg-gray-800" type="submit">Save Purchase</button>
        </form>
    </div>

    <script>
        (function () {
            const table = document.getElementById('items-table').querySelector('tbody');
            const addRowBtn = document.getElementById('add-row');

            function nextIndex() {
                const rows = table.querySelectorAll('tr');
                return rows.length;
            }

            function wireRemove(btn) {
                btn.addEventListener('click', function () {
                    const tr = btn.closest('tr');
                    if (tr) tr.remove();
                });
            }

            table.querySelectorAll('.remove-row').forEach(wireRemove);

            addRowBtn.addEventListener('click', function () {
                const i = nextIndex();
                const tr = document.createElement('tr');
                tr.className = 'border-t align-top';
                tr.innerHTML = `
                    <td class="py-2 pr-4">
                        <select name="items[${i}][product_id]" class="w-72 rounded border px-3 py-2">
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->tracking }})</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="py-2 pr-4">
                        <input name="items[${i}][qty]" type="number" min="1" class="w-24 rounded border px-3 py-2" value="1">
                    </td>
                    <td class="py-2 pr-4">
                        <input name="items[${i}][unit_cost]" type="number" step="0.01" min="0" class="w-32 rounded border px-3 py-2" value="0">
                    </td>
                    <td class="py-2 pr-4">
                        <textarea name="items[${i}][serials]" rows="2" class="w-96 rounded border px-3 py-2" placeholder="One per line (or comma separated)"></textarea>
                    </td>
                    <td class="py-2 pr-4">
                        <button type="button" class="text-red-700 hover:underline remove-row">Remove</button>
                    </td>
                `;
                table.appendChild(tr);
                wireRemove(tr.querySelector('.remove-row'));
            });
        })();
    </script>
@endsection

