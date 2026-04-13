@extends('layouts.helpdesk')

@section('header', 'My Dashboard')

@section('content')
{{-- Welcome Banner --}}
<div class="mb-8 flex items-center justify-between">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Your Queue, {{ auth()->user()->name }}</h2>
        <p class="mt-1 text-sm text-gray-500">Tickets currently assigned to you.</p>
    </div>
    <a href="{{ route('tickets.index') }}" class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 transition flex items-center gap-2">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5"/></svg>
        View My Tickets
    </a>
</div>

{{-- Stats --}}
<div class="grid grid-cols-1 gap-5 sm:grid-cols-3 mb-8">
    <div class="bg-white rounded-xl border border-amber-100 shadow-sm p-5 flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-amber-600 uppercase tracking-widest">Open Assigned</p>
            <p class="mt-3 text-4xl font-black text-amber-700">{{ $stats['open_assigned'] }}</p>
        </div>
        <div class="h-12 w-12 rounded-full bg-amber-50 flex items-center justify-center text-amber-600">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-blue-100 shadow-sm p-5 flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-blue-600 uppercase tracking-widest">In Progress</p>
            <p class="mt-3 text-4xl font-black text-blue-700">{{ $stats['in_progress'] }}</p>
        </div>
        <div class="h-12 w-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-emerald-100 shadow-sm p-5 flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-emerald-600 uppercase tracking-widest">Resolved Today</p>
            <p class="mt-3 text-4xl font-black text-emerald-700">{{ $stats['resolved_today'] }}</p>
        </div>
        <div class="h-12 w-12 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
    </div>
</div>

{{-- My Assigned Tickets --}}
<div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/60 flex items-center justify-between">
        <h3 class="text-sm font-bold text-gray-700 uppercase tracking-widest">My Active Tickets</h3>
        <a href="{{ route('tickets.index') }}" class="text-sm text-blue-600 font-semibold hover:underline">View All →</a>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50/40">
                <tr>
                    <th class="py-3 pl-6 pr-3 text-left text-xs font-semibold text-gray-500 uppercase">#</th>
                    <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Subject</th>
                    <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Requester</th>
                    <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Priority</th>
                    <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="px-3 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($myTickets as $ticket)
                    @php
                        $statusColors = ['open'=>'bg-blue-50 text-blue-700','in_progress'=>'bg-amber-50 text-amber-700','pending'=>'bg-purple-50 text-purple-700','resolved'=>'bg-emerald-50 text-emerald-700','closed'=>'bg-gray-100 text-gray-600'];
                        $priorityColors = ['low'=>'text-gray-400','medium'=>'text-blue-500','high'=>'text-orange-600 font-bold','critical'=>'text-red-700 font-black'];
                    @endphp
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-4 pl-6 pr-3 text-sm text-gray-400 font-mono">#{{ $ticket->id }}</td>
                        <td class="px-3 py-4 text-sm font-medium text-gray-900">{{ Str::limit($ticket->subject, 40) }}</td>
                        <td class="px-3 py-4 text-sm text-gray-600">{{ $ticket->requester->first_name ?? '—' }}</td>
                        <td class="px-3 py-4 text-xs uppercase {{ $priorityColors[$ticket->priority] ?? '' }}">{{ $ticket->priority }}</td>
                        <td class="px-3 py-4">
                            <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium {{ $statusColors[$ticket->status] ?? 'bg-gray-50 text-gray-600' }}">
                                {{ str_replace('_', ' ', $ticket->status) }}
                            </span>
                        </td>
                        <td class="px-3 py-4 text-right pr-6">
                            <a href="{{ route('tickets.show', $ticket) }}" class="text-blue-600 text-sm font-semibold hover:underline">View</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="py-10 text-center text-sm text-gray-400">No tickets assigned to you yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
