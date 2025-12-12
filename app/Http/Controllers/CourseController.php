<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Course::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:50',
            'description' => 'required|string|max:100',
            'thumbnail' => 'file',
            'instructor' => 'required|string',
            'price' => 'required|numeric|min:0|max:1000',
        ]);

        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('images', 'public');
            $validated['thumbnail'] = $path;
        }

        $course = Course::create($validated);

        return response()->json([
            'messages' => 'Success Create Course',
            'course' => $course
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        return response()->json([
            'messages' => "Success fetch course id {$course->id}",
            'course' => $course
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:50',
            'description' => 'required|string|max:100',
            'thumbnail' => 'file',
            'instructor' => 'required|string',
            'price' => 'required|numeric|min:0|max:1000',
        ]);

        if ($request->hasFile('thumbnail')){
            dd("test");
            $validated['thumbnail'] = $request->file('thumbnail')->store('images','public');
        }

        dd($validated);

        $course->update($validated);

        return response()->json([
            'message' => 'Success edit course',
            'course' => $course
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        //
    }
}
