<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('name')->paginate(10);
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.form');
    }

public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255|unique:categories,name',
        'nepali_name' => 'required|string|max:100'
    ]);

    // Get the next available ID (starting from 1, filling gaps)
    $existingIds = Category::orderBy('id')->pluck('id')->toArray();
    $nextId = 1;

    foreach ($existingIds as $id) {
        if ($id != $nextId) break;
        $nextId++;
    }

    // Manually assign the ID
    $category = new Category($validated);
    $category->id = $nextId;
    $category->save();

    return redirect()->route('categories.index')
        ->with('success', 'Category created successfully with ID ' . $nextId . '.');
}

    public function edit(Category $category)
    {
        return view('categories.form', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,'.$category->id,
            'nepali_name' => 'required|string|max:100' // Add this line
            ]);

        $category->update($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        if ($category->questions()->exists()) {
            return redirect()->route('categories.index')
                ->with('error', 'Cannot delete category with associated questions.');
        }

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}