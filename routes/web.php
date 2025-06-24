<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LevelController;
use Illuminate\Http\Request;

// ==============================
// Public Routes (No login required)
// ==============================

// Mode selection page (GET)
Route::get('/', [QuizController::class, 'selectMode'])->name('quiz.selectMode');

// Set selected mode (POST)
Route::post('/quiz/set-mode', [QuizController::class, 'setMode'])->name('quiz.setMode');

// Select category and difficulty (GET)
Route::get('/quiz/category-select', [QuizController::class, 'categorySelect'])->name('quiz.categorySelect');

// Start quiz with selected category & difficulty (POST)
Route::post('/quiz/start', [QuizController::class, 'startQuiz'])->name('quiz.startQuiz');

// Show current quiz question (GET)
Route::get('/quiz/show', [QuizController::class, 'show'])->name('quiz.show');

// Submit answer for current question (POST)
Route::post('/quiz/answer', [QuizController::class, 'checkAnswer'])->name('quiz.answer');

// Skip current question and move to next (POST)
Route::post('/quiz/skip', [QuizController::class, 'skip'])->name('quiz.skip');

// Reset quiz session data (GET)
Route::get('/quiz/reset', [QuizController::class, 'reset'])->name('quiz.reset');


// ==============================
// Protected Routes (Require login)
// ==============================
Route::middleware(['auth'])->group(function () {
    
    // Dashboard home page (GET)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // CRUD resource routes for Questions, Categories, Levels
    Route::resource('questions', QuestionController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('levels', LevelController::class);

    // User Profile management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
