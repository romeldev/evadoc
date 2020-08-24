<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    protected $fillable = [
        'evaluation_id',
        'school_code',
        'student_code',
        'teacher_code', 
        'course_key', 
        // 'course_code',
        // 'course_group', 
        'status',
    ];

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }

    public static function listTeacherReplies( $evaluation_id, $school_code, $teacher_code )
    {
        $query = "
            select
            ri.item_id as item_id, ri.value,
            r.student_code, r.teacher_code, r.course_key
            from reply_items as ri
            left join replies as r on r.id = ri.reply_id
            where r.evaluation_id = $evaluation_id
            and r.school_code = $school_code
            and r.teacher_code = $teacher_code
        ";

        return \DB::select($query);
    }

    public static function listTeachersReplies( $evaluation_id, $school_code )
    {
        $query = "
        select
        ri.item_id as item_id, ri.value,
        r.student_code, teacher_code, course_key
        from reply_items as ri
        left join replies as r on r.id = ri.reply_id
        where r.evaluation_id = $evaluation_id
        and r.school_code = $school_code
        ";

        return \DB::select($query);
    }
}
