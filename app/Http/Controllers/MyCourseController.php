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
            ], 409);
        }

        // cek jika course premium maka masuk orders 
        if($course->type === 'premium') {
            if($course->price === 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'price can\'t be 0'
                ]);
            }

            // insert data ke API create order
            $order = postOrder([
                'user' => $user['data'],
                'course' => $course->toArray()
            ]);

            if($order['status'] === 'error') {
                return response()->json([
                    'status' => $order['status'],
                    'message' => $order['message']
                ], $order['http_code']);
            }

            return response()->json([
                'status' => $order['status'],
                'data' => $order['data']
            ]);
        } else {
            $myCourse = MyCourse::create($data);
    
            return response()->json([
                'status' => 'success',
                'data' => $myCourse
            ]);
        }
    }

    public function createPremiumAccess(Request $request) {
        $data = $request->all();

        $myCourse = MyCourse::create($data);

        return response()->json([
            'status' => 'success',
            'data' => $myCourse
        ]);
    }
}
