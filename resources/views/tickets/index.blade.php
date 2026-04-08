@extends('layouts.helpdesk')

@section('header', 'Manage Tickets')

@section('content')
<div class="sm:flex sm:items-center sm:justify-between mb-8">
    <div>
        <h2 class="text-base font-semibold leading-6 text-gray-900">Support Tickets</h2>
        <p class="mt-2 text-sm text-gray-700">A list of all support tickets tracking customer issues and requests.</p>
    </div>
    <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
        <a href="{{ route('tickets.create') }}" class="block rounded-lg bg-blue-600 px-4 py-2.5 text-center text-sm font-semibold text-white shadow-sm hover:bg-blue-500 transition">
            + New Ticket
        </a>
    </div>
</div>

<div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50/50">
                <tr>
                    <th scope="col" class="py-4 pl-6 pr-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ticket Details</th>
                    <th scope="col" class="px-3 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Requester</th>
                    <th scope="col" class="px-3 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status & Priority</th>
                    <th scope="col" class="px-3 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Assigned To</th>
                    <th scope="col" class="relative py-4 pl-3 pr-6">
                        <span class="sr-only">Actions</span>
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @forelse($tickets as $ticket)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="whitespace-nowrap py-5 pl-6 pr-3">
                            <div class="text-sm font-bold text-gray-900 flex items-center gap-2">
                                #{{ $ticket->id }} - {{ Str::limit($ticket->subject, 30) }}
                            </div>
                            <div class="text-xs text-gray-500 mt-1">
                                Category: <span class="font-medium">{{ $ticket->category->name ?? 'Uncategorized' }}</span> &middot; {{ $ticket->created_at->diffForHumans() }}
                            </div>
                        </td>
                        <td class="whitespace-nowrap px-3 py-5 text-sm">
                            <div class="font-medium text-gray-900">{{ $ticket->requester->first_name ?? 'Unknown' }} {{ $ticket->requester->last_name ?? '' }}</div>
                            <div class="text-gray-500 text-xs">{{ $ticket->requester->department ?? '' }}</div>
                        </td>
                        <td class="whitespace-nowrap px-3 py-5 text-sm">
                            @php
                                $statusColors = [
                                    'open' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
                                    'in_progress' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
                                    'pending' => 'bg-purple-50 text-purple-700 ring-purple-600/20',
                                    'resolved' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                                    'closed' => 'bg-gray-50 text-gray-700 ring-gray-600/20',
                                ];
                                $priorityColors = [
                                    'low' => 'text-gray-500',
                                    'medium' => 'text-blue-600 font-medium',
                                    'high' => 'text-red-600 font-bold flex items-center gap-1',
                                ];
                            @endphp
                            <div class="flex items-center gap-3">
                                <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $statusColors[$ticket->status] ?? 'bg-gray-50' }}">
                                    {{ str_replace('_', ' ', strtoupper($ticket->status)) }}
                                </span>
                                <span class="text-xs uppercase {{ $priorityColors[$ticket->priority] ?? 'text-gray-500' }}">
                                    @if($ticket->priority === 'high')
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    @endif
                                    {{ $ticket->priority }}
                                </span>
                            </div>
                        </td>
                        <td class="whitespace-nowrap px-3 py-5 text-sm text-gray-500">
                            {{ $ticket->assignedUser ? $ticket->assignedUser->name : 'Unassigned' }}
                        </td>
                        <td class="relative whitespace-nowrap py-5 pl-3 pr-6 text-right text-sm font-medium">
                            <a href="{{ route('tickets.show', $ticket) }}" class="text-blue-600 hover:text-blue-900 font-semibold border border-blue-600 px-3 py-1.5 rounded-lg hover:bg-blue-50 transition">View Details</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center text-sm text-gray-500">
                            No tickets currently exist in your scope.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
