<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Model\Calculator;
use App\Model\Qualify;

class CalculatorController extends Controller
{
    // OK: Listar calificacion de los docentes de una escuela para una evaluacion
    public function recordTeachers(Request $request)
    {
        $school_code = $request->user()->school_code;
        // $school_code = 24;

        $request->validate([
            'evaluation_id' => 'required',
        ]);

        return Calculator::recordTeachers( $request->evaluation_id, $school_code );
    }

    public function metaEvaluationQualify(Request $request)
    {
        $school_code = $request->user()->school_code;

        $request->validate([
            'evaluation_id' => 'required',
            'teacher_code' => 'required',
        ]);

        return Calculator::metaEvaluationQualify( $school_code, $request->evaluation_id, $request->teacher_code );
    }

    public function courseQualify(Request $request)
    {
        $request->validate([
            'evaluation_id' => 'required',
            'teacher_code' => 'required',
            'course_key' => 'required',
        ]);

        return Qualify::courseQualify( 
            $request->evaluation_id, 
            $request->teacher_code, 
            $request->course_key,
            $request->user()->school_code
        );
    }

    public function saveCourseQualify(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'evaluation_id' => 'required',
            'teacher_code' => 'required',
            'course_key' => 'required',
            'indicators' => 'required|array|min:1',
            'indicators.*.value' => 'required|numeric',
        ]);

        $qualify = Qualify::find($request->id);
        $qualify->updated_at = \Carbon\Carbon::now();
        $qualify->save();
        $avg = $qualify->saveIndicators($request->indicators);
        $qualify->avg = $avg;
        $qualify->save();
        return true;
    }
}
