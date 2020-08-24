<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class RepoEvadoc extends Model
{
    CONST CONN = 'mysql';

    public static function data( $query, $option=null )
    {
        
        $data = DB::connection(static::CONN)->select($query);
        
        if( $option == 'first'){
            $data = isset($data[0])? (array)  $data[0]: null;
        }
        return $data;
    }

    // 1. obtener las calificaciones de todos los cursos por escuela y periodo
    public static function indicatorsWithQualifies( $evaluation_id, $school_code ) //not include student info such as enrollments
    {
        $query = "select
        qi.indicator_id,
        qi.value,
        q.evaluation_id,
        q.course_key,
        q.teacher_code,
        q.school_code
        from qualify_indicator as qi
        left join qualifies as q on q.id = qi.qualify_id
        where q.school_code = $school_code
        and evaluation_id = $evaluation_id";
        return static::data( $query );
    }
}
