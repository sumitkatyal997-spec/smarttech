@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-semibold">New Category</h1>
        <a class="text-sm hover:underline" href="{{ route('categories.index') }}">Back</a>
    </div>

    <div class="bg-white border rounded p-4 max-w-xl">
        <form method="POST" action="{{ route('categories.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-1">Name</label>
                <input name="name" value="{{ old('name') }}" class="w-full rounded border px-3 py-2" required>
            </div>
            <button class="px-4 py-2 rounded bg-gray-900 text-white hover:bg-gray-800" type="submit">Create</button>
        </form>
    </div>
@endsection

