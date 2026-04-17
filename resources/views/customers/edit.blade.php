@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-semibold">Edit Customer</h1>
        <a class="text-sm hover:underline" href="{{ route('customers.index') }}">Back</a>
    </div>

    <div class="bg-white border rounded p-4 max-w-xl">
        <form method="POST" action="{{ route('customers.update', $customer) }}" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-medium mb-1">Name</label>
                <input name="name" value="{{ old('name', $customer->name) }}" class="w-full rounded border px-3 py-2" required>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Phone</label>
                    <input name="phone" value="{{ old('phone', $customer->phone) }}" class="w-full rounded border px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Email</label>
                    <input name="email" value="{{ old('email', $customer->email) }}" type="email" class="w-full rounded border px-3 py-2">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Address</label>
                <textarea name="address" rows="3" class="w-full rounded border px-3 py-2">{{ old('address', $customer->address) }}</textarea>
            </div>
            <button class="px-4 py-2 rounded bg-gray-900 text-white hover:bg-gray-800" type="submit">Save</button>
        </form>
    </div>
@endsection

