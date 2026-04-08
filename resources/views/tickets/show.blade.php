@extends('layouts.helpdesk')

@section('header', 'Ticket Details')

@section('content')
<div class="mb-6">
    <a href="{{ route('tickets.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-900 flex items-center gap-1 transition">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Back to Queue
    </a>
</div>

<div class="flex flex-col lg:flex-row gap-8 items-start">
    <!-- Left Column: The Thread -->
    <div class="flex-1 w-full space-y-6">
        
        <!-- Original Issue -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex items-start justify-between bg-gray-50/50">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <span class="text-xs font-bold text-gray-500 tracking-wider">#{{ $ticket->id }}</span>
                        <h2 class="text-xl font-bold text-gray-900">{{ $ticket->subject }}</h2>
                    </div>
                    <div class="text-sm text-gray-500 flex items-center gap-2">
                        <span>Submitted by <span class="font-semibold text-gray-700">{{ $ticket->requester->first_name ?? 'Unknown' }}</span></span>
                        <span>&middot;</span>
                        <span>{{ $ticket->created_at->format('M d, Y h:i A') }}</span>
                    </div>
                </div>
            </div>
            <div class="p-8 prose max-w-none text-gray-800">
                {!! nl2br(e($ticket->description)) !!}
            </div>
        </div>

        <!-- Responses Thread -->
        <div class="space-y-6">
            @foreach($ticket->replies as $reply)
                <div class="flex gap-4">
                    <div class="flex-shrink-0 mt-1">
                        @php
                            // Determine avatar style based on user role
                            $isCustomer = $reply->user->role === 'requester';
                            $avatarColor = $isCustomer ? 'bg-gray-200 text-gray-600' : 'bg-blue-600 text-white';
                        @endphp
                        <div class="h-10 w-10 rounded-full flex items-center justify-center font-bold text-sm {{ $avatarColor }}">
                            {{ substr($reply->user->name, 0, 1) }}
                        </div>
                    </div>
                    <div class="flex-1 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-5 py-3 border-b border-gray-50 flex items-center justify-between {{ $isCustomer ? 'bg-gray-50/50' : 'bg-blue-50/30' }}">
                            <div class="text-sm font-semibold {{ $isCustomer ? 'text-gray-900' : 'text-blue-900' }}">
                                {{ $reply->user->name }} <span class="text-xs font-normal text-gray-500 ml-1">({{ ucfirst($reply->user->role) }})</span>
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $reply->created_at->diffForHumans() }}
                            </div>
                        </div>
                        <div class="p-5 text-sm text-gray-800 whitespace-pre-wrap">{{ $reply->message }}</div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Add Reply Form -->
        <div class="bg-white rounded-xl shadow-sm border border-blue-100 overflow-hidden mt-8">
            <div class="bg-blue-50/50 px-6 py-4 border-b border-blue-100">
                <h3 class="text-sm font-semibold text-blue-900">Post a Reply</h3>
            </div>
            <form action="{{ route('tickets.replies.store', $ticket) }}" method="POST" class="p-6">
                @csrf
                <textarea name="message" rows="4" required placeholder="Type your response here..." class="block w-full rounded-md border-0 py-3 px-4 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"></textarea>
                
                <div class="mt-4 flex items-center justify-between">
                    <div class="text-xs text-gray-500">Responses are visible to the requester.</div>
                    <button type="submit" class="rounded-md bg-blue-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 transition">
                        Send Message
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Right Column: Meta Information -->
    <div class="w-full lg:w-80 flex-shrink-0 space-y-6">
        
        <!-- Status Widget -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-5 bg-gray-50/80 border-b border-gray-100">
                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Ticket Management</h3>
            </div>
            <div class="p-5 space-y-5">
                
                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'supervisor' || auth()->user()->role === 'agent')
                    <form action="{{ route('tickets.update-status', $ticket) }}" method="POST">
                        @csrf @method('PATCH')
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Current Status</label>
                        <select name="status" onchange="this.form.submit()" class="block w-full rounded-md border-0 py-2 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-blue-600 sm:text-sm sm:leading-6 font-medium bg-gray-50">
                            <option value="open" {{ $ticket->status === 'open' ? 'selected' : '' }}>Open</option>
                            <option value="in_progress" {{ $ticket->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="pending" {{ $ticket->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="resolved" {{ $ticket->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </form>
                @else
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Status</label>
                        <span class="inline-flex items-center rounded-md bg-gray-100 px-3 py-1 text-sm font-medium text-gray-800">
                            {{ str_replace('_', ' ', strtoupper($ticket->status)) }}
                        </span>
                    </div>
                @endif

                <div class="pt-4 border-t border-gray-100">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Priority</label>
                    <span class="inline-flex items-center px-3 py-1 font-semibold text-sm rounded-full 
                        {{ $ticket->priority === 'low' ? 'bg-gray-100 text-gray-600' : '' }}
                        {{ $ticket->priority === 'medium' ? 'bg-blue-100 text-blue-700' : '' }}
                        {{ $ticket->priority === 'high' ? 'bg-red-100 text-red-700 ring-1 ring-red-600/20' : '' }}">
                        {{ strtoupper($ticket->priority) }}
                    </span>
                </div>

                <div class="pt-4 border-t border-gray-100">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Assigned Agent</label>
                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'supervisor')
                        <form action="{{ route('tickets.assign', $ticket) }}" method="POST">
                            @csrf @method('PATCH')
                            <select name="assigned_user_id" onchange="this.form.submit()" class="block w-full rounded-md border-0 py-2 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-blue-600 sm:text-sm sm:leading-6 font-medium bg-gray-50">
                                <option value="">Unassigned</option>
                                @foreach($agents as $agent)
                                    <option value="{{ $agent->id }}" {{ $ticket->assigned_user_id == $agent->id ? 'selected' : '' }}>
                                        {{ $agent->name }} ({{ ucfirst($agent->role) }})
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    @else
                        <div class="flex items-center gap-3">
                            @if($ticket->assignedUser)
                                <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center font-bold text-blue-700 text-xs">
                                    {{ substr($ticket->assignedUser->name, 0, 1) }}
                                </div>
                                <div class="text-sm font-medium text-gray-900">{{ $ticket->assignedUser->name }}</div>
                            @else
                                <div class="text-sm text-gray-500 border border-dashed border-gray-300 rounded px-3 py-1.5 bg-gray-50 italic">Unassigned</div>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="pt-4 border-t border-gray-100">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Category</label>
                    <div class="text-sm text-gray-900">{{ $ticket->category->name ?? 'Uncategorized' }}</div>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
