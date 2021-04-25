<?php

namespace App\Http\Controllers;

use App\Course;
use App\MyCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MyCourseController extends Controller
{
    public function index(Request $request) {
        
        $myCourse = MyCourse::query()->with('course');
        
        $user = $request->query('user_id');
        $myCourse->when($user, function($query) use ($user) {
            return $query->where('user_id', '=', $user);
        });

        return response()->json([
            'status' => 'success',
            'data' => $myCourse->get()
        ]);
    }

    public function create(Request $request) {
        $rules = [
            'course_id' => 'integer|required',
            'user_id' => 'integer|required'
        ];

        $data = $request->all();

        $validator = Validator::make($data, $rules);

        if($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ]);
        }

        $course = Course::find($request->course_id);

        if(!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'course not found'
            ], 404);
        }

        $user = getUser($request->user_id);

        if($user['status'] === 'error') {
            return response()->json([
                'status' => $user['status'],
                'message' => $user['message']
            ], $user['http_code']);
        }

        $isMyCourseExist = MyCourse::where('course_id', $request->course_id)
                                    ->where('user_id', $request->user_id)
                                    ->exists();
        
        if($isMyCourseExist) {
            return response()->json([
                'status' => 'error', 
                'message' => 'you already have the course'
            ], 400);
        }

        $myCourse = MyCourse::create($data);

        return response()->json([
            'status' => 'success',
            'data' => $myCourse
        ]);

    }
}
