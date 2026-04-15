@extends('layouts.helpdesk')

@section('header', 'System Settings')

@section('content')
<div class="max-w-2xl mx-auto">

    <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
        <div class="px-8 py-6 border-b border-gray-100 bg-gray-50/50">
            <h3 class="text-lg font-semibold text-gray-900">System Configuration</h3>
            <p class="mt-1 text-sm text-gray-500">Control global system behaviour. Changes take effect immediately.</p>
        </div>

        @if ($errors->any())
            <div class="mx-8 mt-6 rounded-lg bg-red-50 border border-red-200 p-4">
                <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('system.update') }}" method="POST" class="px-8 py-6 space-y-8">
            @csrf

            {{-- User Registration --}}
            <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-gray-50 px-6 py-4">
                <div>
                    <p class="text-sm font-semibold text-gray-900">User Registration</p>
                    <p class="text-xs text-gray-500 mt-0.5">Allow new users to register accounts via the login page.</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="registration_enabled" value="1"
                        {{ $settings['registration_enabled'] === '1' ? 'checked' : '' }}
                        class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-blue-600 peer-focus:ring-2 peer-focus:ring-blue-300 transition after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full"></div>
                </label>
            </div>

            {{-- Default Priority --}}
            <div>
                <label class="block text-sm font-semibold text-gray-900 mb-1">Default Ticket Priority</label>
                <p class="text-xs text-gray-500 mb-3">Priority automatically assigned when a new ticket is created.</p>
                <div class="grid grid-cols-4 gap-3">
                    @foreach(['low', 'medium', 'high', 'critical'] as $priority)
                        @php
                            $colors = [
                                'low'      => 'border-gray-300 text-gray-600 peer-checked:bg-gray-100 peer-checked:border-gray-500 peer-checked:text-gray-900',
                                'medium'   => 'border-blue-300 text-blue-600 peer-checked:bg-blue-50 peer-checked:border-blue-600 peer-checked:text-blue-800',
                                'high'     => 'border-orange-300 text-orange-600 peer-checked:bg-orange-50 peer-checked:border-orange-600 peer-checked:text-orange-800',
                                'critical' => 'border-red-300 text-red-600 peer-checked:bg-red-50 peer-checked:border-red-600 peer-checked:text-red-800',
                            ];
                        @endphp
                        <label class="relative cursor-pointer">
                            <input type="radio" name="default_ticket_priority" value="{{ $priority }}"
                                {{ $settings['default_ticket_priority'] === $priority ? 'checked' : '' }}
                                class="sr-only peer">
                            <div class="text-center rounded-lg border-2 py-3 px-2 text-xs font-bold uppercase tracking-wide transition {{ $colors[$priority] }}">
                                {{ $priority }}
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Default Status --}}
            <div>
                <label class="block text-sm font-semibold text-gray-900 mb-1">Default Ticket Status on Creation</label>
                <p class="text-xs text-gray-500 mb-3">Status automatically set when a new ticket is submitted.</p>
                <div class="grid grid-cols-2 gap-3">
                    @foreach(['open' => 'Open', 'in_progress' => 'In Progress'] as $value => $label)
                        <label class="relative cursor-pointer">
                            <input type="radio" name="default_ticket_status" value="{{ $value }}"
                                {{ $settings['default_ticket_status'] === $value ? 'checked' : '' }}
                                class="sr-only peer">
                            <div class="text-center rounded-lg border-2 border-gray-300 py-3 px-4 text-sm font-semibold text-gray-600 transition peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:text-blue-800">
                                {{ $label }}
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center justify-end gap-4 border-t border-gray-100 pt-6">
                <button type="submit" class="rounded-lg bg-blue-600 px-8 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 transition">
                    Save Settings
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
