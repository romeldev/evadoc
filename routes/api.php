<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    $user = $request->user();
    $user->avatar = $user->avatar;
    return $user;
});


Route::post('login', 'API\AuthController@login');
Route::middleware('auth:api')->post('logout', 'API\AuthController@logout');

Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');

Route::get('common', 'API\CommonController@index');
Route::apiResource('permissions', 'API\PermissionController');
Route::apiResource('roles', 'API\RoleController');
Route::apiResource('users', 'API\UserController');
Route::apiResource('menus', 'API\MenuController');



// Route::apiResource('schools', 'API\SchoolController');
// Route::apiResource('faculties', 'API\FacultyController');


Route::group(['middleware' => ['auth:api']], function(){
    Route::apiResource('scales', 'API\ScaleController');
    Route::apiResource('levels', 'API\LevelController');
    Route::apiResource('surveys', 'API\SurveyController');
    Route::apiResource('evaluations', 'API\EvaluationController');



    Route::get('/calculator/qualify', 'API\CalculatorController@qualify');
    Route::get('/calculator/record-teachers', 'API\CalculatorController@recordTeachers');
    Route::get('/calculator/meta-evaluation-qualify', 'API\CalculatorController@metaEvaluationQualify');
    Route::get('/calculator/course-qualify', 'API\CalculatorController@courseQualify');
    Route::post('/calculator/save-course-qualify', 'API\CalculatorController@saveCourseQualify');
});







Route::group(['prefix' => 'students'], function () {
    Route::get('/{code}', 'API\StudentController@studentCourses');
    // Route::get('/last-evaluation', 'API\StudentController@lastEvaluation');
    // Route::get('/student/{cod}', 'API\StudentController@student');
    // Route::get('/student/{cod}/courses', 'API\StudentController@studentCourses');
    // Route::get('/surveys', 'API\StudentController@surveys');
    // Route::post('/surveys', 'API\StudentController@saveSurveys');
});


Route::post('/student/survey', 'API\StudentController@survey');
Route::get('/student/evaluation', 'API\StudentController@evaluation');
Route::get('/student/{code}', 'API\StudentController@index');

Route::get('reports/meta', 'API\ReportController@meta');
Route::get('reports/report', 'API\ReportController@report');
Route::post('reports/generate', 'API\ReportController@generate');
Route::get('reports/general_download', 'API\ReportController@general_download');

Route::get('/exclusions/teachers', 'API\ExclusionController@teachers');
Route::post('/exclusions/save-teachers-courses-exclusions', 'API\ExclusionController@saveTeachersCoursesExclusions');


Route::get('/config', 'API\ConfigController@get');
Route::post('/config', 'API\ConfigController@post');
