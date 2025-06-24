@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-semibold mb-6">Quiz Dashboard</h2>
                
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-blue-50 p-6 rounded-lg shadow">
                        <h3 class="text-lg font-medium text-gray-900">Total Questions</h3>
                        <p class="mt-2 text-3xl font-bold text-blue-600">{{ $questionCount }}</p>
                    </div>
                    
                    <div class="bg-green-50 p-6 rounded-lg shadow">
                        <h3 class="text-lg font-medium text-gray-900">Categories</h3>
                        <p class="mt-2 text-3xl font-bold text-green-600">{{ $categoryCount }}</p>
                    </div>
                    
                    <div class="bg-purple-50 p-6 rounded-lg shadow">
                        <h3 class="text-lg font-medium text-gray-900">Difficulty Levels</h3>
                        <p class="mt-2 text-3xl font-bold text-purple-600">{{ $levelCount }}</p>
                    </div>
                </div>
                
                <!-- Recent Questions -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium mb-4">Recent Questions</h3>
                    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Question</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Level</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($recentQuestions as $question)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $question->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ Str::limit($question->correct_answer, 50) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $question->category->name }}<br>
                                    <span class="text-gray-400">{{ $question->category->nepali_name }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $question->level->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $question->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $question->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('questions.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-4 rounded-lg text-center transition duration-300">
                        Add New Question
                    </a>
                    <a href="{{ route('categories.index') }}" class="bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-4 rounded-lg text-center transition duration-300">
                        Manage Categories
                    </a>
                    <a href="{{ route('levels.index') }}" class="bg-purple-500 hover:bg-purple-600 text-white font-bold py-3 px-4 rounded-lg text-center transition duration-300">
                        Manage Levels
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection