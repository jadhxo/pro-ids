<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;

class EnrollmentController extends Controller
{
    public function enroll(Course $course)
    {
        abort_unless($course->published, 404);

        return Enrollment::firstOrCreate([
            'user_id' => auth()->id(),
            'course_id' => $course->id,
        ]);
    }

    public function myCourses()
    {
        return Enrollment::where('user_id', auth()->id())
            ->with('course')
            ->get();
    }
}