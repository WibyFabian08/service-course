<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\MentorController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\PreviewController;
use App\Http\Controllers\MyCourseController;
use App\Http\Controllers\ImageCourseController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// mentor api
Route::get('mentors', "MentorController@index");
Route::post('mentors', 'MentorController@create');
Route::put('mentors/{id}', 'MentorController@update');
Route::get('mentors/{id}', 'MentorController@show');
Route::delete('mentors/{id}', 'MentorController@destroy');

// courses
Route::get('courses', 'CourseController@index');
Route::get('courses/{id}', 'CourseController@show');
Route::post('courses', 'CourseController@create');
Route::put('courses/{id}', 'CourseController@update');
Route::delete('courses/{id}', 'CourseController@destroy');

// chapter
Route::get('chapters', 'ChapterController@index');
Route::post('chapters', 'ChapterController@create');
Route::put('chapters/{id}', 'ChapterController@update');
Route::get('chapters/{id}', 'ChapterController@show');
Route::delete('chapters/{id}', 'ChapterController@destroy');

// lesson 
Route::get('lessons', 'LessonController@index');
Route::get('lessons/{id}', 'LessonController@show');
Route::post('lessons', 'LessonController@create');
Route::put('lessons/{id}', 'LessonController@update');
Route::delete('lessons/{id}', 'LessonController@destroy');

// image course
Route::post('image-courses', 'ImageCourseController@create');
Route::delete('image-courses/{id}', 'ImageCourseController@destroy');

// my course
Route::get('my-courses', 'MyCourseController@index');
Route::post('my-courses', 'MyCourseController@create');

// preview
Route::post('reviews', 'PreviewController@create');
Route::put('reviews/{id}', 'PreviewController@update');
Route::delete('reviews/{id}', 'PreviewController@destroy');
