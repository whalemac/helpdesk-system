@extends('layouts.helpdesk')

@section('header', 'Edit Requester')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('requesters.show', $requester) }}" class="text-sm font-medium text-gray-500 hover:text-gray-900 flex items-center gap-1 transition">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Requester
        </a>
    </div>
    <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
        <div class="px-8 py-6 border-b border-gray-100 bg-gray-50/50">
            <h3 class="text-lg font-semibold text-gray-900">Edit Requester</h3>
        </div>
        <form action="{{ route('requesters.update', $requester) }}" method="POST" class="px-8 py-6 space-y-6">
            @csrf @method('PATCH')
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-900">First Name <span class="text-red-500">*</span></label>
                    <input type="text" name="first_name" required value="{{ old('first_name', $requester->first_name) }}" class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-blue-600 sm:text-sm">
                    @error('first_name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-900">Last Name <span class="text-red-500">*</span></label>
                    <input type="text" name="last_name" required value="{{ old('last_name', $requester->last_name) }}" class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-blue-600 sm:text-sm">
                    @error('last_name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-900">Email Address <span class="text-red-500">*</span></label>
                <input type="email" name="email" required value="{{ old('email', $requester->email) }}" class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-blue-600 sm:text-sm">
                @error('email') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-900">Phone Number <span class="text-red-500">*</span></label>
                <input type="text" name="phone" required value="{{ old('phone', $requester->phone) }}" class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-blue-600 sm:text-sm">
                @error('phone') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-900">Department / Company</label>
                <input type="text" name="department" value="{{ old('department', $requester->department) }}" class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-blue-600 sm:text-sm">
            </div>
            <div class="flex items-center justify-end gap-4 border-t border-gray-100 pt-6">
                <a href="{{ route('requesters.show', $requester) }}" class="text-sm font-semibold text-gray-900 hover:text-gray-600">Cancel</a>
                <button type="submit" class="rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 transition">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
