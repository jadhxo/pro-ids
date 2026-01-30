<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\LessonCompletion;

class LessonProgressController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Student: Mark lesson as completed
    |--------------------------------------------------------------------------
    */
    public function complete(Lesson $lesson)
    {
        abort_unless($lesson->course->published, 404);

        $completion = LessonCompletion::firstOrCreate([
            'user_id' => auth()->id(),
            'lesson_id' => $lesson->id,
        ]);

        return response()->json([
            'message' => 'Lesson completed',
            'completed_at' => $completion->completed_at,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Student: Get progress for a course
    |--------------------------------------------------------------------------
    */
    public function progress($courseId)
    {
        $userId = auth()->id();

        $totalLessons = Lesson::where('course_id', $courseId)->count();

        $completedLessons = LessonCompletion::where('user_id', $userId)
            ->whereIn(
                'lesson_id',
                Lesson::where('course_id', $courseId)->pluck('id')
            )
            ->count();

        return response()->json([
            'course_id' => $courseId,
            'completed' => $completedLessons,
            'total' => $totalLessons,
            'percentage' => $totalLessons === 0
                ? 0
                : round(($completedLessons / $totalLessons) * 100),
        ]);
    }
}