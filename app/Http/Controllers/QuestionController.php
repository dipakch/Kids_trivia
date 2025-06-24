<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Category;
use App\Models\Level;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index()
    {
        $questions = Question::with(['category', 'level'])
            ->when(request('category_id'), function($query) {
                $query->where('category_id', request('category_id'));
            })
            ->when(request('level_id'), function($query) {
                $query->where('level_id', request('level_id'));
            })
            ->latest()
            ->paginate(10);

        $categories = Category::orderBy('name')->get();
        $levels = Level::orderBy('order_index')->get();

        return view('questions.index', compact('questions', 'categories', 'levels'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $levels = Level::orderBy('order_index')->get();
        
        return view('questions.form', compact('categories', 'levels'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'level_id' => 'required|exists:levels,id',
            'image_url' => 'required|url',
            'correct_answer' => 'required|string|max:255',
            'correct_answer_nepali' => 'required|string|max:255',
            'is_active' => 'boolean'
        ]);

        Question::create($validated);

        return redirect()->route('questions.index')
            ->with('success', 'Question created successfully.');
    }

    public function show(Question $question)
    {
        return view('questions.show', compact('question'));
    }

    public function edit(Question $question)
    {
        $categories = Category::orderBy('name')->get();
        $levels = Level::orderBy('order_index')->get();
        
        return view('questions.form', compact('question', 'categories', 'levels'));
    }

    public function update(Request $request, Question $question)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'level_id' => 'required|exists:levels,id',
            'image_url' => 'required|url',
            'correct_answer' => 'required|string|max:255',
            'correct_answer_nepali' => 'required|string|max:255',
            'is_active' => 'boolean'
        ]);

        $question->update($validated);

        return redirect()->route('questions.index')
            ->with('success', 'Question updated successfully.');
    }

    public function destroy(Question $question)
    {
        $question->delete();

        return redirect()->route('questions.index')
            ->with('success', 'Question deleted successfully.');
    }
}