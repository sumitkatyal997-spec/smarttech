<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - {{ config('app.name', 'SmartTech Inventory') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-900">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-md bg-white border rounded-lg p-6">
            <h1 class="text-xl font-semibold mb-1">Sign in</h1>
            <p class="text-sm text-gray-600 mb-6">Use your email and password.</p>

            @if ($errors->any())
                <div class="mb-4 rounded border border-red-200 bg-red-50 px-4 py-2 text-red-800 text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium mb-1">Email</label>
                    <input name="email" value="{{ old('email') }}" type="email" class="w-full rounded border px-3 py-2" required autofocus>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Password</label>
                    <input name="password" type="password" class="w-full rounded border px-3 py-2" required>
                </div>
                <div class="flex items-center justify-between">
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox" name="remember" class="rounded border">
                        Remember me
                    </label>
                </div>
                <button type="submit" class="w-full rounded bg-gray-900 text-white px-4 py-2 hover:bg-gray-800">
                    Login
                </button>
            </form>
        </div>
    </div>
</body>
</html>

