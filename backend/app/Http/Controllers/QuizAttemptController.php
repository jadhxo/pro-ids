<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;

class QuizAttemptController extends Controller
{
    public function submit(Request $request, Quiz $quiz)
    {
        $answers = $request->input('answers'); // [question_id => option_id]

        $score = 0;
        $total = $quiz->questions()->count();

        foreach ($quiz->questions as $question) {
            $correct = $question->options()
                ->where('is_correct', true)
                ->value('id');

            if (($answers[$question->id] ?? null) == $correct) {
                $score++;
            }
        }

        return QuizAttempt::updateOrCreate(
            [
                'quiz_id' => $quiz->id,
                'user_id' => auth()->id(),
            ],
            [
                'score' => $score,
                'total' => $total,
            ]
        );
    }
}