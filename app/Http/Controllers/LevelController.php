<?php

namespace App\Http\Controllers;

use App\Models\Level;
use Illuminate\Http\Request;

class LevelController extends Controller
{
    public function index()
    {
        $levels = Level::orderBy('order_index')->paginate(10);
        return view('levels.index', compact('levels'));
    }

    public function create()
    {
        return view('levels.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:levels,name',
            'order_index' => 'required|integer|min:1'
        ]);

        Level::create($validated);

        return redirect()->route('levels.index')
            ->with('success', 'Level created successfully.');
    }

    public function edit(Level $level)
    {
        return view('levels.form', compact('level'));
    }

    public function update(Request $request, Level $level)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:levels,name,'.$level->id,
            'order_index' => 'required|integer|min:1'
        ]);

        $level->update($validated);

        return redirect()->route('levels.index')
            ->with('success', 'Level updated successfully.');
    }

    public function destroy(Level $level)
    {
        if ($level->questions()->exists()) {
            return redirect()->route('levels.index')
                ->with('error', 'Cannot delete level with associated questions.');
        }

        $level->delete();

        return redirect()->route('levels.index')
            ->with('success', 'Level deleted successfully.');
    }
}