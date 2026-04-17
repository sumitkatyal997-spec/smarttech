@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-semibold">Customers</h1>
        <a class="px-3 py-2 rounded bg-gray-900 text-white hover:bg-gray-800" href="{{ route('customers.create') }}">New Customer</a>
    </div>

    <div class="bg-white border rounded overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="text-left text-gray-600">
                <tr>
                    <th class="py-2 px-4">Name</th>
                    <th class="py-2 px-4">Phone</th>
                    <th class="py-2 px-4">Email</th>
                    <th class="py-2 px-4 w-48">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($customers as $customer)
                    <tr class="border-t">
                        <td class="py-2 px-4">{{ $customer->name }}</td>
                        <td class="py-2 px-4">{{ $customer->phone }}</td>
                        <td class="py-2 px-4">{{ $customer->email }}</td>
                        <td class="py-2 px-4 flex gap-2">
                            <a class="px-3 py-1.5 rounded border bg-white hover:bg-gray-50" href="{{ route('customers.edit', $customer) }}">Edit</a>
                            <form method="POST" action="{{ route('customers.destroy', $customer) }}" onsubmit="return confirm('Delete this customer?');">
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
        {{ $customers->links() }}
    </div>
@endsection

