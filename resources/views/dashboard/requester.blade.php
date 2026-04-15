@extends('layouts.helpdesk')

@section('header', 'My Support Tickets')

@section('content')
    {{-- Welcome + Submit CTA --}}
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Hello, {{ auth()->user()->name }} 👋</h2>
            <p class="mt-1 text-sm text-gray-500">Track the status of your support requests below.</p>
        </div>
        <a href="{{ route('tickets.create') }}"
            class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 transition flex items-center gap-2">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            + Submit New Ticket
        </a>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-3 mb-8">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-widest">Total Submitted</p>
            <p class="mt-3 text-4xl font-black text-gray-900">{{ $stats['total_submitted'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-amber-100 shadow-sm p-5">
            <p class="text-xs font-semibold text-amber-600 uppercase tracking-widest">Pending / Open</p>
            <p class="mt-3 text-4xl font-black text-amber-700">{{ $stats['pending_tickets'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-emerald-100 shadow-sm p-5">
            <p class="text-xs font-semibold text-emerald-600 uppercase tracking-widest">Resolved</p>
            <p class="mt-3 text-4xl font-black text-emerald-700">{{ $stats['resolved_tickets'] }}</p>
        </div>
    </div>

    {{-- My Ticket List --}}
    <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/60">
            <h3 class="text-sm font-bold text-gray-700 uppercase tracking-widest">My Submissions</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50/40">
                    <tr>
                        <th class="py-3 pl-6 pr-3 text-left text-xs font-semibold text-gray-500 uppercase">#</th>
                        <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Subject</th>
                        <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Category</th>
                        <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Priority</th>
                        <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                        <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Submitted</th>
                        <th class="px-3 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($myTickets as $ticket)
                        @php
                            $statusColors = ['open' => 'bg-blue-50 text-blue-700', 'in_progress' => 'bg-amber-50 text-amber-700', 'pending' => 'bg-purple-50 text-purple-700', 'resolved' => 'bg-emerald-50 text-emerald-700', 'closed' => 'bg-gray-100 text-gray-600'];
                            $priorityColors = ['low' => 'text-gray-400', 'medium' => 'text-blue-500', 'high' => 'text-orange-600 font-bold', 'critical' => 'text-red-700 font-black'];
                        @endphp
                        <tr class="hover:bg-gray-50 transition">
                            <td class="py-4 pl-6 pr-3 text-sm text-gray-400 font-mono">#{{ $ticket->id }}</td>
                            <td class="px-3 py-4 text-sm font-medium text-gray-900">{{ Str::limit($ticket->subject, 40) }}</td>
                            <td class="px-3 py-4 text-sm text-gray-500">{{ $ticket->category->name ?? '—' }}</td>
                            <td class="px-3 py-4 text-xs uppercase {{ $priorityColors[$ticket->priority] ?? '' }}">
                                {{ $ticket->priority }}</td>
                            <td class="px-3 py-4">
                                <span
                                    class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium {{ $statusColors[$ticket->status] ?? 'bg-gray-50 text-gray-600' }}">
                                    {{ str_replace('_', ' ', $ticket->status) }}
                                </span>
                            </td>
                            <td class="px-3 py-4 text-sm text-gray-400">{{ $ticket->created_at->format('M d, Y') }}</td>
                            <td class="px-3 py-4 text-right pr-6">
                                <a href="{{ route('tickets.show', $ticket) }}"
                                    class="text-blue-600 text-sm font-semibold hover:underline">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-16 text-center">
                                <div class="text-gray-400 text-sm">You haven't submitted any tickets yet.</div>
                                <a href="{{ route('tickets.create') }}"
                                    class="mt-4 inline-flex rounded-lg bg-blue-600 px-5 py-2 text-sm font-bold text-white hover:bg-blue-500 transition">Submit
                                    Your First Ticket</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection