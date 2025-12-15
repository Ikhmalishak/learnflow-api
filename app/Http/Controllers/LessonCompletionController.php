<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LessonCompletion;
use Illuminate\Http\Request;

class LessonCompletionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'message' => 'success',
            'lesson_completion' => LessonCompletion::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'lesson_id' => 'required|exists:lessons,id',
        ]);

        $lessonCompleted = LessonCompletion::where('user_id', $validated['user_id'])
            ->where('lesson_id', $validated['lesson_id'])->first();

        if($lessonCompleted){
            return response()->json([
                'message' => 'The user already completed this lesson',
                'lesson_completed' => $lessonCompleted
            ],422);
        }

        $completion = LessonCompletion::create([
            'user_id' => $validated['user_id'],
            'lesson_id' => $validated['lesson_id'],
            'completed_at' => now()
        ]);

        $lesson = Lesson::findOrFail($validated['lesson_id']);

        $total_lessons = $lesson->course->lessons()->count();

        $completed_lesson = LessonCompletion::where('user_id', auth()->id())->whereIn(
            'lesson_id',
            Lesson::where('course_id', $lesson->course_id)->pluck('id')
        )
            ->count();

        $percentage_progress = ($completed_lesson / $total_lessons) * 100;

        $enrollment = Enrollment::where('user_id', auth()->id())->where('course_id', $lesson->course->id);

        $enrollment->update([
            'progress_percentage' => 100
        ]);

        return response()->json([
            'message' => 'success',
            'lesson_completion' => $completion,
            'percentage_progress' => $percentage_progress
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(LessonCompletion $lessonCompletion)
    {
        return response()->json([
            'message' => 'success',
            'lesson_completion' => $lessonCompletion
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LessonCompletion $lessonCompletion)
    {
        $validated = $request->validate([
            'user_id' => 'exists:users,id',
            'lesson_id' => 'exists:lessons,id',
        ]);

        $lessonCompletion->update($validated);

        return response()->json([
            'message' => 'success',
            'lesson_completion' => $lessonCompletion
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LessonCompletion $lessonCompletion)
    {
        $lessonCompletion->delete();

        return response()->json([
            'message' => 'Success',
        ]);
    }
}
