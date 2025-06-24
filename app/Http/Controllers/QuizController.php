<?php
namespace App\Http\Controllers; 

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Category;
use App\Models\Level;

class QuizController extends Controller
{
    /**
     * Step 1:
     * Show the language mode selection page.
     * User selects between 'english-nepali' or 'nepali-english' quiz modes.
     */
    public function selectMode()
    {
        return view('quiz.mode'); // Render language mode selection view
    }

    /**
     * Step 2:
     * Handle the mode selection form submission.
     * Validate mode and store it in session.
     * Redirect to category & difficulty selection.
     */
    public function setMode(Request $request)
    {
        $validated = $request->validate([
            'mode' => 'required|in:english-nepali,nepali-english',
        ]);

        session(['mode' => $validated['mode']]);

        return redirect()->route('quiz.categorySelect');
    }

    /**
     * Show category and difficulty level selection page.
     * Pass all categories and levels from DB to the view.
     */
    public function categorySelect()
    {
        $categories = Category::all();
        $levels = Level::all();

        return view('quiz.select_category', compact('categories', 'levels'));
    }

    /**
     * Start the quiz by validating selected category and level,
     * store them in session, reset quiz state variables,
     * then redirect to show the first question.
     */
    public function startQuiz(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'level_id' => 'required|exists:levels,id',
        ]);

        // Reset session data for quiz start
        session([
            'category_id' => $request->category_id,
            'level_id' => $request->level_id,
            'quiz_question_ids' => null,
            'quiz_index' => null,
        ]);

        return redirect()->route('quiz.show');
    }

    /**
     * Show the current quiz question based on session state.
     * - Initialize question list & shuffle if not already.
     * - If quiz finished, clear session and redirect to mode selection.
     * - Determine correct answer column based on mode.
     * - Prepare 3 incorrect options + 1 correct option, shuffle options.
     * - Return quiz.show view with all needed data.
     */
    public function show(Request $request)
    {
        $mode = session('mode');
        $category_id = session('category_id');
        $level_id = session('level_id');

        // Redirect if required session data is missing
        if (!$mode || !$category_id || !$level_id) {
            return redirect()->route('quiz.selectMode');
        }

        // Initialize questions for this category & level if not set
        if (!session()->has('quiz_question_ids')) {
            $question_ids = Question::where('category_id', $category_id)
                ->where('level_id', $level_id)
                ->pluck('id')
                ->shuffle()
                ->toArray();

            // Redirect back if no questions found
            if (empty($question_ids)) {
                return redirect()->route('quiz.categorySelect')
                    ->with('error', 'No questions available for this category and level.');
            }

            session(['quiz_question_ids' => $question_ids, 'quiz_index' => 0]);
        }

        $quiz_question_ids = session('quiz_question_ids');
        $quiz_index = session('quiz_index', 0);

        // If all questions are done, clear session and go back to mode select
        if ($quiz_index >= count($quiz_question_ids)) {
            session()->forget(['quiz_question_ids', 'quiz_index', 'quiz_progress', 'category_id', 'level_id', 'mode']);
            return redirect()->route('quiz.selectMode')
                ->with('message', 'Quiz completed! Please select mode to start again.');
        }

        $question_id = $quiz_question_ids[$quiz_index];
        $question = Question::findOrFail($question_id);

        // Decide correct answer column based on mode
        $column = $mode === 'english-nepali' ? 'correct_answer_nepali' : 'correct_answer';
        $correct_answer = $question->$column;

        // Fetch 3 unique incorrect answers (options)
        $other_options = Question::where('id', '!=', $question->id)
            ->where('category_id', $category_id)
            ->where('level_id', $level_id)
            ->inRandomOrder()
            ->pluck($column)
            ->unique()
            ->take(3)
            ->values();

        // If fewer than 3 incorrect options, refill to ensure 3
        while ($other_options->count() < 3) {
            $fillers = Question::where('id', '!=', $question->id)
                ->where('category_id', $category_id)
                ->where('level_id', $level_id)
                ->inRandomOrder()
                ->pluck($column)
                ->unique()
                ->values();

            $other_options = $other_options->merge($fillers)->unique()->take(3);
        }

        // Combine correct answer with incorrect ones and shuffle for random option order
        $options = $other_options->push($correct_answer)->shuffle()->values()->toArray();

        // Note: Do NOT increment quiz_index here; it will be incremented only on correct answers or skip.

        return view('quiz.show', [
            'question' => $question,
            'options' => $options,
            'correct_answer' => $correct_answer,
            'mode' => $mode,
            'progress' => $quiz_index + 1,
            'total' => count($quiz_question_ids),
        ]);
    }

    /**
     * Check the submitted answer.
     * - If correct, increment quiz_index to move to next question.
     * - Return JSON response with result status.
     */
    public function checkAnswer(Request $request)
    {
        $data = $request->validate([
            'selected_option' => 'required|string',
            'correct_answer' => 'required|string',
        ]);

        $isCorrect = trim($data['selected_option']) === trim($data['correct_answer']);

        if ($isCorrect) {
            $quiz_index = session('quiz_index', 0);
            session(['quiz_index' => $quiz_index + 1]);
        }

        return response()->json([
            'result' => $isCorrect ? 'correct' : 'incorrect'
        ]);
    }

    /**
     * Skip the current question without answering.
     * - Increment quiz_index to move to next question.
     * - Redirect back to show route to load next question.
     */
    public function skip()
    {
        $index = session('quiz_index', 0);
        session(['quiz_index' => $index + 1]);

        // Optional: Log skipped question ID here if needed.

        return redirect()->route('quiz.show');
    }

    /**
     * Reset the entire quiz session to start fresh.
     * Redirect to mode selection page.
     */
    public function reset()
    {
        session()->forget(['quiz_question_ids', 'quiz_index', 'quiz_progress', 'category_id', 'level_id', 'mode']);
        return redirect()->route('quiz.selectMode');
    }

    /**
     * Alias for checkAnswer if needed.
     * You can remove this method if not used.
     */
    public function answer(Request $request)
    {
        return $this->checkAnswer($request);
    }
}
