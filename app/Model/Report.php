<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{

    const TYPE_INDICATORS = 1;
    const TYPE_SURVEY = 2;
    const TYPE_SURVEY_AVG = 3;
    const TYPE_SURVEY_SINGLE = 4;

    // Report #1
    public static function reportIndicators( $evaluation_id, $school_code )
    {
        $evaluation = Evaluation::find( $evaluation_id );

        $indicatorWithQualify = collect(RepoEvadoc::indicatorsWithQualifies($evaluation->id, $school_code ) );

        $evaluation->indicators = $evaluation->indicators()->orderBy('order', 'asc')->get();

        $eIntervals = $evaluation->level? $evaluation->level->intervals: [];

        $teachers = RepoUnprg::vTreeTeachers($school_code, $evaluation->period_id );


        foreach($teachers as $key => $teacher) {
            foreach($teacher->courses as $keyc => $course)
            {
                $couseIndicators = $indicatorWithQualify
                    ->where('teacher_code', $teacher->code)
                    ->where('course_key', $course->key)
                    ->toArray();
                
                $course->indicators = [];

                foreach($couseIndicators as $row) $course->indicators[$row->indicator_id] = $row;

                $course->indicators = collect($course->indicators);
                $course->total = $course->indicators->sum('value');
                $course->level = Level::evalInIntervals($eIntervals, $course->total);

            }
            static::teacherMath($teacher, $eIntervals);
        }

        $evaluation->teachers = $teachers;

        return $evaluation;
    }

    // Report #2
    public static function reportSurvey( $evaluation_id, $school_code )
    {
        $evaluation = Evaluation::find($evaluation_id);
        $survey = $evaluation->survey;
        $level = $survey->level;

        $data = collect( Reply::listTeachersReplies($evaluation_id, $school_code) );

        $teachersTotal = RepoUnprg::allTeachers( $school_code, $evaluation->period_id, false); // sin filtro

        $teachers = RepoUnprg::allTeachers( $school_code, $evaluation->period_id );

        foreach($teachers as $teacher)
        {
            $teacher->score = $data->where('teacher_code', $teacher->code)->avg('value');
            $teacher->score_level = Level::evalInIntervals($level->intervals, $teacher->score);
        }

        static::dataSurveyChart($teachers, $level);

        $data = new \Stdclass;
        $data->teachers = $teachers;
        $data->total_teachers = $teachersTotal->count();
        $data->number_teachers_evaluated = $teachers->count();
        $data->school = RepoUnprg::oneSchool($school_code);
        $data->evaluation = $evaluation;
        $data->datachart = static::dataSurveyChart($teachers, $level);

        return $data;
    }

    public static function dataSurveyChart($teachers, $level)
    {
        // dd( $teachers, $level);

        $datachart =[];

        $intervals = collect([]);
        foreach($level->intervals as $interval)
        {
            $intervals->push( (object)[
                'colour' => $interval->colour,
                'label' => $interval->value,
                'number' => $teachers->where('score_level', $interval->value)->count(),
            ]);
        }

        $total = $intervals->sum('number');

        foreach($intervals as $interval)
        {
            if( $total > 0 ){
                $interval->percent = number_format( ($interval->number/$total)*100, 0);
            }else{
                $interval->percent = 0;
            }
        }
        return $intervals;
    }

    // Report #3
    public static function reportSurveyAvg($evaluation_id, $school_code )
    {
        $evaluation = Evaluation::find($evaluation_id);
        $survey = $evaluation->survey;
        $scale = (object) $survey->scale->toArray();
        $level = (object) $survey->level->toArray();

        $data = collect( Reply::listTeachersReplies($evaluation_id, $school_code) );

        foreach($survey->items as $item)
        {

            $teacherReplies = $data->where('item_id', $item->id)->groupBy('teacher_code');
            $item->replies = collect([]);
            $levelTeachers = collect([]);

            foreach($teacherReplies as $teacherReply)
            {
                $avg = $teacherReply->avg('value');
                $levelTeachers->push( (object) [
                    'avg' => $avg,
                    'level' => Level::evalInIntervals($level->intervals, $avg),
                ]);
            }

            $item->intervals = json_decode(json_encode($level->intervals));
            foreach($item->intervals as $interval)
            {
                $results = $levelTeachers->where('level', $interval->value);
                if( $results->count() > 0){
                    $interval->num_teachers = $results->count();
                    $interval->reply_avg = $levelTeachers->where('level', $interval->value)->first()->avg;
                }else{
                    $interval->num_teachers = 0;
                    $interval->reply_avg = null;
                }
            }

        }

        $number_teachers_evaluated = 15;
        $institutional_score = $data->avg('value');
        $institutional_score_level = Level::evalInIntervals($level->intervals, $institutional_score);

        $data = new \Stdclass;
        $data->items = $survey->items;
        $data->number_teachers_evaluated = $number_teachers_evaluated;
        $data->institutional_score = $institutional_score;
        $data->institutional_score_level = $institutional_score_level;
        return $data;
    }

    // Report #4
    public static function reportSurveySingle($evaluation_id, $school_code, $teacher_code )
    {
        $evaluation = Evaluation::find($evaluation_id);
        $survey = $evaluation->survey;

        $teacher = RepoUnprg::oneTeacherWithCourses( $school_code, $evaluation->period_id, $teacher_code );

        $items = null;
        if( $teacher !== null){
            $items = static::teacherReplies( $evaluation->id, $school_code, $teacher_code, $teacher->courses );
        }

        $data = new \Stdclass;
        $data->teacher = $teacher;
        $data->items = $items;
        $data->scale = $survey->scale;

        return $data;
    }

    // For Report #4
    public static function teacherReplies( $evaluation_id, $school_code, $teacher_code, $courses )
    {

        $survey = Evaluation::find($evaluation_id)->survey;
        $scale = (object) $survey->scale->toArray();
        $level = (object ) $survey->level->toArray();

        $data = collect( Reply::listTeacherReplies($evaluation_id, $school_code, $teacher_code) );

        $items =  collect([]);
        foreach($survey->items as $item)
        {
            $myItem = new \Stdclass;
            $myItem->id = $item->id;
            $myItem->name = $item->name;
            $myItem->courses = collect([]);

            foreach($courses as $course)
            {
                $myCourse = new \Stdclass;
                $myCourse->code = $course->code;
                $myCourse->group = $course->group;
                $myCourse->name = $course->name;

                $replies = $data->where('item_id', $item->id)
                    ->where('course_key', $course->key);

                $myCourse->avg = (int) $replies->avg('value');

                $myCourse->scale = json_decode(json_encode($scale));

                foreach($myCourse->scale->options as $option)
                {
                    $option->check = $option->value == $myCourse->avg;
                }
                
                $myItem->courses->push($myCourse);
            }

            $items->push($myItem);
        }

        // objeter promedio de por curso

        foreach($courses as $course)
        {
            $replies = $data->where('course_key', $course->key);

            $course->avg = $replies->avg('value');
            $course->level = Level::evalInIntervals( $level->intervals, $course->avg );
            $course->surveyed_students = $replies->groupBy('student_code')->count();
            $course->enrolled_students  = $course->number_students;
        }

        return $items;
    }
    
    private static function teacherMath(&$teacher, $eIntervals)
    {
        $tInidicators = collect([]);

        foreach($teacher->courses as $course)
        {
            foreach($course->indicators as $indicator)
            {
                $tInidicators->push($indicator);
            }
        }

        // agrupar calificaciones por inidicadores
        $groups = $tInidicators->groupBy('indicator_id');

        // calcular el promedio de cada grupo de indicador
        $teacher->indicators = [];
        foreach($groups as $indicator_id => $group)
        {
            $teacher->indicators[$indicator_id] = (object) [ 
                'id' => $indicator_id,
                'avg' => $group->avg('value') 
            ];
        }

        $teacher->indicators = collect($teacher->indicators);
        $teacher->total = $teacher->indicators->sum('avg');
        $teacher->level = Level::evalInIntervals($eIntervals, $teacher->total);
    }

    public static function arrayTypes()
    {
        return [
            [ 
                'id' => self::TYPE_INDICATORS, 
                'name' => 'INDICATORS',
                'descrip' => 'EVALUACIÓN DEL DESEMPEÑO DOCENTE POR INDICADORES',
            ],
            [ 
                'id' => self::TYPE_SURVEY, 
                'name' => 'SURVEY',
                'descrip' => 'RESULTADOS PROMEDIO INDIVIDUAL DE LA OPINION DE LOS ESTUDIANTES',
            ],
            [ 
                'id' => self::TYPE_SURVEY_AVG, 
                'name' => 'SURVEY_AVG',
                'descrip' => 'RESULTADOS PROMEDIO DEL DESEMPEÑO DOCENTE SEGÚN OPINION DEL ESTUDIANTE',
            ],
            [ 
                'id' => self::TYPE_SURVEY_SINGLE, 
                'name' => 'SURVEY_SINGLE',
                'descrip' => 'EVALUACIÓN DEL DESEMPEÑO DOCENTE POR CURSO, SEGÚN LA OPINIÓN DE LOS ESTUDIANTES',
            ],
        ];
    }
}
