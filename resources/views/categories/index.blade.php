@extends('layouts.helpdesk')

@section('header', 'Manage Categories')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    {{-- Category List --}}
    <div class="lg:col-span-2">
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-base font-semibold leading-6 text-gray-900">Current Categories</h3>
            </div>

            @if($categories->isEmpty())
                <div class="p-8 text-center text-gray-500 text-sm">No categories found. Create one to get started!</div>
            @else
                <ul class="divide-y divide-gray-100">
                    @foreach($categories as $category)
                        <li class="flex items-center justify-between gap-x-6 px-6 py-5 hover:bg-gray-50 transition">
                            <div class="min-w-0">
                                <div class="flex items-start gap-x-3">
                                    <p class="text-sm font-semibold leading-6 text-gray-900">{{ $category->name }}</p>
                                    <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $category->status === 'active' ? 'bg-emerald-50 text-emerald-700 ring-emerald-600/20' : 'bg-gray-50 text-gray-600 ring-gray-500/10' }}">
                                        {{ ucfirst($category->status) }}
                                    </span>
                                </div>
                                @if($category->description)
                                    <p class="mt-1 text-xs leading-5 text-gray-500">{{ $category->description }}</p>
                                @endif
                            </div>
                            <div class="flex flex-none items-center gap-x-3">
                                <a href="{{ route('categories.edit', $category) }}"
                                    class="rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition">
                                    Edit
                                </a>
                                <form action="{{ route('categories.destroy', $category) }}" method="POST"
                                    onsubmit="return confirm('Delete this category? This cannot be undone.')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-red-600 shadow-sm ring-1 ring-inset ring-red-300 hover:bg-red-50 transition">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    {{-- Create Category Form --}}
    <div class="lg:col-span-1">
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-base font-semibold leading-6 text-gray-900">Add New Category</h3>
            </div>

            @if ($errors->any())
                <div class="mx-6 mt-4 rounded-lg bg-red-50 border border-red-200 p-3">
                    <ul class="list-disc list-inside text-xs text-red-700 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('categories.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium leading-6 text-gray-900">Category Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required value="{{ old('name') }}"
                        class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6">
                </div>
                <div>
                    <label class="block text-sm font-medium leading-6 text-gray-900">Description</label>
                    <textarea name="description" rows="3"
                        class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6">{{ old('description') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium leading-6 text-gray-900">Status</label>
                    <select name="status" class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="pt-2">
                    <button type="submit"
                        class="w-full rounded-md bg-blue-600 px-3 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition">
                        Create Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
