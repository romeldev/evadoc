<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class UnprgRepository extends Model
{
    CONST CONN = 'unprg';

    public static function data( $query, $option=null )
    {
        
        $data = DB::connection(static::CONN)->select($query);
        
        if( $option == 'first'){
            $data = isset($data[0])? (array)  $data[0]: null;
        }
        return $data;
    }

    public static function coursesByCodTeacher( $cod )
    {
        $query = "
        select
        c.cod, c.name
        from matricula as m
        left join curso as c on c.cod = m.cod_curso
        where m.cod_docente = $cod
        group by c.cod, c.name
        ";
        return static::data( $query );
    }

    public static function coursesByCodStudent( $cod )
    {
        $query = "
        select
        c.cod,
        c.name, 
        d.cod as teacher_cod, 
        d.fullname as teacher_fullname
        from matricula as m
        left join curso as c on c.cod = m.cod_curso
        left join docente as d on d.cod = m.cod_docente
        where m.cod_alumno = $cod
        ";
        return static::data( $query );
    }

    public static function studentAll()
    {
        $query = "select cod, fullname from alumno";
        return static::data( $query );
    }

    public static function courseAll()
    {
        $query = "select cod, name from curso";
        return static::data( $query );
    }

    public static function allEnrollments()
    {
        $query = "
        select 
        cod_alumno as student_cod,
        cod_docente as teacher_cod,
        cod_curso as course_cod
        from matricula
        ";
        return static::data( $query );
    }

    public static function allCoursesWithTeachers()
    {
        $query = "
        select
        c.cod, 
        c.name, 
        d.cod as teacher_cod,
        d.fullname as teacher_fullname
        from matricula as m
        left join curso as c on m.cod_curso = c.cod
        left join docente as d on m.cod_docente = d.cod
        group by c.cod,  c.name,  d.cod, d.fullname
        ";
        return static::data( $query );
    }

    public static function studentByCod( $cod )
    {
        $query = "select cod, fullname from alumno where cod = $cod";
        return static::data( $query, 'first' );
    }

    public static function teacherByCod( $cod )
    {
        $query = "select cod, fullname from docente where cod = $cod";
        return static::data( $query, 'first' );
    }

    public static function periods()
    {
        $query = "select cod as id, name, date_start, date_end from semestre";
        return static::data( $query);
    }

    public static function periods2()
    {
        $data = [
            [ 
                'id'=> 1, 
                'name'=> '2019-I', 
                'date_start' => '2019-03-01',
                'date_end' => '2019-07-20',
            ],
            [ 
                'id'=> 2, 
                'name'=> '2019-II', 
                'date_start' => '2019-08-01',
                'date_end' => '2019-12-20',
            ],
            [ 
                'id'=> 3, 
                'name'=> '2020-I', 
                'date_start' => '2020-03-01',
                'date_end' => '2020-07-20',
            ],
        ];

        return $data;
    }

    // only migration data porposes
    public static function enrollmentsByCodPeriod( $cod )
    {
        $query = "
        select *
        from matricula
        where semestre_code = $cod
        order by alumno_code
        ";
        return static::data( $query);
    }
}
