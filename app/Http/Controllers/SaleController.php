<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index()
    {
        return view('sales.index', [
            'sales' => Sale::query()
                ->with(['customer', 'creator'])
                ->orderByDesc('sold_at')
                ->orderByDesc('id')
                ->paginate(20),
        ]);
    }

    public function create()
    {
        return view('sales.create', [
            'customers' => Customer::query()->orderBy('name')->get(),
            'products' => Product::query()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id' => ['nullable', 'exists:customers,id'],
            'invoice_no' => ['nullable', 'string', 'max:255'],
            'sold_at' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.qty' => ['nullable', 'integer', 'min:1'],
            'items.*.unit_price' => ['nullable', 'numeric', 'min:0'],
            'items.*.serials' => ['nullable', 'string'],
        ]);

        $userId = $request->user()->id;

        $sale = DB::transaction(function () use ($data, $userId) {
            $sale = Sale::create([
                'customer_id' => $data['customer_id'] ?? null,
                'invoice_no' => $data['invoice_no'] ?? null,
                'sold_at' => $data['sold_at'],
                'notes' => $data['notes'] ?? null,
                'created_by' => $userId,
                'total' => 0,
            ]);

            $total = 0;

            foreach ($data['items'] as $row) {
                $product = Product::query()->findOrFail($row['product_id']);
                $unitPrice = (float) ($row['unit_price'] ?? 0);

                if ($product->tracking === 'serial') {
                    $serials = $this->parseSerials($row['serials'] ?? null);
                    if (count($serials) === 0) {
                        continue;
                    }

                    $units = ProductUnit::query()
                        ->where('product_id', $product->id)
                        ->whereIn('serial_number', $serials)
                        ->where('status', 'in_stock')
                        ->lockForUpdate()
                        ->get()
                        ->keyBy('serial_number');

                    foreach ($serials as $serial) {
                        $unit = $units->get($serial);
                        if (!$unit) {
                            throw new \RuntimeException("Unit not in stock: {$serial}");
                        }

                        /** @var SaleItem $item */
                        $item = SaleItem::create([
                            'sale_id' => $sale->id,
                            'product_id' => $product->id,
                            'product_unit_id' => $unit->id,
                            'qty' => 1,
                            'unit_price' => $unitPrice,
                            'line_total' => round($unitPrice, 2),
                        ]);

                        $unit->update([
                            'status' => 'sold',
                            'sale_item_id' => $item->id,
                        ]);

                        StockMovement::create([
                            'product_id' => $product->id,
                            'direction' => 'out',
                            'qty' => 1,
                            'reason' => 'sale',
                            'sale_item_id' => $item->id,
                            'created_by' => $userId,
                        ]);

                        $total += round($unitPrice, 2);
                    }

                    continue;
                }

                $qty = (int) ($row['qty'] ?? 0);
                if ($qty <= 0) {
                    continue;
                }

                if ($product->onHand() < $qty) {
                    throw new \RuntimeException("Not enough stock for {$product->name} (need {$qty}).");
                }

                $lineTotal = round($qty * $unitPrice, 2);

                /** @var SaleItem $item */
                $item = SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'qty' => $qty,
                    'unit_price' => $unitPrice,
                    'line_total' => $lineTotal,
                ]);

                StockMovement::create([
                    'product_id' => $product->id,
                    'direction' => 'out',
                    'qty' => $qty,
                    'reason' => 'sale',
                    'sale_item_id' => $item->id,
                    'created_by' => $userId,
                ]);

                $total += $lineTotal;
            }

            $sale->update(['total' => $total]);

            return $sale;
        });

        return redirect()->route('sales.show', $sale);
    }

    public function show(Sale $sale)
    {
        return view('sales.show', [
            'sale' => $sale->load(['customer', 'creator', 'items.product', 'items.productUnit']),
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
