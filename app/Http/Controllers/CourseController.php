<?php

namespace App\Http\Controllers;

use App\Models\course;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\Section;
use App\Models\Lecture;

class CourseController extends Controller
{
    public function getCategories()
    {
        // Anyone can view categories
        $categories = Category::all(['id', 'name', 'slug']);
        return response()->json($categories, 200);
    }



    public function store(Request $request)
    {
        // RBAC + OBAC: Check permission and role
        if (!$request->user()->can('create courses')) {
            return response()->json(['message' => 'Unauthorized - You do not have permission to create courses'], 403);
        }

        $this->authorize('create', course::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'level' => 'required|in:beginner,intermediate,advanced',
            'category_id' => 'required|exists:categories,id',
            'slug' => 'nullable|string|unique:courses,slug',
            'thumbnail' => 'nullable|image|max:2048',
            'language' => 'nullable|string|max:100',
            'status' => 'required|in:draft,published,archived',
        ]);

        $thumbnail = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnail = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        // Generate unique slug
        $slug = $validated['slug'] ?? Str::slug($validated['title']);
        $originalSlug = $slug;
        $counter = 1;
        
        while (course::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $course = course::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'duration' => $validated['duration'],
            'level' => $validated['level'],
            'category_id' => $validated['category_id'],
            'instructor_id' => $request->user()->id,
            'slug' => $slug,
            'thumbnail' => $thumbnail,
            'language' => $validated['language'] ?? 'English',
            'status' => $validated['status'],
        ]);

        return response()->json(['message' => 'Course created successfully', 'course' => $course], 201);
    }

    public function show($id)
    {
        $course = course::with('category', 'instructor')->find($id);
        
        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }
        
        // OBAC: Anyone can view a published course
        $this->authorize('view', $course);
        
        return response()->json($course, 200);
    }
        public function index()
    {
        $courses = course::with('category', 'instructor')->get();
        return response()->json($courses, 200);
    }

    public function update(Request $request, $id)
    {
        $course = course::find($id);
        
        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        // RBAC + OBAC: Check permission and object ownership
        if (!$request->user()->can('update courses')) {
            return response()->json(['message' => 'Unauthorized - You do not have permission to update courses'], 403);
        }

        $this->authorize('update', $course);

        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'duration' => 'nullable|integer|min:1',
            'level' => 'nullable|in:beginner,intermediate,advanced',
            'category_id' => 'nullable|exists:categories,id',
            'language' => 'nullable|string|max:100',
            'status' => 'nullable|in:draft,published,archived',
        ]);

        $course->update($validated);

        return response()->json(['message' => 'Course updated successfully', 'course' => $course], 200);
    }
    //create cource sections and lectures methods here
    public function createSection(Request $request, $courseId)
    {
        // Check permission
        if (!$request->user()->can('create sections')) {
            return response()->json(['message' => 'Unauthorized - You do not have permission to create sections'], 403);
        }

        // Validate input
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'position' => 'nullable|integer|min:0',
        ]);

        // Ensure course exists
        $course = course::find($courseId);
        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        // OBAC: Ensure the authenticated user owns the course or is admin
        $this->authorize('manageSection', $course);

        $section = Section::create([
            'course_id' => $courseId,
            'title' => $validated['title'],
            'position' => $validated['position'] ?? 0,
        ]);

        return response()->json(['message' => 'Section created successfully', 'section' => $section], 201);
    }
    // add video lecture to section
    public function addLecture(Request $request, $sectionId)
    {
        // Check permission
        if (!$request->user()->can('create lectures')) {
            return response()->json(['message' => 'Unauthorized - You do not have permission to create lectures'], 403);
        }

        // Validate input
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'duration' => 'nullable|integer|min:0',
            'video' => 'nullable|mimes:mp4,mov,avi,wmv|max:20480',
            'video_url' => 'nullable|string',
            'is_preview' => 'nullable|boolean',
            'position' => 'nullable|integer|min:0',
        ]);

        // Ensure section exists
        $section = Section::find($sectionId);
        if (!$section) {
            return response()->json(['message' => 'Section not found'], 404);
        }

        // Ensure the authenticated user owns the parent course
        $course = course::find($section->course_id);
        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }
        
        // OBAC: Ensure the authenticated user owns the course or is admin
        $this->authorize('manageLecture', $course);

        // Handle video upload
        $videoUrl = null;
        if ($request->hasFile('video')) {
            $videoUrl = $request->file('video')->store('lectures', 'public');
        } elseif (!empty($validated['video_url'])) {
            $videoUrl = $validated['video_url'];
        }

        $lecture = Lecture::create([
            'section_id' => $sectionId,
            'title' => $validated['title'],
            'content' => $validated['content'] ?? null,
            'duration' => $validated['duration'] ?? 0,
            'video_url' => $videoUrl,
            'is_preview' => isset($validated['is_preview']) ? (bool)$validated['is_preview'] : false,
            'position' => $validated['position'] ?? 0,
        ]);

        return response()->json(['message' => 'Lecture added successfully', 'lecture' => $lecture], 201);
    }
    //store lecture video in storage and return url
    public function uploadLectureVideo(Request $request)
    {
        // Check permission
        if (!$request->user()->can('upload lecture videos')) {
            return response()->json(['message' => 'Unauthorized - You do not have permission to upload lecture videos'], 403);
        }

        $request->validate([
            'video' => 'required|mimes:mp4,mov,avi,wmv|max:20480', // max 20MB
        ]);

        if ($request->hasFile('video')) {
            $path = $request->file('video')->store('lectures', 'public');
            return response()->json(['message' => 'Video uploaded successfully', 'video_url' => $path], 201);
        }

        return response()->json(['message' => 'No video file provided'], 400);
    }

    // Get sections for a course
    public function getSections($courseId)
    {
        $course = course::find($courseId);
        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        $sections = Section::where('course_id', $courseId)
            ->orderBy('position')
            ->with(['lectures' => function($query) {
                $query->orderBy('position');
            }])
            ->get();

        return response()->json($sections, 200);
    }

    // Get lectures for a section
    public function getLectures($sectionId)
    {
        $section = Section::find($sectionId);
        if (!$section) {
            return response()->json(['message' => 'Section not found'], 404);
        }

        $lectures = Lecture::where('section_id', $sectionId)
            ->orderBy('position')
            ->get();

        return response()->json($lectures, 200);
    }

}

