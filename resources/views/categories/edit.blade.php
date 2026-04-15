@extends('layouts.helpdesk')

@section('header', 'Edit Category')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('categories.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-900 flex items-center gap-1 transition">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Categories
        </a>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
        <div class="px-8 py-6 border-b border-gray-100 bg-gray-50/50">
            <h3 class="text-lg font-semibold text-gray-900">Edit Category</h3>
            <p class="mt-1 text-sm text-gray-500">Update the category name, description, and active status.</p>
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

        <form action="{{ route('categories.update', $category) }}" method="POST" class="px-8 py-6 space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium leading-6 text-gray-900">Category Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" required value="{{ old('name', $category->name) }}"
                    class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium leading-6 text-gray-900">Description</label>
                <textarea name="description" rows="3"
                    class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm">{{ old('description', $category->description) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium leading-6 text-gray-900">Status <span class="text-red-500">*</span></label>
                <select name="status" class="mt-2 block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm">
                    <option value="active"   {{ old('status', $category->status) === 'active'   ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $category->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <div class="flex items-center justify-end gap-4 border-t border-gray-100 pt-6">
                <a href="{{ route('categories.index') }}" class="text-sm font-semibold text-gray-900 hover:text-gray-600">Cancel</a>
                <button type="submit" class="rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 transition">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
