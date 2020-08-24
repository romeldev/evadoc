<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Calculator extends Model
{
    
    // OK
    public static function recordTeachers( $evaluation_id, $school_code )
    {
        $evaluation = Evaluation::find($evaluation_id);

        // 1. DB Externa: consultar docentes de una escuela para un semestre especifico

        $teachers = RepoUnprg::allTeachers( $school_code, $evaluation->period_id );

        // 2. DB Local: consultar las calificaciones de los docentes de una escuela para una evaluacion especifica
        $qualifies = Qualify::where('evaluation_id', $evaluation_id)
            ->where('school_code', $school_code)
            ->get();

        // 3. Obtener la calificacon para cada docente

        foreach($teachers as $teacher)
        {
            $qualify_avg = $qualifies->where('teacher_code', $teacher->code)->avg('avg');
            $teacher->record = $qualify_avg? $qualify_avg: 0;
        }


        $data = [];
        foreach(collect($teachers)->sortByDesc('record') as $row) $data[] = $row;
        return $data;
    }

    public static function courseTeacher($period_code, $teacher_code, $course_code)
    {
        $course = Unprg::courseByCode($course_code);

        $course->indicators = [];

        return $course;
    }

    // OK
    public static function metaEvaluationQualify( $school_code, $evaluation_id, $teacher_code)
    {
        $evaluation = Evaluation::find( $evaluation_id );
        $teacher = RepoUnprg::oneTeacherWithCourses( $school_code, $evaluation->period_id, $teacher_code );
        $data['evaluation'] = $evaluation;
        $data['teacher'] = $teacher;
        return $data;
    }

    public static function qualifyCourse($request)
    {
        $evaluation = Evaluation::find($request->evaluation_id);

        $indicators = $evaluation->indicators()->select('id', 'name', 'editable', 'type_id', \DB::raw('0 as value'))->get();

        return ['indicators' => $indicators];
    }

}
