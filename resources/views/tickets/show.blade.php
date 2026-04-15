@extends('layouts.helpdesk')

@section('header', 'Ticket Details')

@section('content')

{{-- Back + Edit Ticket --}}
<div class="mb-6 flex items-center justify-between">
    <a href="{{ route('tickets.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-900 flex items-center gap-1 transition">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Back to Queue
    </a>
    @if(in_array(auth()->user()->role, ['supervisor', 'agent']) && $ticket->status !== 'closed')
        <a href="{{ route('tickets.edit', $ticket) }}" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 transition">
            Edit Ticket
        </a>
    @endif
</div>

{{-- Validation Errors --}}
@if ($errors->any())
    <div class="mb-6 rounded-lg bg-red-50 border border-red-200 p-4">
        <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif

{{-- Closed ticket banner --}}
@if($ticket->status === 'closed')
    <div class="mb-6 rounded-lg bg-gray-100 border border-gray-300 p-4 text-sm text-gray-600 flex items-center gap-2">
        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
        This ticket is <strong>closed</strong>. Replies and status changes are disabled.
    </div>
@endif

<div class="flex flex-col lg:flex-row gap-8 items-start">

    {{-- ── LEFT: Thread ───────────────────────────────────────────────────── --}}
    <div class="flex-1 w-full space-y-6">

        {{-- Original Ticket --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex items-start justify-between bg-gray-50/50">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <span class="text-xs font-bold text-gray-500 tracking-wider">#{{ $ticket->id }}</span>
                        <h2 class="text-xl font-bold text-gray-900">{{ $ticket->subject }}</h2>
                    </div>
                    <div class="text-sm text-gray-500 flex items-center gap-2">
                        <span>Submitted by <span class="font-semibold text-gray-700">{{ $ticket->requester->first_name ?? 'Unknown' }} {{ $ticket->requester->last_name ?? '' }}</span></span>
                        <span>&middot;</span>
                        <span>{{ $ticket->created_at->format('M d, Y h:i A') }}</span>
                    </div>
                </div>
            </div>
            <div class="p-8 prose max-w-none text-gray-800">
                {!! nl2br(e($ticket->description)) !!}
            </div>
        </div>

        {{-- Reply Thread --}}
        <div class="space-y-6">
            @foreach($ticket->replies as $reply)
                @php
                    $isRequester = $reply->user->role === 'requester';
                    $isInternal  = $reply->reply_type === 'internal';
                    $avatarColor = $isRequester ? 'bg-gray-200 text-gray-600' : ($isInternal ? 'bg-amber-100 text-amber-700' : 'bg-blue-600 text-white');
                @endphp
                @if($isInternal && auth()->user()->role === 'requester')
                    @continue {{-- Requesters cannot see internal notes --}}
                @endif
                <div class="flex gap-4">
                    <div class="flex-shrink-0 mt-1">
                        <div class="h-10 w-10 rounded-full flex items-center justify-center font-bold text-sm {{ $avatarColor }}">
                            {{ substr($reply->user->name, 0, 1) }}
                        </div>
                    </div>
                    <div class="flex-1 rounded-xl shadow-sm border overflow-hidden {{ $isInternal ? 'border-amber-200' : 'border-gray-100' }}">
                        <div class="px-5 py-3 border-b flex items-center justify-between {{ $isInternal ? 'bg-amber-50/60 border-amber-100' : ($isRequester ? 'bg-gray-50/50 border-gray-50' : 'bg-blue-50/30 border-blue-50') }}">
                            <div class="text-sm font-semibold {{ $isRequester ? 'text-gray-900' : ($isInternal ? 'text-amber-800' : 'text-blue-900') }}">
                                {{ $reply->user->name }}
                                <span class="text-xs font-normal text-gray-500 ml-1">({{ ucfirst($reply->user->role) }})</span>
                                @if($isInternal)
                                    <span class="ml-2 inline-flex items-center rounded bg-amber-100 px-1.5 py-0.5 text-xs font-bold text-amber-700">Internal Note</span>
                                @endif
                            </div>
                            <div class="text-xs text-gray-500">{{ $reply->created_at->diffForHumans() }}</div>
                        </div>
                        <div class="p-5 text-sm text-gray-800 whitespace-pre-wrap">{{ $reply->message }}</div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Reply Form --}}
        @if($ticket->status !== 'closed')
            <div class="bg-white rounded-xl shadow-sm border border-blue-100 overflow-hidden mt-8">
                <div class="bg-blue-50/50 px-6 py-4 border-b border-blue-100">
                    <h3 class="text-sm font-semibold text-blue-900">Post a Reply</h3>
                </div>
                <form action="{{ route('tickets.replies.store', $ticket) }}" method="POST" class="p-6">
                    @csrf
                    <textarea name="message" rows="4" required
                        placeholder="Type your response here..."
                        class="block w-full rounded-md border-0 py-3 px-4 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"></textarea>

                    <div class="mt-4 flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <span class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Reply Type:</span>
                            <label class="flex items-center gap-1.5 text-sm text-gray-700 cursor-pointer">
                                <input type="radio" name="reply_type" value="public" checked class="text-blue-600"> Public
                            </label>
                            @if(auth()->user()->role !== 'requester')
                                <label class="flex items-center gap-1.5 text-sm text-gray-700 cursor-pointer">
                                    <input type="radio" name="reply_type" value="internal" class="text-blue-600"> Internal Note
                                </label>
                            @endif
                        </div>
                        <button type="submit" class="rounded-md bg-blue-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 transition">
                            Send Message
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </div>

    {{-- ── RIGHT: Sidebar ─────────────────────────────────────────────────── --}}
    <div class="w-full lg:w-80 flex-shrink-0 space-y-6">

        {{-- Status Widget --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-5 bg-gray-50/80 border-b border-gray-100">
                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Ticket Management</h3>
            </div>
            <div class="p-5 space-y-5">

                {{-- Status Update: Agents (assigned only) + Admin/Supervisor --}}
                @php
                    $canUpdateStatus = auth()->user()->role === 'admin'
                        || auth()->user()->role === 'supervisor'
                        || (auth()->user()->role === 'agent' && $ticket->assigned_user_id === auth()->id());
                @endphp

                @if($canUpdateStatus && $ticket->status !== 'closed')
                    <form action="{{ route('tickets.update-status', $ticket) }}" method="POST">
                        @csrf @method('PATCH')
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Update Status</label>
                        <div class="flex gap-2">
                            <select name="status" class="block flex-1 rounded-md border-0 py-2 pl-3 pr-8 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-blue-600 sm:text-sm font-medium bg-gray-50">
                                @foreach(['open', 'in_progress', 'pending', 'resolved', 'closed'] as $s)
                                    <option value="{{ $s }}" {{ $ticket->status === $s ? 'selected' : '' }}>
                                        {{ str_replace('_', ' ', ucfirst($s)) }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="rounded-md bg-blue-600 px-3 py-2 text-xs font-bold text-white hover:bg-blue-500 transition">Set</button>
                        </div>
                    </form>
                @else
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Status</label>
                        @php
                            $sc = ['open'=>'bg-blue-50 text-blue-700','in_progress'=>'bg-amber-50 text-amber-700','pending'=>'bg-purple-50 text-purple-700','resolved'=>'bg-emerald-50 text-emerald-700','closed'=>'bg-gray-100 text-gray-600'];
                        @endphp
                        <span class="inline-flex items-center rounded-md px-3 py-1 text-sm font-medium {{ $sc[$ticket->status] ?? 'bg-gray-50' }}">
                            {{ str_replace('_', ' ', strtoupper($ticket->status)) }}
                        </span>
                    </div>
                @endif

                {{-- Priority --}}
                <div class="pt-4 border-t border-gray-100">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Priority</label>
                    @php
                        $pc = ['low'=>'bg-gray-100 text-gray-600','medium'=>'bg-blue-100 text-blue-700','high'=>'bg-orange-100 text-orange-700 ring-1 ring-orange-600/20','critical'=>'bg-red-100 text-red-800 ring-1 ring-red-600/20'];
                    @endphp
                    <span class="inline-flex items-center px-3 py-1 font-semibold text-sm rounded-full {{ $pc[$ticket->priority] ?? 'bg-gray-100' }}">
                        {{ strtoupper($ticket->priority) }}
                    </span>
                </div>

                {{-- Reassign widget (Admin + Supervisor) --}}
                @if(in_array(auth()->user()->role, ['admin', 'supervisor']))
                    <div class="pt-4 border-t border-gray-100">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Reassign Ticket</label>
                        <form action="{{ route('tickets.reassign', $ticket) }}" method="POST">
                            @csrf @method('PATCH')
                            <select name="assigned_user_id" class="block w-full rounded-md border-0 py-2 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-blue-600 sm:text-sm font-medium bg-gray-50 mb-2">
                                <option value="">— Unassigned —</option>
                                @foreach($agents as $agent)
                                    <option value="{{ $agent->id }}" {{ $ticket->assigned_user_id == $agent->id ? 'selected' : '' }}>
                                        {{ $agent->name }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="w-full rounded-md bg-slate-700 px-3 py-2 text-xs font-bold text-white hover:bg-slate-600 transition">
                                Reassign
                            </button>
                        </form>
                    </div>
                @else
                    {{-- Read-only assignment view for agents/requesters --}}
                    <div class="pt-4 border-t border-gray-100">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Assigned Agent</label>
                        @if($ticket->assignedUser)
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center font-bold text-blue-700 text-xs">
                                    {{ substr($ticket->assignedUser->name, 0, 1) }}
                                </div>
                                <div class="text-sm font-medium text-gray-900">{{ $ticket->assignedUser->name }}</div>
                            </div>
                        @else
                            <div class="text-sm text-gray-500 border border-dashed border-gray-300 rounded px-3 py-1.5 bg-gray-50 italic">Unassigned</div>
                        @endif
                    </div>
                @endif

                {{-- Category --}}
                <div class="pt-4 border-t border-gray-100">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Category</label>
                    <div class="text-sm text-gray-900">{{ $ticket->category->name ?? 'Uncategorized' }}</div>
                </div>

                {{-- Requester info --}}
                <div class="pt-4 border-t border-gray-100">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Requester</label>
                    <div class="text-sm font-medium text-gray-900">{{ $ticket->requester->first_name ?? '—' }} {{ $ticket->requester->last_name ?? '' }}</div>
                    <div class="text-xs text-gray-500 mt-0.5">{{ $ticket->requester->email ?? '' }}</div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
