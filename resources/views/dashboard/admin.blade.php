@extends('layouts.helpdesk')

@section('header', 'Admin Dashboard')

@section('content')
{{-- Welcome Banner --}}
<div class="mb-8 flex items-center justify-between">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Welcome back, {{ auth()->user()->name }} 👋</h2>
        <p class="mt-1 text-sm text-gray-500">Here's a full system overview for today.</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('users.create') }}" class="rounded-lg bg-slate-800 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-slate-700 transition flex items-center gap-2">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add User
        </a>
        <a href="{{ route('categories.index') }}" class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 transition flex items-center gap-2">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
            Manage Categories
        </a>
    </div>
</div>

{{-- Stats Grid --}}
<div class="grid grid-cols-2 gap-5 sm:grid-cols-3 lg:grid-cols-5 mb-8">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
        <p class="text-xs font-semibold text-amber-600 uppercase tracking-widest">Open Tickets</p>
        <p class="mt-3 text-4xl font-black text-amber-700">{{ $stats['open_tickets'] }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
        <p class="text-xs font-semibold text-emerald-600 uppercase tracking-widest">Resolved</p>
        <p class="mt-3 text-4xl font-black text-emerald-700">{{ $stats['resolved_tickets'] }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
        <p class="text-xs font-semibold text-red-600 uppercase tracking-widest">High Priority</p>
        <p class="mt-3 text-4xl font-black text-red-700">{{ $stats['high_priority'] }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
        <p class="text-xs font-semibold text-blue-600 uppercase tracking-widest">Total Users</p>
        <p class="mt-3 text-4xl font-black text-blue-700">{{ $stats['total_users'] }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
        <p class="text-xs font-semibold text-purple-600 uppercase tracking-widest">Categories</p>
        <p class="mt-3 text-4xl font-black text-purple-700">{{ $stats['total_categories'] }}</p>
    </div>
</div>

{{-- Recent Tickets Table --}}
<div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/60 flex items-center justify-between">
        <h3 class="text-sm font-bold text-gray-700 uppercase tracking-widest">Recent Tickets</h3>
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
                    <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Assigned</th>
                    <th class="px-3 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($recentTickets as $ticket)
                    @php
                        $statusColors = ['open'=>'bg-blue-50 text-blue-700','in_progress'=>'bg-amber-50 text-amber-700','pending'=>'bg-purple-50 text-purple-700','resolved'=>'bg-emerald-50 text-emerald-700','closed'=>'bg-gray-100 text-gray-600'];
                        $priorityColors = ['low'=>'text-gray-400','medium'=>'text-blue-500 font-semibold','high'=>'text-orange-600 font-bold','critical'=>'text-red-700 font-black'];
                    @endphp
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-4 pl-6 pr-3 text-sm text-gray-400 font-mono">#{{ $ticket->id }}</td>
                        <td class="px-3 py-4 text-sm font-medium text-gray-900">{{ Str::limit($ticket->subject, 40) }}</td>
                        <td class="px-3 py-4 text-sm text-gray-600">{{ $ticket->requester->first_name ?? '—' }} {{ $ticket->requester->last_name ?? '' }}</td>
                        <td class="px-3 py-4 text-xs uppercase {{ $priorityColors[$ticket->priority] ?? '' }}">{{ $ticket->priority }}</td>
                        <td class="px-3 py-4">
                            <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium {{ $statusColors[$ticket->status] ?? 'bg-gray-50 text-gray-600' }}">
                                {{ str_replace('_', ' ', $ticket->status) }}
                            </span>
                        </td>
                        <td class="px-3 py-4 text-sm text-gray-500">{{ $ticket->assignedUser->name ?? '—' }}</td>
                        <td class="px-3 py-4 text-right pr-6">
                            <a href="{{ route('tickets.show', $ticket) }}" class="text-blue-600 text-sm font-semibold hover:underline">View</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="py-10 text-center text-sm text-gray-400">No tickets yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
