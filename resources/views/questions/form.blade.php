@extends('layouts.app')
@if(isset($question))
    @section('title', 'Edit Question')
@else
    @section('title', 'Create Question')
@endif

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-semibold mb-6">
                    @if(isset($question))
                        Edit Question #{{ $question->id }}
                    @else
                        Add New Question
                    @endif
                </h2>

                <form method="POST" 
                    action="{{ isset($question) ? route('questions.update', $question->id) : route('questions.store') }}"
                    enctype="multipart/form-data">
                    @csrf
                    @if(isset($question))
                        @method('PUT')
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Category -->
                        <div class="col-span-1">
                            <label for="category_id" class="block text-sm font-medium text-gray-700">Category *</label>
                            <select name="category_id" id="category_id" required
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ (isset($question) && $question->category_id == $category->id) || old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }} ({{ $category->nepali_name }})
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Level -->
                        <div class="col-span-1">
                            <label for="level_id" class="block text-sm font-medium text-gray-700">Level *</label>
                            <select name="level_id" id="level_id" required
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                <option value="">Select Level</option>
                                @foreach($levels as $level)
                                    <option value="{{ $level->id }}"
                                        {{ (isset($question) && $question->level_id == $level->id) || old('level_id') == $level->id ? 'selected' : '' }}>
                                        {{ $level->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('level_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Image URL -->
                        <div class="col-span-2">
                            <label for="image_url" class="block text-sm font-medium text-gray-700">Image URL *</label>
                            <input type="url" name="image_url" id="image_url" required
                                value="{{ isset($question) ? $question->image_url : old('image_url') }}"
                                class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('image_url')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- English Answer -->
                        <div class="col-span-1">
                            <label for="correct_answer" class="block text-sm font-medium text-gray-700">Correct Answer (English) *</label>
                            <input type="text" name="correct_answer" id="correct_answer" required
                                value="{{ isset($question) ? $question->correct_answer : old('correct_answer') }}"
                                class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('correct_answer')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nepali Answer -->
                        <div class="col-span-1">
                            <label for="correct_answer_nepali" class="block text-sm font-medium text-gray-700">Correct Answer (Nepali) *</label>
                            <input type="text" name="correct_answer_nepali" id="correct_answer_nepali" required
                                value="{{ isset($question) ? $question->correct_answer_nepali : old('correct_answer_nepali') }}"
                                class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('correct_answer_nepali')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Active Status -->
                        <div class="col-span-2">
                            <div class="flex items-center">
                                <input type="checkbox" name="is_active" id="is_active" value="1"
                                    {{ (isset($question) && $question->is_active) || old('is_active', true) ? 'checked' : '' }}
                                    class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                <label for="is_active" class="ml-2 block text-sm text-gray-900">Active</label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <a href="{{ route('questions.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded mr-2">
                            Cancel
                        </a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                            @if(isset($question))
                                Update Question
                            @else
                                Add Question
                            @endif
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection