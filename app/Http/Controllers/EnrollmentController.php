<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\enrollment;

class EnrollmentController extends Controller
{
    // Get all enrollments for authenticated user
    public function getUserEnrollments()
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $enrollments = enrollment::where('user_id', auth()->user()->id)
            ->with(['course.instructor', 'course.category'])
            ->get();

        return response()->json(['enrollments' => $enrollments], 200);
    }

    // Get specific enrollment details
    public function getEnrollmentDetails($enrollmentId)
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $enrollment = enrollment::with(['course.instructor', 'course.category', 'orderItem'])
            ->find($enrollmentId);

        if (!$enrollment) {
            return response()->json(['message' => 'Enrollment not found'], 404);
        }

        if ($enrollment->user_id !== auth()->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json(['enrollment' => $enrollment], 200);
    }

    // Check if user is enrolled in a course
    public function checkEnrollment($courseId)
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $enrollment = enrollment::where('user_id', auth()->user()->id)
            ->where('course_id', $courseId)
            ->first();
        $isEnrolled = $enrollment ? true : false;
        return response()->json(['enrolled' => $isEnrolled, 'enrollment' => $enrollment], 200);     
    }
    // remove enrollment (optional) course 
    public function removeEnrollment($enrollmentId)
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $enrollment = enrollment::find($enrollmentId);

        if (!$enrollment) {
            return response()->json(['message' => 'Enrollment not found'], 404);
        }

        if ($enrollment->user_id !== auth()->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $enrollment->delete();

        return response()->json(['message' => 'Enrollment removed successfully'], 200);
    }
}
