@extends('layouts.helpdesk')

@section('header', 'Dashboard')

@section('content')
<!-- Welcome Banner -->
<div class="mb-8 rounded-xl bg-white p-8 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between border-l-4 border-blue-600">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Welcome back, {{ auth()->user()->name }}!</h2>
        <p class="mt-1 text-sm text-gray-500">Here's what's happening with your support tickets today.</p>
    </div>
    <div class="mt-4 sm:mt-0">
        @php
            $roleColors = [
                'admin' => 'bg-purple-100 text-purple-800 border-purple-200',
                'supervisor' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                'agent' => 'bg-blue-100 text-blue-800 border-blue-200',
                'requester' => 'bg-gray-100 text-gray-800 border-gray-200',
            ];
            $colorClass = $roleColors[$role] ?? 'bg-gray-100 text-gray-800 border-gray-200';
        @endphp
        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-bold uppercase tracking-wide border {{ $colorClass }}">
            {{ $role }}
        </span>
    </div>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
    @if($role === 'admin' || $role === 'supervisor')
        <!-- Admin/Supervisor Stats -->
        <div class="rounded-xl bg-white p-6 shadow-sm border border-gray-100 hover:shadow-md transition">
            <div class="flex items-center space-x-3 text-gray-500 mb-4">
                <svg class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <h3 class="text-sm font-semibold uppercase tracking-wider">Open Tickets</h3>
            </div>
            <div class="text-4xl font-bold text-gray-900">{{ $stats['open_tickets'] ?? 0 }}</div>
        </div>
        <div class="rounded-xl bg-white p-6 shadow-sm border border-gray-100 hover:shadow-md transition">
            <div class="flex items-center space-x-3 text-gray-500 mb-4">
                <svg class="h-6 w-6 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <h3 class="text-sm font-semibold uppercase tracking-wider">Resolved Tickets</h3>
            </div>
            <div class="text-4xl font-bold text-gray-900">{{ $stats['resolved_tickets'] ?? 0 }}</div>
        </div>
        <div class="rounded-xl bg-white p-6 shadow-sm border border-gray-100 hover:shadow-md transition">
            <div class="flex items-center space-x-3 text-gray-500 mb-4">
                <svg class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <h3 class="text-sm font-semibold uppercase tracking-wider">High Priority</h3>
            </div>
            <div class="text-4xl font-bold text-red-600">{{ $stats['high_priority'] ?? 0 }}</div>
        </div>
    @elseif($role === 'agent')
        <!-- Agent Stats -->
        <div class="rounded-xl bg-white p-6 shadow-sm border border-gray-100 hover:shadow-md transition">
            <div class="flex items-center space-x-3 text-gray-500 mb-4">
                <svg class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <h3 class="text-sm font-semibold uppercase tracking-wider">Assigned To Me</h3>
            </div>
            <div class="text-4xl font-bold text-gray-900">{{ $stats['assigned_tickets'] ?? 0 }}</div>
        </div>
        <div class="rounded-xl bg-white p-6 shadow-sm border border-gray-100 hover:shadow-md transition">
            <div class="flex items-center space-x-3 text-gray-500 mb-4">
                <svg class="h-6 w-6 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <h3 class="text-sm font-semibold uppercase tracking-wider">My Resoled Tickets</h3>
            </div>
            <div class="text-4xl font-bold text-gray-900">{{ $stats['resolved_tickets'] ?? 0 }}</div>
        </div>
    @elseif($role === 'requester')
        <!-- Requester Stats -->
        <div class="rounded-xl bg-white p-6 shadow-sm border border-gray-100 hover:shadow-md transition">
            <div class="flex items-center space-x-3 text-gray-500 mb-4">
                <svg class="h-6 w-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <h3 class="text-sm font-semibold uppercase tracking-wider">My Submissions</h3>
            </div>
            <div class="text-4xl font-bold text-gray-900">{{ $stats['my_tickets'] ?? 0 }}</div>
        </div>
        <div class="rounded-xl bg-white p-6 shadow-sm border border-gray-100 hover:shadow-md transition">
            <div class="flex items-center space-x-3 text-gray-500 mb-4">
                <svg class="h-6 w-6 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <h3 class="text-sm font-semibold uppercase tracking-wider">Pending Responses</h3>
            </div>
            <div class="text-4xl font-bold text-orange-600">{{ $stats['my_open_tickets'] ?? 0 }}</div>
        </div>
    @endif
</div>
@endsection