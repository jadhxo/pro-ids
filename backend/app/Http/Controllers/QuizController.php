<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function store(Request $request, Lesson $lesson)
    {
        abort_unless($lesson->course->instructor_id === auth()->id(), 403);

        $data = $request->validate([
            'title' => 'required|string',
        ]);

        return Quiz::create([
            'lesson_id' => $lesson->id,
            'title' => $data['title'],
        ]);
    }

    public function show(Quiz $quiz)
    {
        return $quiz->load('questions.options');
    }
}