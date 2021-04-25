<?php

namespace App\Http\Controllers;

use App\Course;
use App\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PreviewController extends Controller
{
    public function create(Request $request) {
        $rules = [
            'course_id' => 'integer|required',
            'user_id' => 'integer|required',
            'rating' => 'integer|min:1|max:5|required',
            'note' => 'string'
        ];

        $data = $request->all();

        $validator = Validator::make($data, $rules);

        if($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
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
        
        $review = Review::create($data);

        return response()->json([
            'status' => 'success',
            'data' => $review
        ]);
    }

    public function update(Request $request, $id) {
        $rules = [
            'rating' => 'integer|min:1|max:5|required',
            'note' => 'string'
        ];

        $data = $request->all();

        $validator = Validator::make($data, $rules);

        if($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        $review = Review::find($id);

        if(!$review) {
            return response()->json([
                'status' => 'error',
                'message' => 'review not found'
            ], 404);
        }

        $review->update($data);

        return response()->json([
            'status' => 'success',
            'data' => $review
        ]);
    }

    public function destroy($id) {
        $review = Review::find($id);

        if(!$review) {
            return response()->json([
                'status' => 'error',
                'message' => 'review not found'
            ], 404);
        }

        $review->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'review deleted'
        ]);
    }
}
