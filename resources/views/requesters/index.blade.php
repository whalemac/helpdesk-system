@extends('layouts.helpdesk')

@section('header', 'Manage Requesters')

@section('content')
    <div class="sm:flex sm:items-center sm:justify-between mb-8">
        <div>
            <h2 class="text-base font-semibold leading-6 text-gray-900">Customer Directory</h2>
            <p class="mt-2 text-sm text-gray-700">A list of all users who have submitted or can submit helpdesk tickets.</p>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
            <a href="{{ route('requesters.create') }}"
                class="block rounded-lg bg-blue-600 px-4 py-2.5 text-center text-sm font-semibold text-white shadow-sm hover:bg-blue-500 transition">
                + Add Requester
            </a>
        </div>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th scope="col"
                            class="py-4 pl-6 pr-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Name</th>
                        <th scope="col"
                            class="px-3 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Contact
                            Info</th>
                        <th scope="col"
                            class="px-3 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Department</th>
                        <th scope="col"
                            class="px-3 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Created
                        </th>
                        <th scope="col" class="relative py-4 pl-3 pr-6">
                            <span class="sr-only">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($requesters as $requester)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="whitespace-nowrap py-5 pl-6 pr-3 text-sm font-medium text-gray-900">
                                {{ $requester->first_name }} {{ $requester->last_name }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-5 text-sm text-gray-500">
                                <div class="text-gray-900">{{ $requester->email }}</div>
                                <div class="text-xs text-gray-400 mt-0.5">{{ $requester->phone }}</div>
                            </td>
                            <td class="whitespace-nowrap px-3 py-5 text-sm text-gray-500">
                                {{ $requester->department ?? 'N/A' }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-5 text-sm text-gray-500">
                                {{ $requester->created_at->format('M d, Y') }}
                            </td>
                            <td class="relative whitespace-nowrap py-5 pl-3 pr-6 text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('requesters.show', $requester) }}" class="text-gray-500 hover:text-gray-700 font-medium">View</a>
                                    <a href="{{ route('requesters.edit', $requester) }}" class="text-blue-600 hover:text-blue-800 font-semibold">Edit</a>
                                    <form action="{{ route('requesters.destroy', $requester) }}" method="POST" onsubmit="return confirm('Delete this requester?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 font-semibold">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-sm text-gray-500">
                                No requesters found in the system.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection