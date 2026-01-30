<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Instructor: Create course
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $course = Course::create([
            'instructor_id' => auth()->id(),
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'published' => false,
        ]);

        return response()->json($course, 201);
    }

    /*
    |--------------------------------------------------------------------------
    | Instructor: List own courses
    |--------------------------------------------------------------------------
    */
    public function myCourses()
    {
        return Course::where('instructor_id', auth()->id())
            ->withCount('lessons')
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Instructor: Publish course
    |--------------------------------------------------------------------------
    */
    public function publish(Course $course)
    {
        abort_unless($course->instructor_id === auth()->id(), 403);

        $course->update([
            'published' => true,
        ]);

        return response()->json([
            'message' => 'Course published',
            'course' => $course,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Student: List published courses
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        return Course::where('published', true)
            ->with('instructor:id,name')
            ->withCount('lessons')
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Student: View single published course
    |--------------------------------------------------------------------------
    */
    public function show(Course $course)
    {
        abort_unless($course->published, 404);

        return $course->load([
            'instructor:id,name',
            'lessons',
        ]);
    }
}