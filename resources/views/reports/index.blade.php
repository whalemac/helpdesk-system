@extends('layouts.helpdesk')

@section('header', 'System Analytics & Reports')

@section('content')
<div class="sm:flex sm:items-center sm:justify-between mb-8">
    <div>
        <h2 class="text-base font-semibold leading-6 text-gray-900">Analytics Overview</h2>
        <p class="mt-2 text-sm text-gray-700">Realtime breakdown of the Helpdesk system workload, assignments, and structural data.</p>
    </div>
</div>

<div class="space-y-8">
    
    <!-- Top KPI Grid -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-widest">Total Volume</p>
                <p class="mt-2 text-4xl font-black text-gray-900">{{ $totalTickets }}</p>
            </div>
            <div class="h-12 w-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </div>
        </div>
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-amber-600 uppercase tracking-widest">Currently Open</p>
                <p class="mt-2 text-4xl font-black text-amber-700">{{ $openTickets }}</p>
            </div>
            <div class="h-12 w-12 rounded-full bg-amber-50 flex items-center justify-center text-amber-600">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-emerald-600 uppercase tracking-widest">Resolved</p>
                <p class="mt-2 text-4xl font-black text-emerald-700">{{ $resolvedTickets }}</p>
            </div>
            <div class="h-12 w-12 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Category Distribution -->
        <div class="rounded-xl border border-gray-200 bg-white overflow-hidden shadow-sm">
            <div class="bg-gray-50/80 px-6 py-4 border-b border-gray-100">
                <h3 class="text-sm font-bold tracking-widest text-gray-500 uppercase">Tickets by Category</h3>
            </div>
            <div class="p-6">
                <ul class="space-y-4">
                    @forelse($ticketsByCategory as $cat)
                        <li class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-800">{{ $cat->name }}</span>
                            <span class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-xs font-bold text-gray-600 border border-gray-200">
                                {{ $cat->tickets_count }}
                            </span>
                        </li>
                    @empty
                        <li class="text-sm text-gray-500 italic text-center py-4">No data available</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="space-y-8">
            <!-- Status Distribution -->
            <div class="rounded-xl border border-gray-200 bg-white overflow-hidden shadow-sm">
                <div class="bg-gray-50/80 px-6 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-bold tracking-widest text-gray-500 uppercase">Status Breakdown</h3>
                </div>
                <div class="p-6 grid grid-cols-2 sm:grid-cols-3 gap-4">
                    @foreach($ticketsByStatus as $statusData)
                        <div class="rounded-lg border border-dashed border-gray-200 p-4 text-center">
                            <div class="text-2xl font-bold text-gray-800">{{ $statusData->count }}</div>
                            <div class="text-xs uppercase mt-1 font-medium text-gray-500">{{ str_replace('_', ' ', $statusData->status) }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Priority Distribution -->
            <div class="rounded-xl border border-gray-200 bg-white overflow-hidden shadow-sm">
                <div class="bg-gray-50/80 px-6 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-bold tracking-widest text-gray-500 uppercase">Priority Queue</h3>
                </div>
                <div class="p-6 grid grid-cols-3 gap-4">
                    @foreach($ticketsByPriority as $priorityData)
                        <div class="rounded-lg p-3 text-center {{ $priorityData->priority == 'high' ? 'bg-red-50 text-red-700' : ($priorityData->priority == 'medium' ? 'bg-blue-50 text-blue-700' : 'bg-gray-100 text-gray-600') }}">
                            <div class="text-2xl font-black">{{ $priorityData->count }}</div>
                            <div class="text-[10px] font-bold uppercase mt-1">{{ $priorityData->priority }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        
    </div>

</div>
@endsection
