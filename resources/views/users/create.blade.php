@extends('layouts.helpdesk')

@section('header', 'Add New User')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('users.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-900 flex items-center gap-1 transition">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Users
        </a>
    </div>
    <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
        <div class="px-8 py-6 border-b border-gray-100 bg-gray-50/50">
            <h3 class="text-lg font-semibold text-gray-900">Create User Account</h3>
            <p class="mt-1 text-sm text-gray-500">Add a new user and assign their system role.</p>
        </div>
        <form action="{{ route('users.store') }}" method="POST" class="px-8 py-6 space-y-6">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-900">Full Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" required value="{{ old('name') }}" class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-blue-600 sm:text-sm">
                @error('name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-900">Email Address <span class="text-red-500">*</span></label>
                <input type="email" name="email" required value="{{ old('email') }}" class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-blue-600 sm:text-sm">
                @error('email') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-900">Role <span class="text-red-500">*</span></label>
                <select name="role" required class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-blue-600 sm:text-sm">
                    <option value="">Select role...</option>
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="supervisor" {{ old('role') === 'supervisor' ? 'selected' : '' }}>Supervisor</option>
                    <option value="agent" {{ old('role') === 'agent' ? 'selected' : '' }}>Support Agent</option>
                    <option value="requester" {{ old('role') === 'requester' ? 'selected' : '' }}>Requester</option>
                </select>
                @error('role') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-900">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" required class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-blue-600 sm:text-sm">
                    @error('password') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-900">Confirm Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password_confirmation" required class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-blue-600 sm:text-sm">
                </div>
            </div>
            <div class="flex items-center justify-end gap-4 border-t border-gray-100 pt-6">
                <a href="{{ route('users.index') }}" class="text-sm font-semibold text-gray-900 hover:text-gray-600">Cancel</a>
                <button type="submit" class="rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 transition">Create User</button>
            </div>
        </form>
    </div>
</div>
@endsection
