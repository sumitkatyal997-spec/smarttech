<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'SmartTech Inventory') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-900">
    <div class="min-h-screen">
        <nav class="bg-white border-b">
            <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <a href="{{ route('dashboard') }}" class="font-semibold">SmartTech Inventory</a>
                    <div class="hidden md:flex items-center gap-3 text-sm text-gray-700">
                        <a class="hover:underline" href="{{ route('products.index') }}">Products</a>
                        <a class="hover:underline" href="{{ route('categories.index') }}">Categories</a>
                        <a class="hover:underline" href="{{ route('suppliers.index') }}">Suppliers</a>
                        <a class="hover:underline" href="{{ route('customers.index') }}">Customers</a>
                        <a class="hover:underline" href="{{ route('purchases.index') }}">Purchases</a>
                        <a class="hover:underline" href="{{ route('sales.index') }}">Sales</a>
                    </div>
                </div>

                <div class="flex items-center gap-3 text-sm">
                    <span class="text-gray-600 hidden sm:inline">
                        {{ auth()->user()->name }} ({{ auth()->user()->role }})
                    </span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="px-3 py-1.5 rounded bg-gray-900 text-white hover:bg-gray-800" type="submit">Logout</button>
                    </form>
                </div>
            </div>
        </nav>

        <main class="max-w-6xl mx-auto p-4">
            @if (session('status'))
                <div class="mb-4 rounded border border-green-200 bg-green-50 px-4 py-2 text-green-800">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 rounded border border-red-200 bg-red-50 px-4 py-2 text-red-800">
                    <div class="font-semibold mb-1">Please fix these errors:</div>
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>

