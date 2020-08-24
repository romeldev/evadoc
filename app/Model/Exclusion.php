<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\RepoUnprg;

class Exclusion extends Model
{
    public $table = 'exclusions';

    const TYPE_COURSE = 'C';
    const TYPE_TEACHER = 'T';
    const TYPE_SCHOOL = 'S';

    protected $fillable = [ 'type', 'evaluation_id', 'school_code', 'teacher_code', 'course_code', 'course_group', 'course_key' ];

    public static function teachers( $school_code, $evaluation_id )
    {
        $evaluation = Evaluation::find($evaluation_id);
        
        $teachers = RepoUnprg::vTreeTeachers( $school_code, $evaluation->period_id, false);


        $exclusions = Exclusion::where('evaluation_id', $evaluation_id)
        ->where('school_code', $school_code )->get();

        foreach($teachers as $teacher){

            $exTeacher = $exclusions->where('type', Exclusion::TYPE_TEACHER)
                ->where('evaluation_id', $evaluation_id)
                ->where('school_code', $school_code)
                ->where('teacher_code', $teacher->code)->first();

            $teacher->status = $exTeacher!==null;

            foreach($teacher->courses as $course)
            {
                $exCourse = $exclusions->where('type', Exclusion::TYPE_COURSE)
                ->where('evaluation_id', $evaluation_id)
                ->where('school_code', $school_code)
                ->where('teacher_code', $teacher->code)
                ->where('course_key', $course->key)->first();
                $course->status = $exCourse!==null;
            }
        }

        return $teachers;
    }

}
