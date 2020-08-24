<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Model\Evaluation;
use App\Model\RepoUnprg;
use App\Model\Reply;
use App\Model\Student;

class StudentController extends Controller
{

    public function index( $student_code )
    {
        $period_code = 1;
        $student = Student::info( $period_code, $student_code);

        return response()->json($student, 200);
    }

    public function evaluation()
    {
        return Student::evaluation();
    }

    public function survey(Request $request)
    {
        $request->validate([
            'school_code' => 'required',
            'evaluation_id' => 'required',
            'student_code' => 'required',
            'teacher_code' => 'required',
            'course_code' => 'required',
            'course_group' => 'required',
            'survey_items' => 'required|array|min:1',
            'survey_items.*.value' => 'required',
        ]);

        return Student::saveReply($request);
    }
}
