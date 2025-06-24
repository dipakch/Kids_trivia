@extends('layouts.app')
@if(isset($level))
    @section('title', 'Edit Level')
@else
    @section('title', 'Create Level')
@endif

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-semibold mb-6">
                    @if(isset($level))
                        Edit Level: {{ $level->name }}
                    @else
                        Add New Level
                    @endif
                </h2>

                <form method="POST" 
                    action="{{ isset($level) ? route('levels.update', $level->id) : route('levels.store') }}">
                    @csrf
                    @if(isset($level))
                        @method('PUT')
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div class="col-span-1">
                            <label for="name" class="block text-sm font-medium text-gray-700">Level Name *</label>
                            <input type="text" name="name" id="name" required
                                value="{{ isset($level) ? $level->name : old('name') }}"
                                class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Order Index -->
                        <div class="col-span-1">
                            <label for="order_index" class="block text-sm font-medium text-gray-700">Order Index *</label>
                            <input type="number" name="order_index" id="order_index" min="1" required
                                value="{{ isset($level) ? $level->order_index : old('order_index') }}"
                                class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('order_index')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <a href="{{ route('levels.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded mr-2">
                            Cancel
                        </a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                            @if(isset($level))
                                Update Level
                            @else
                                Add Level
                            @endif
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection