<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Lesson::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required | exists:courses',
            'title' => 'required | string | max:50',
            'content' => 'string',
            'video_url' => 'string',
            'order' => 'required | integer'
        ]);

        $lesson = Lesson::create($validated);

        return response()->json([
            'message' => 'success',
            'lesson' => $lesson
        ]);
    }

    public function show(Lesson $lesson)
    {
        return response()->json([
            'message' => 'success',
            'lesson' => $lesson
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lesson $lesson)
    {
        $validated = $request->validate([
            'course_id' => 'exists:courses',
            'title' => 'string | max:50',
            'content' => 'string',
            'video_url' => 'string',
            'order' => 'integer'
        ]);

        $lesson->update($validated);

        return response()->json([
            'message' => 'success',
            'lesson' => $lesson
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lesson $lesson)
    {
        $lesson->delete();

        return response()->json([
            'message' => 'Success',
        ]);
    }
}
