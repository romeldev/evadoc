<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\RepoUnprg;
use App\Model\Evaluation;
use App\Model\Reply;

class CheckController extends Controller
{
    public function checkStudent( $student_code )
    {
        $data['status'] = 'FAIL';
        $data['code'] = 'UNKNOWN';

        if( _const('CHECK_STUDENT') == 0 ) {
            $data['code'] = 'SYSTEM_DISABLED';
            return $data;
        }

        // 1. Get current evaluation
        $evaluation = Evaluation::currentEvaluation();

        if( $evaluation == null){
            $data['code'] = 'NOT_EVALUATION';
            return $data;
        }

        // 2. Get student info
        $student = RepoUnprg::oneStudent( $student_code, $evaluation->period_id );
        if( $student == null){
            $data['code'] = 'STUDENT_NOT_FOUND';
            return $data;
        }

        // 3. Check if all student courses(enable) was replied;
        $replies = Reply::where([
            'evaluation_id' => $evaluation->id,
            'school_code' => $student->school_code,
            'student_code' => $student->code,
        ])->get();

        // 4. Check student courses equals replied courses by student
        if( $student->courses->count() == $replies->count() ){
            $data['status'] = 'OK';
            $data['code'] = 'COMPLETE';
        }else{
            $data['code'] = 'IMCOMPLETE';
        }

        // use 'OK' or 'COMPLETE' for check a student
        return $data;
    }
}
