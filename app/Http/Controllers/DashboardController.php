<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Category;
use App\Models\Level;

class DashboardController extends Controller
{
    public function index()
    {
        $questionCount = Question::count();
        $categoryCount = Category::count();
        $levelCount = Level::count();
        
        $recentQuestions = Question::with(['category', 'level'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'questionCount',
            'categoryCount',
            'levelCount',
            'recentQuestions'
        ));
    }
}