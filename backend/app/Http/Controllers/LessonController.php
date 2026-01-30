<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function store(Request $request, Course $course)
    {
        abort_unless($course->instructor_id === auth()->id(), 403);

        $data = $request->validate([
            'title' => 'required|string',
            'content' => 'nullable|string',
            'order' => 'integer',
        ]);

        return Lesson::create([
            'course_id' => $course->id,
            ...$data,
        ]);
    }

    public function index(Course $course)
    {
        abort_unless($course->published, 404);
        return $course->lessons;
    }
}