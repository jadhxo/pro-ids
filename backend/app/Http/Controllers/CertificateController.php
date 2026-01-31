<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonCompletion;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificateController extends Controller
{
    /**
     * Generate certificate ONLY if all requirements are met.
     */
    public function generate($courseId)
    {
        $user = Auth::user();

        $course = Course::with(['lessons', 'quizzes'])->findOrFail($courseId);

        // Prevent duplicate certificates
        if (Certificate::where('user_id', $user->id)->where('course_id', $courseId)->exists()) {
            return response()->json(['message' => 'Certificate already generated'], 409);
        }

        // 1. Check enrollment
        if (!$user->enrollments()->where('course_id', $courseId)->exists()) {
            return response()->json(['message' => 'User not enrolled in this course'], 403);
        }

        // 2. Check ALL lessons completed
        $lessonIds = $course->lessons->pluck('id');

        $completedLessonsCount = LessonCompletion::where('user_id', $user->id)
            ->whereIn('lesson_id', $lessonIds)
            ->count();

        if ($completedLessonsCount !== $lessonIds->count()) {
            return response()->json(['message' => 'All lessons must be completed'], 403);
        }

        // 3. Check ALL quizzes passed
        $quizIds = Quiz::where('course_id', $courseId)
            ->orWhereIn('lesson_id', $lessonIds)
            ->pluck('id');

        foreach ($quizIds as $quizId) {
            $attempt = QuizAttempt::where('user_id', $user->id)
                ->where('quiz_id', $quizId)
                ->orderByDesc('score')
                ->first();

            if (!$attempt) {
                return response()->json(['message' => 'All quizzes must be attempted'], 403);
            }

            $quiz = Quiz::find($quizId);

            if ($attempt->score < $quiz->passing_score) {
                return response()->json(['message' => 'All quizzes must be passed'], 403);
            }
        }

        // 4. Generate certificate
        $verificationCode = strtoupper(Str::random(12));

        $certificate = Certificate::create([
            'user_id' => $user->id,
            'course_id' => $courseId,
            'verification_code' => $verificationCode,
            'generated_at' => now(),
        ]);

        $pdf = Pdf::loadView('certificates.certificate', [
            'studentName' => $user->name,
            'courseTitle' => $course->title,
            'instructorName' => $course->creator->name ?? 'Instructor',
            'date' => now()->toDateString(),
            'verificationCode' => $verificationCode,
        ]);

        $fileName = "certificate_{$certificate->id}.pdf";
        $path = storage_path("app/public/certificates/{$fileName}");

        $pdf->save($path);

        $certificate->update([
            'download_url' => "/storage/certificates/{$fileName}",
        ]);

        return response()->json([
            'message' => 'Certificate generated successfully',
            'download_url' => $certificate->download_url,
            'verification_code' => $verificationCode,
        ]);
    }

    /**
     * Public verification endpoint.
     */
    public function verify($code)
    {
        $certificate = Certificate::where('verification_code', $code)
            ->with(['user', 'course'])
            ->firstOrFail();

        return response()->json([
            'student' => $certificate->user->name,
            'course' => $certificate->course->title,
            'generated_at' => $certificate->generated_at,
            'verification_code' => $certificate->verification_code,
        ]);
    }
}