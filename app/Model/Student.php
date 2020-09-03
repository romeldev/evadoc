<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\StdEvaluationResource;

class Student extends Model
{

    public static function evaluation()
    {
        // Evaluation::orderBy('period_id', 'desc')->first()
        $evaluation = new StdEvaluationResource( Evaluation::first() );
        return $evaluation;
    }

    public static function info( $period_code, $student_code )
    {
        $evaluation = static::evaluation();

        $student = RepoUnprg::oneStudent(  $student_code, $period_code );

        if ( $student == null) return null;

        //TODO: check replied courses
        foreach($student->courses as $course)
        {
            $reply = Reply::where([
                'evaluation_id' => $evaluation->id,
                'school_code' => $student->school_code,
                'student_code' => $student->code,
                'teacher_code' => $course->teacher_code,
                'course_key' => $course->key,
            ])->first();

            $course->replied = $reply!==null;
        }
        return $student;
    }

    public static function saveReply( $request )
    {
        $reply = Reply::create([
            'evaluation_id' => $request->evaluation_id,
            'school_code' => $request->school_code,
            'student_code' => $request->student_code,
            'teacher_code' => $request->teacher_code,
            'course_key' => $request->course_code.'-'.$request->course_group,
            // 'course_group' => $request->course_group,
            'status' => 1,
        ]);

        $surveyItems = [];
        foreach($request->survey_items as $item)
        {
            $item = (object) $item;
            $surveyItems[] = [
                'reply_id' => $reply->id,
                'item_id' => $item->id,
                'value' => $item->value,
            ];
        }

        ReplyItem::insert($surveyItems);

        return true;
    }

    public static function crypt( $text )
    {
        return $text;
    }

    public static function decrypt( $text )
    {
        return $text;
    }
}
