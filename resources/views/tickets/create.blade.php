@extends('layouts.helpdesk')

@section('header', 'Create New Ticket')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('tickets.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-900 flex items-center gap-1 transition">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Tickets
        </a>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
        <div class="px-8 py-6 border-b border-gray-100 bg-gray-50/50">
            <h3 class="text-lg font-semibold leading-6 text-gray-900">Ticket Details</h3>
            <p class="mt-1 text-sm text-gray-500">Log a new issue or request. Please provide as much detail as possible to help agents resolve it quickly.</p>
        </div>
        
        <form action="{{ route('tickets.store') }}" method="POST" class="px-8 py-6">
            @csrf
            <div class="grid grid-cols-1 gap-x-8 gap-y-6 sm:grid-cols-6">
                
                {{-- Left Column: Primary Details --}}
                <div class="sm:col-span-4 space-y-6">
                    <div>
                        <label class="block text-sm font-medium leading-6 text-gray-900">Subject / Issue Summary <span class="text-red-500">*</span></label>
                        <input type="text" name="subject" required value="{{ old('subject') }}" class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6">
                        @error('subject') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium leading-6 text-gray-900">Description <span class="text-red-500">*</span></label>
                        <textarea name="description" rows="6" required class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6">{{ old('description') }}</textarea>
                        @error('description') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- Right Column: Meta details --}}
                <div class="sm:col-span-2 space-y-6 rounded-lg bg-gray-50 p-6 border border-gray-100">
                    
                    <div>
                        <label class="block text-sm font-medium leading-6 text-gray-900">Requester <span class="text-red-500">*</span></label>
                        <select name="requester_id" required class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6">
                            <option value="">Select Requester...</option>
                            @foreach($requesters as $req)
                                <option value="{{ $req->id }}">{{ $req->first_name }} {{ $req->last_name }}</option>
                            @endforeach
                        </select>
                        @error('requester_id') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium leading-6 text-gray-900">Problem Category <span class="text-red-500">*</span></label>
                        <select name="category_id" required class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6">
                            <option value="">Select Category...</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium leading-6 text-gray-900">Priority</label>
                            <select name="priority" class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6">
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
                                <option value="critical">Critical</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium leading-6 text-gray-900">Status</label>
                            <select name="status" class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6">
                                <option value="open">Open</option>
                                <option value="in_progress">In Progress</option>
                                <option value="resolved">Resolved</option>
                            </select>
                        </div>
                    </div>

                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'supervisor')
                    <div class="border-t border-gray-200 mt-4 pt-4">
                        <label class="block text-sm font-medium leading-6 text-gray-900">Assign To Agent</label>
                        <select name="assigned_user_id" class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6">
                            <option value="">Unassigned</option>
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id }}">{{ $agent->name }} ({{ ucfirst($agent->role) }})</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                </div>
            </div>

            <div class="mt-8 flex items-center justify-end gap-x-4 border-t border-gray-100 pt-6">
                <a href="{{ route('tickets.index') }}" class="text-sm font-semibold leading-6 text-gray-900 hover:text-gray-600">Cancel</a>
                <button type="submit" class="rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition">
                    Submit Ticket
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
