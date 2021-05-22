<?php

namespace App\Http\Controllers;

use File;
use App\Course;
use App\ImageCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ImageCourseController extends Controller
{
    public function create(Request $request) {
        $rules = [
            'image' => 'image|required',
            'course_id' => 'integer|required'
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

        if($request->file('image')) {
            $data['image'] = $request->file('image')->store('assets/image-course', 'public');
        }

        $image = ImageCourse::create($data);
        

        return response()->json([
            'status' => 'success',
            'data' => $image
        ]);
    }

    public function destroy($id) {
        $image = ImageCourse::find($id);

        if(!$image) {
            return response()->json([
                'status' => 'error',
                'message' => 'image not found'
            ], 404);
        }
        $image_course = $image->image;

        $array = explode('/', ltrim($image_course, '/'));

        $path = 'storage/assets/image-course/';

        File::delete($path.end($array));

        $image->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'image deleted'
        ]);
    }
}
