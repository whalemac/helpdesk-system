@extends('layouts.helpdesk')

@section('header', 'User Management')

@section('content')
<div class="sm:flex sm:items-center sm:justify-between mb-8">
    <div>
        <h2 class="text-base font-semibold leading-6 text-gray-900">System Users</h2>
        <p class="mt-2 text-sm text-gray-700">Manage all user accounts and their roles in the system.</p>
    </div>
    <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
        <a href="{{ route('users.create') }}" class="block rounded-lg bg-blue-600 px-4 py-2.5 text-center text-sm font-semibold text-white shadow-sm hover:bg-blue-500 transition">
            + Add User
        </a>
    </div>
</div>

<div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50/50">
            <tr>
                <th class="py-4 pl-6 pr-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">User</th>
                <th class="px-3 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                <th class="px-3 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Role</th>
                <th class="px-3 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Joined</th>
                <th class="relative py-4 pl-3 pr-6"><span class="sr-only">Actions</span></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 bg-white">
            @forelse($users as $user)
                <tr class="hover:bg-gray-50 transition">
                    <td class="whitespace-nowrap py-4 pl-6 pr-3">
                        <div class="flex items-center gap-3">
                            <div class="h-9 w-9 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div class="font-medium text-gray-900">{{ $user->name }}
                                @if($user->id === auth()->id())
                                    <span class="ml-2 text-xs text-blue-600 font-semibold">(You)</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-600">{{ $user->email }}</td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm">
                        @php
                            $roleColors = [
                                'admin' => 'bg-red-50 text-red-700 ring-red-600/20',
                                'supervisor' => 'bg-purple-50 text-purple-700 ring-purple-600/20',
                                'agent' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
                                'requester' => 'bg-gray-50 text-gray-700 ring-gray-600/20',
                            ];
                        @endphp
                        <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $roleColors[$user->role] ?? 'bg-gray-50' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $user->created_at->format('M d, Y') }}</td>
                    <td class="relative whitespace-nowrap py-4 pl-3 pr-6 text-right text-sm font-medium">
                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('users.edit', $user) }}" class="text-blue-600 hover:text-blue-800 font-semibold">Edit</a>
                            @if($user->id !== auth()->id())
                                <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Delete this user?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-semibold">Delete</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="py-12 text-center text-sm text-gray-500">No users found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
