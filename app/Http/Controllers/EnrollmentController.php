<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EnrollmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'message' => 'success',
            'enrollment' => Enrollment::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $userId = auth()->id(); // Always use authenticated user

        $existingEnrollment = Enrollment::where('user_id', $userId)
            ->where('course_id', $validated['course_id'])
            ->first();

        if ($existingEnrollment) {
            return response()->json([
                'message' => 'You are already enrolled in this course',
                'enrollment' => $existingEnrollment
            ], 422);
        }

        $enrollment = Enrollment::create([
            'user_id' => $userId,
            'course_id' => $validated['course_id'],
            'progress_percentage' => 0,
        ]);

        return response()->json([
            'message' => 'success',
            'enrollment' => $enrollment
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Enrollment $enrollment)
    {
        return response()->json([
            'message' => 'success',
            'lesson' => $enrollment
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Enrollment $enrollment)
    {
        $validated = $request->validate([
            'user_id' => 'exists:users,id',
            'course_id' => 'exists:courses,id',
        ]);

        $enrollment->update($validated);

        return response()->json([
            'message' => 'success',
            'enrollment' => $enrollment
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Enrollment $enrollment)
    {
        $enrollment->delete();

        return response()->json([
            'message' => 'Success',
        ]);
    }
}
