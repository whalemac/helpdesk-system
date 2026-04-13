@extends('layouts.helpdesk')

@section('header', 'Supervisor Dashboard')

@section('content')
{{-- Welcome Banner --}}
<div class="mb-8 flex items-center justify-between">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Team Overview</h2>
        <p class="mt-1 text-sm text-gray-500">Monitor your team's performance and escalated issues.</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('tickets.index') }}" class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 transition">View All Tickets</a>
        <a href="{{ route('reports.index') }}" class="rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 transition">View Reports</a>
    </div>
</div>

{{-- Stats --}}
<div class="grid grid-cols-1 gap-5 sm:grid-cols-3 mb-8">
    <div class="bg-white rounded-xl border border-amber-100 shadow-sm p-5">
        <p class="text-xs font-semibold text-amber-600 uppercase tracking-widest">Total Open Tickets</p>
        <p class="mt-3 text-4xl font-black text-amber-700">{{ $stats['open_tickets'] }}</p>
    </div>
    <div class="bg-white rounded-xl border border-red-100 shadow-sm p-5">
        <p class="text-xs font-semibold text-red-600 uppercase tracking-widest">High / Critical Priority</p>
        <p class="mt-3 text-4xl font-black text-red-700">{{ $stats['high_priority'] }}</p>
    </div>
    <div class="bg-white rounded-xl border border-emerald-100 shadow-sm p-5">
        <p class="text-xs font-semibold text-emerald-600 uppercase tracking-widest">Resolved This Week</p>
        <p class="mt-3 text-4xl font-black text-emerald-700">{{ $stats['resolved_this_week'] }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    {{-- Agent Performance Table --}}
    <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/60">
            <h3 class="text-sm font-bold text-gray-700 uppercase tracking-widest">Agent Performance</h3>
        </div>
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50/40">
                <tr>
                    <th class="py-3 pl-6 pr-3 text-left text-xs font-semibold text-gray-500 uppercase">Agent</th>
                    <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Role</th>
                    <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Assigned</th>
                    <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Resolved</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($agents as $agent)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-4 pl-6 pr-3">
                            <div class="flex items-center gap-2">
                                <div class="h-8 w-8 rounded-full bg-slate-700 flex items-center justify-center text-white text-xs font-bold">{{ substr($agent->name, 0, 1) }}</div>
                                <span class="text-sm font-medium text-gray-900">{{ $agent->name }}</span>
                            </div>
                        </td>
                        <td class="px-3 py-4 text-sm text-gray-500">{{ ucfirst($agent->role) }}</td>
                        <td class="px-3 py-4 text-sm font-bold text-gray-900">{{ $agent->assigned_tickets_count }}</td>
                        <td class="px-3 py-4 text-sm font-bold text-emerald-600">{{ $agent->resolved_tickets_count }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="py-8 text-center text-sm text-gray-400">No agents found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Escalated Tickets --}}
    <div class="rounded-xl border border-red-100 bg-white shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-red-100 bg-red-50/60 flex items-center gap-2">
            <svg class="h-4 w-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <h3 class="text-sm font-bold text-red-700 uppercase tracking-widest">Escalated Tickets</h3>
        </div>
        <table class="min-w-full divide-y divide-red-50">
            <thead class="bg-red-50/30">
                <tr>
                    <th class="py-3 pl-6 pr-3 text-left text-xs font-semibold text-gray-500 uppercase">#</th>
                    <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Subject</th>
                    <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Priority</th>
                    <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Assigned</th>
                    <th class="px-3 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($escalatedTickets as $ticket)
                    <tr class="hover:bg-red-50/30 transition">
                        <td class="py-4 pl-6 pr-3 text-sm text-gray-400 font-mono">#{{ $ticket->id }}</td>
                        <td class="px-3 py-4 text-sm font-medium text-gray-900">{{ Str::limit($ticket->subject, 30) }}</td>
                        <td class="px-3 py-4 text-xs font-black uppercase {{ $ticket->priority === 'critical' ? 'text-red-700' : 'text-orange-600' }}">{{ $ticket->priority }}</td>
                        <td class="px-3 py-4 text-sm text-gray-500">{{ $ticket->assignedUser->name ?? 'Unassigned' }}</td>
                        <td class="px-3 py-4 pr-6 text-right">
                            <a href="{{ route('tickets.show', $ticket) }}" class="text-red-600 text-sm font-semibold hover:underline">View</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="py-8 text-center text-sm text-gray-400">No escalated tickets. 🎉</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
