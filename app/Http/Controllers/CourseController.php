<?php

namespace App\Http\Controllers;

use File;
use App\Course;
use App\Mentor;
use App\Review;
use App\Chapter;
use App\MyCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    public function index(Request $request) {
        $courses = Course::query();

        $q = $request->query('q');
        $status = $request->query('status');

        $courses->when($q, function($query) use ($q) {
            return $query->whereRaw("name LIKE '%".strtolower($q."%'"));
        });

        $courses->when($status, function($query) use ($status) {
            return $query->where('status', '=', $status);
        });

        return response()->json([
            'status' => 'success',
            'data' => $courses->paginate(10)
        ]);
    }

    public function show($id) {
        $course = Course::with('chapter.lesson')->with('imageCourse')->with('mentor')->find($id);

        if(!$course) {
            return response()->josn([
                'status' => 'error',
                'message' => 'course not found'
            ]);
        }

        // get all review and parse to array
        $reviews = Review::where('course_id', $id)->get()->toArray();

        // // cek jika ada review atau tidak
        if(count($reviews) > 0) {
            // jika ada select semua user id dari tiap array review
            $userIds = array_column($reviews, 'user_id');

            // get data user by ids / get user lebih dari satu
            $users = getUserByIds($userIds);

            // cek ada user atau tidak
            if($users['status'] === 'error') {
                // jika tidak ada user maka return array kosong
                $reviews = [];
            } else {
                // jika user data
                foreach($reviews as $key => $review) {
                    $userIndex = array_search($review['user_id'], array_column($users['data'], 'id'));
                    $reviews[$key]['users'] = $users['data'][$userIndex];
                }
            }
        }
        
        $totalStudents = MyCourse::where('course_id', $id)->count();
        $totalVideo = Chapter::where('course_id', $id)->withCount('lesson')->get()->toArray();
        $finalTotalVideos = array_sum(array_column($totalVideo, 'lesson_count'));
        
        // echo "<prev>".print_r($finalTotalVideos, 1)."</prev>";

        $course['reviews'] = $reviews;
        $course['total_students'] = $totalStudents;
        $course['total_videos'] = $finalTotalVideos;

        return response()->json([
            'status' => 'success',
            'data' => $course
        ]);


    }


    public function create(Request $request) {
        // buat validator
        $rules = [
            'name' => 'string|required',
            'certificate' => 'boolean|required',
            'thumbnail' => 'image|required',
            'type' => 'required|in:free,premium',
            'status' => 'required|in:draft,published',
            'price' => 'integer',
            'level' => 'required|in:all-level,beginner,intermediate,advance',
            'description' => 'string',
            'mentor_id' => 'required|integer'
        ];

        $data = $request->all();

        // cek validator 
        $validator = Validator::make($data, $rules);

        if($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        // cek mentor ada atau tidak ada
        $mentor = Mentor::find($request->mentor_id);

        if(!$mentor) {
            return response()->json([
                'status' => 'error',
                'message' => 'mentor not found'
            ], 404);
        }

        if($request->file('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('assets/images', 'public');
        }

        $course = Course::create($data);

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);

    }

    public function update(Request $request,$id) {
        // buat validasi
        $rules = [
            'name' => 'string|required',
            'certificate' => 'boolean|required',
            'thumbnail' => 'string|url',
            'type' => 'required|in:free,premium',
            'status' => 'required|in:draft,published',
            'price' => 'integer',
            'level' => 'required|in:all-level,beginner,intermediate,advance',
            'description' => 'text',
            'mentor_id' => 'required|integer'
        ];

        // get data by id
        $course = Course::find($id);

        if(!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'course not found'
            ], 404);
        }

        // get data request
        $data = $request->all();

        $validator = Validator::make($data, $rules);

        if($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        // cek mentor ada atau tidak
        $mentor = Mentor::find($request->mentor_id);

        if(!$mentor) {
            return response()->json([
                'status' => 'error',
                'message' => 'mentor not found'
            ], 404);
        }

        $course->update($data);

        return response()->json([
            'status' => 'success',
            'data' => $course
        ]);
    }

    public function destroy($id) {
        $course = Course::find($id);

        if(!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'course not found'
            ], 404);
        }

        $image = $course->thumbnail;

        $array = explode('/', ltrim($image, '/'));

        $path = 'storage/assets/images/';

        File::delete($path.end($array));

        $course->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'course deleted'
        ]);
    }
}
