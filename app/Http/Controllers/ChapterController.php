<?php

namespace App\Http\Controllers;

use App\Course;
use App\Mentor;
use App\Chapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChapterController extends Controller
{
    public function index(Request $request) {
        $chapter = Chapter::query();
        $courseId = $request->query('course_id');

        $chapter->where('course_id', $courseId);

        return response()->json([
            'status' => 'success',
            'data' => $chapter->get()
        ]);

    }

    public function show($id) {
        $chapter = Chapter::find($id);

        if(!$chapter) {
            return response()->json([
                'status' => 'error',
                'message' => 'chapter not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $chapter
        ]);
    }

    public function create(Request $request) {
        // buat validator
        $rules = [
            'name' => 'string|required',
            'course_id' => "required|integer"
        ];

        // get all data request
        $data = $request->all();

        // cek validasi
        $validator = Validator::make($data, $rules);

        if($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        // cek mentor ada atau tidak ada
        $course = Course::find($request->course_id);

        if(!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'course not found'
            ], 404);
        }

        $chapter = Chapter::create($data);

        return response()->json([
            'status' => 'success',
            'data' => $chapter
        ]);
    }

    public function update(Request $request, $id) {
        // buat validator
        $rules = [
            'name' => 'string|required',
            'course_id' => 'integer|required'
        ];

        // get all data request
        $data = $request->all();

        // cek validasi
        $validator = Validator::make($data, $rules);

        if($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ]);
        }

        // cek course ada atau tidk
        $course = Course::find($request->course_id);

        if(!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'course not found'
            ], 404);
        }

        $chapter = Chapter::find($id);

        if(!$chapter) {
            return response()->json([
                'status' => 'error',
                'message' => 'chapter not found'
            ], 404);
        }

        $chapter->update($data);

        return response()->json([
            'status' => 'success',
            'data' => $chapter
        ]);
    }

    public function destroy($id) {
        $chapter = Chapter::find($id);

        if(!$chapter) {
            return response()->json([
                'status' => 'error',
                'message' => 'chapter not found'
            ], 404);
        }

        $chapter->delete();

        return response()->json([
            'status' => 'success', 
            'message' => 'chapter deleted'
        ]);
    }
}
