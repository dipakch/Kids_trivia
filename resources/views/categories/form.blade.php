@extends('layouts.app')

@if(isset($category))
    @section('title', 'Edit Category')
@else
    @section('title', 'Create Category')
@endif

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-semibold mb-6">
                    @if(isset($category))
                        Edit Category: {{ $category->name }}
                    @else
                        Add New Category
                    @endif
                </h2>

                <form method="POST" 
                    action="{{ isset($category) ? route('categories.update', $category->id) : route('categories.store') }}">
                    @csrf
                    @if(isset($category))
                        @method('PUT')
                    @endif

                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Category Name *</label>
                        <input type="text" name="name" id="name" required
                            value="{{ isset($category) ? $category->name : old('name') }}"
                            class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="nepali_name" class="block text-sm font-medium text-gray-700">Nepali Name *</label>
                        <input type="text" name="nepali_name" id="nepali_name" required
                            value="{{ isset($category) ? $category->nepali_name : old('nepali_name') }}"
                            class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        @error('nepali_name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-6 flex justify-end">
                        <a href="{{ route('categories.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded mr-2">
                            Cancel
                        </a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                            @if(isset($category))
                                Update Category
                            @else
                                Add Category
                            @endif
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection