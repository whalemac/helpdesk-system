@extends('layouts.helpdesk')

@section('header', 'System Analytics & Reports')

@section('content')

{{-- DATE FILTER + EXPORT --}}
<div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mb-8">
    <h3 class="text-sm font-bold text-gray-700 uppercase tracking-widest mb-4">Filter by Date Range</h3>
    <form action="{{ route('reports.index') }}" method="GET">
        <div class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Date From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                    class="rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 text-sm">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Date To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                    class="rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 text-sm">
            </div>
            <button type="submit"
                class="rounded-lg bg-blue-600 px-5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 transition">
                Generate Report
            </button>
            <a href="{{ route('reports.export-pdf', ['date_from' => request('date_from'), 'date_to' => request('date_to')]) }}"
                class="rounded-lg border border-gray-300 bg-white px-5 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 transition flex items-center gap-2">
                <svg class="h-4 w-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                Export as PDF
            </a>
            @if(request('date_from') || request('date_to'))
                <a href="{{ route('reports.index') }}" class="text-sm text-gray-400 hover:text-gray-600 underline">Clear Filter</a>
            @endif
        </div>
    </form>
    @if(request('date_from') || request('date_to'))
        <p class="mt-3 text-xs text-gray-500">
            Showing results
            @if(request('date_from')) from <strong>{{ request('date_from') }}</strong> @endif
            @if(request('date_to')) to <strong>{{ request('date_to') }}</strong> @endif
        </p>
    @endif
</div>

<div class="space-y-8">

    {{-- Top KPI Grid --}}
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-widest">Total Volume</p>
                <p class="mt-2 text-4xl font-black text-gray-900">{{ $totalTickets }}</p>
            </div>
            <div class="h-12 w-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
        </div>
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-amber-600 uppercase tracking-widest">Currently Open</p>
                <p class="mt-2 text-4xl font-black text-amber-700">{{ $openTickets }}</p>
            </div>
            <div class="h-12 w-12 rounded-full bg-amber-50 flex items-center justify-center text-amber-600">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-emerald-600 uppercase tracking-widest">Resolved</p>
                <p class="mt-2 text-4xl font-black text-emerald-700">{{ $resolvedTickets }}</p>
            </div>
            <div class="h-12 w-12 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        {{-- Category Distribution --}}
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
            {{-- Status Distribution --}}
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

            {{-- Priority Distribution --}}
            <div class="rounded-xl border border-gray-200 bg-white overflow-hidden shadow-sm">
                <div class="bg-gray-50/80 px-6 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-bold tracking-widest text-gray-500 uppercase">Priority Queue</h3>
                </div>
                <div class="p-6 grid grid-cols-4 gap-3">
                    @foreach($ticketsByPriority as $priorityData)
                        @php
                            $bg = match($priorityData->priority) {
                                'critical' => 'bg-red-100 text-red-800',
                                'high'     => 'bg-orange-50 text-orange-700',
                                'medium'   => 'bg-blue-50 text-blue-700',
                                default    => 'bg-gray-100 text-gray-600',
                            };
                        @endphp
                        <div class="rounded-lg p-3 text-center {{ $bg }}">
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