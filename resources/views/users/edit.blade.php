@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-semibold">Edit User</h1>
        <a class="text-sm hover:underline" href="{{ route('users.index') }}">Back</a>
    </div>

    <div class="bg-white border rounded p-4 max-w-xl">
        <form method="POST" action="{{ route('users.update', $user) }}" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-medium mb-1">Name</label>
                <input name="name" value="{{ old('name', $user->name) }}" class="w-full rounded border px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Email</label>
                <input name="email" type="email" value="{{ old('email', $user->email) }}" class="w-full rounded border px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Role</label>
                <select name="role" class="w-full rounded border px-3 py-2" required>
                    <option value="staff" @selected(old('role', $user->role) === 'staff')>Staff</option>
                    <option value="admin" @selected(old('role', $user->role) === 'admin')>Admin</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">New password (optional)</label>
                <input name="password" type="password" class="w-full rounded border px-3 py-2" placeholder="Leave blank to keep existing password">
                <div class="text-xs text-gray-600 mt-1">Minimum 6 characters.</div>
            </div>
            <button class="px-4 py-2 rounded bg-gray-900 text-white hover:bg-gray-800" type="submit">Save</button>
        </form>
    </div>
@endsection

