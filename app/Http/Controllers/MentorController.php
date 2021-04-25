<?php

namespace App\Http\Controllers;

use App\Mentor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MentorController extends Controller
{
    public function index() {
        $mentors = Mentor::all();

        return response()->json([
            'status' => 'success',
            'message' => $mentors
        ]);
    }

    public function create(Request $request) {
       // buat validasi request
       $rules = [
            'name' => 'string|required',
            'profile' => 'string|required',
            'email' =>  'email|required',
            'profession' => 'required|string'
       ];

       // tampung data request
       $data = $request->all();

       // cel vaidasi 
       $validator = Validator::make($data, $rules);

       if($validator->fails()) {
           return response()->json([
               'status' => 'error',
               'message' => $validator->errors()
           ], 400);
       }

       $mentor = Mentor::create($data);

       return response()->json([
           'status' => 'success',
           'data' => $mentor
       ]);
    }

    public function update(Request $request, $id) {
        // buat validator
        $rules = [
            'name' => 'string|required',
            'email' => 'email|required',
            'profession' => 'string|required',
            'profile' => 'string|required'
        ];

        // get all data request
        $data = $request->all();

        $validator = Validator::make($data, $rules);

        // cek validasi
        if($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        // cek mentor ada atau tidak
        $mentor = Mentor::find($id);

        if(!$mentor) {
            return response()->json([
                'status' => 'error',
                'message' => 'mentor not found'
            ], 404);
        }

        $mentor->update($data);

        return response()->json([
            'status' => 'success',
            'message' => $mentor
        ]);

    }

    public function show($id) {
        $mentor = Mentor::find($id);

        if(!$mentor) {
            return response()->json([
                'status' => 'error',
                'message' => 'mentor not found'
            ], 400);
        }

        return response()->json([
            'status' => 'success',
            'message' => $mentor
        ]);
    }

    public function destroy($id) {
        $mentor = Mentor::find($id);

        if(!$mentor) {
            return response()->json([
                'status' => 'error',
                'message' => 'mentor not found'
            ], 400);
        }

        $mentor->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'mentor deleted'
        ]);
    }
}
