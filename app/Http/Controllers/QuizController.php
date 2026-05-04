<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuizItemRequest;
use App\Http\Requests\StoreQuizRequest;
use App\Models\Quiz;
use App\Models\QuizItem;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Session\Store;

class QuizController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $currentUser = $request->user()->load('company.users');
        $userInSameCompany = $currentUser->company->users()->pluck('id')->toArray();

        $quizzes = Quiz::where(function ($q) use ($userInSameCompany) {
            $q->where('is_custom', 0)
              ->orWhereIn('user_id', $userInSameCompany);
        })->with('user')->orderByDesc('created_at')->paginate(10);
        return view('quiz.index', compact('quizzes'));
    }

    public function create()
    {
        return view('quiz.create');
    }

    public function store(StoreQuizRequest $request)
    {
        $currentUser = $request->user();
        $quiz = new Quiz;
        $quiz['title'] = $request['title'];
        $quiz['min_score'] = $request['min_score'];
        $quiz['show_question'] = $request['show_question'];
        $quiz['is_custom'] = $request['is_custom'];
        $currentUser->quizes()->save($quiz);

        return redirect('/quiz/' . $quiz['id'])->withMessage('Quiz created. Now add the questions');
    }

    public function show(Quiz $quiz)
    {
        $this->authorize('view', $quiz);
        $quiz->load('items');
        return view('quiz.show', compact('quiz'));
    }

    public function destroy(Quiz $quiz)
    {
        $this->authorize('delete', $quiz);
        $quiz->items()->delete();
        $quiz->delete();
        return redirect('/quiz')->withMessage('Quiz deleted');
    }

    public function addQuestion(StoreQuizItemRequest $request, Quiz $quiz)
    {
        $this->authorize('manageItems', $quiz);
        $item = new QuizItem;
        $item['question'] = $request['question'];
        $item['answer'] = $request['answers'];

        $quiz->items()->save($item);
        return redirect()->back()->withMessage('Question added');
    }
}
