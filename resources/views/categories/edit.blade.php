@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-semibold">Edit Category</h1>
        <a class="text-sm hover:underline" href="{{ route('categories.index') }}">Back</a>
    </div>

    <div class="bg-white border rounded p-4 max-w-xl">
        <form method="POST" action="{{ route('categories.update', $category) }}" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-medium mb-1">Name</label>
                <input name="name" value="{{ old('name', $category->name) }}" class="w-full rounded border px-3 py-2" required>
            </div>
            <button class="px-4 py-2 rounded bg-gray-900 text-white hover:bg-gray-800" type="submit">Save</button>
        </form>
    </div>
@endsection

