@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-semibold">New Supplier</h1>
        <a class="text-sm hover:underline" href="{{ route('suppliers.index') }}">Back</a>
    </div>

    <div class="bg-white border rounded p-4 max-w-xl">
        <form method="POST" action="{{ route('suppliers.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-1">Name</label>
                <input name="name" value="{{ old('name') }}" class="w-full rounded border px-3 py-2" required>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Phone</label>
                    <input name="phone" value="{{ old('phone') }}" class="w-full rounded border px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Email</label>
                    <input name="email" value="{{ old('email') }}" type="email" class="w-full rounded border px-3 py-2">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Address</label>
                <textarea name="address" rows="3" class="w-full rounded border px-3 py-2">{{ old('address') }}</textarea>
            </div>
            <button class="px-4 py-2 rounded bg-gray-900 text-white hover:bg-gray-800" type="submit">Create</button>
        </form>
    </div>
@endsection

