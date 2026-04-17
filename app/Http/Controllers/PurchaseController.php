<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\StockMovement;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index()
    {
        return view('purchases.index', [
            'purchases' => Purchase::query()
                ->with(['supplier', 'creator'])
                ->orderByDesc('purchased_at')
                ->orderByDesc('id')
                ->paginate(20),
        ]);
    }

    public function create()
    {
        return view('purchases.create', [
            'suppliers' => Supplier::query()->orderBy('name')->get(),
            'products' => Product::query()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
            'reference' => ['nullable', 'string', 'max:255'],
            'purchased_at' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.qty' => ['nullable', 'integer', 'min:1'],
            'items.*.unit_cost' => ['nullable', 'numeric', 'min:0'],
            'items.*.serials' => ['nullable', 'string'],
        ]);

        $userId = $request->user()->id;

        $purchase = DB::transaction(function () use ($data, $userId) {
            $purchase = Purchase::create([
                'supplier_id' => $data['supplier_id'] ?? null,
                'reference' => $data['reference'] ?? null,
                'purchased_at' => $data['purchased_at'],
                'notes' => $data['notes'] ?? null,
                'created_by' => $userId,
                'total' => 0,
            ]);

            $total = 0;

            foreach ($data['items'] as $row) {
                $product = Product::query()->findOrFail($row['product_id']);

                $unitCost = (float) ($row['unit_cost'] ?? 0);
                $serials = $this->parseSerials($row['serials'] ?? null);

                $qty = $product->tracking === 'serial'
                    ? count($serials)
                    : (int) ($row['qty'] ?? 0);

                if ($qty <= 0) {
                    continue;
                }

                if ($product->tracking === 'serial' && count($serials) !== $qty) {
                    throw new \RuntimeException('Serial parse mismatch.');
                }

                $lineTotal = round($qty * $unitCost, 2);

                /** @var PurchaseItem $item */
                $item = PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $product->id,
                    'qty' => $qty,
                    'unit_cost' => $unitCost,
                    'line_total' => $lineTotal,
                    'serials_text' => $product->tracking === 'serial' ? implode("\n", $serials) : null,
                ]);

                if ($product->tracking === 'serial') {
                    foreach ($serials as $serial) {
                        ProductUnit::create([
                            'product_id' => $product->id,
                            'purchase_item_id' => $item->id,
                            'serial_number' => $serial,
                            'status' => 'in_stock',
                        ]);
                    }
                }

                StockMovement::create([
                    'product_id' => $product->id,
                    'direction' => 'in',
                    'qty' => $qty,
                    'reason' => 'purchase',
                    'purchase_item_id' => $item->id,
                    'created_by' => $userId,
                ]);

                $total += $lineTotal;
            }

            $purchase->update(['total' => $total]);

            return $purchase;
        });

        return redirect()->route('purchases.show', $purchase);
    }

    public function show(Purchase $purchase)
    {
        return view('purchases.show', [
            'purchase' => $purchase->load(['supplier', 'creator', 'items.product', 'items.units']),
        ]);
    }

    private function parseSerials(?string $serialsText): array
    {
        if ($serialsText === null) {
            return [];
        }

        $serialsText = str_replace(["\r\n", "\r"], "\n", trim($serialsText));
        if ($serialsText === '') {
            return [];
        }

        $parts = preg_split('/[\n,]+/', $serialsText) ?: [];
        $serials = array_values(array_filter(array_map(static fn ($s) => trim((string) $s), $parts)));

        return array_values(array_unique($serials));
    }
}
