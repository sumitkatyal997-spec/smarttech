<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;

class DashboardController extends Controller
{
    public function index()
    {
        $products = Product::query()->orderBy('name')->limit(10)->get();

        return view('dashboard', [
            'productCount' => Product::query()->count(),
            'purchaseCount' => Purchase::query()->count(),
            'saleCount' => Sale::query()->count(),
            'recentProducts' => $products,
        ]);
    }
}
