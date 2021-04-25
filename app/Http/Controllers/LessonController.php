<?php

namespace App\Http\Controllers;

use App\Lesson;
use App\Chapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LessonController extends Controller
{
    public function index(Request $request) {
        $lessons = Lesson::query();

        $chapter = Chapter::find($request->query('chapter_id'));
        
        if(!$chapter) {
            return response()->json([
                'status' => 'error',
                'message' => 'chapter not found'
            ], 404);
        }

        $lessons->where('chapter_id', $request->chapter_id);
        
        return response()->json([
            'status' => 'success',
            'data' => $lessons->get()
        ]);
    }

    public function show($id) {
        $lesson = Lesson::find($id);

        if(!$lesson) {
            return response()->json([
                'status' => 'error',
                'message' => 'lesson not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $lesson
        ]);
    }


    public function create(Request $request) {
        // cek validasi
        $rules = [
            'name' => 'string|required',
            'video' => 'required|string',
            'chapter_id' => 'required|integer'
        ];

        $data = $request->all();

        $validator = Validator::make($data, $rules);

        if($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        // cek chapter id ada atau tidak
        $chapter = Chapter::find($request->chapter_id);

        if(!$chapter) {
            return response()->json([
                'status' => 'error',
                'message' => 'chapter not found'
            ], 404);
        }

        $lesson = Lesson::create($data);

        return response()->json([
            'status' => 'success',
            'data' => $lesson
        ]);
    }

    public function update(Request $request, $id) {
        $rules = [
            'name' => 'string|required',
            'video' => 'string|required',
            'chapter_id' => 'integer|required'
        ];

        $data = $request->all();

        $validator = Validator::make($data, $rules);

        if($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        $lesson = Lesson::find($id);

        if(!$lesson) {
            return response()->json([
                'status' => 'error',
                'message' => 'lesson not found'
            ], 404);
        }

        $lesson->update($data);

        return response()->json([
            'status' => 'success',
            'data' => $lesson
        ]);
    }

    public function destroy($id) {
        $lesson = Lesson::find($id);

        if(!$lesson) {
            return response()->json([
                'status' => 'error',
                'message' => 'lesson not found'
            ], 404);
        }

        $lesson->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'lesson deleted'
        ]);
    }
}
