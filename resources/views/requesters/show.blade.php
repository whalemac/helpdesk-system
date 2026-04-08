@extends('layouts.helpdesk')

@section('header', 'Requester Profile')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('requesters.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-900 flex items-center gap-1 transition">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Directory
        </a>
    </div>

    <!-- Profile Card -->
    <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden mb-8">
        <div class="p-8">
            <div class="flex items-center gap-x-6">
                <div class="h-20 w-20 flex-shrink-0 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-2xl font-bold">
                    {{ substr($requester->first_name, 0, 1) }}{{ substr($requester->last_name, 0, 1) }}
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $requester->first_name }} {{ $requester->last_name }}</h2>
                    <p class="text-sm text-gray-500 mt-1">{{ $requester->department ?? 'General Support' }}</p>
                </div>
            </div>
            
            <div class="mt-8 border-t border-gray-100 pt-8 grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Email Address</h4>
                    <p class="mt-2 text-base text-gray-900">{{ $requester->email }}</p>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Phone Number</h4>
                    <p class="mt-2 text-base text-gray-900">{{ $requester->phone }}</p>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Added To System</h4>
                    <p class="mt-2 text-base text-gray-900">{{ $requester->created_at->format('F d, Y') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
