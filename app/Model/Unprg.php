<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class Unprg extends Model
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

    public static function viewData( $period_id ) //not include student info such as enrollments
    {
        $query = "select distinct
        f.code as faculty_code,
        f.name as faculty_name,
        e.code as school_code,
        e.name as school_name,
        d.code as teacher_code,
        d.fullname as teacher_fullname,
        c.code as course_code,
        c.name as course_name,
        grupo_code as course_group
        from matricula as m
        left join curso as c on c.code = m.curso_code
        left join docente as d on d.code = m.docente_code
        left join docente_escuela as de on d.code = de.docente_code
        left join escuela as e on e.code = de.escuela_code
        left join facultad as f on f.code = e.facultad_code
        where m.semestre_code = $period_id";
        return static::data( $query );
    }

    public static function schoolAll()
    {
        $query = "select 
        code, 
        name 
        from escuela";
        return static::data( $query );
    }

    public static function facultyAll()
    {
        $query = "select 
        code, 
        name 
        from facultad";
        return static::data( $query );
    }

    public static function schoolsByFacultyCode( $code )
    {
        $query = "select 
        code, 
        name 
        from escuela
        where facultad_code=$code";
        return static::data( $query );
    }

    // Docentes de una escuela en el periodo actual
    public static function teachersBySchoolCode( $period_code, $school_code)
    {
        $query = "select 
        d.code, 
        d.fullname 
        from docente as d
        left join docente_escuela as de on de.docente_code = d.code
        where de.escuela_code=$school_code";
        return static::data( $query );
    }

    public static function teachersByFacultyCode( $code )
    {
        $query = "select 
        d.code, 
        d.fullname 
        from docente as d
        left join docente_escuela as de on de.docente_code = d.code
        left join escuela as e on e.code = de.escuela_code
        left join facultad as f on f.code = e.facultad_code
        where f.code=$code";
        return static::data( $query );
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

    //use
    public static function teacherByCod( $code )
    {
        $query = "select code, fullname from docente where code = $code";
        return static::data( $query, 'first' );
    }

    public static function currentCoursesByTeacherCode( $period_code, $teacher_code )
    {
        $query = "select distinct
        c.code, c.name, m.grupo_code as group_code
        from matricula as m
        left join curso as c on c.code = m.curso_code
        where m.semestre_code = $period_code
        and m.docente_code = $teacher_code";
        
        return static::data( $query);
    }

    public static function periods()
    {
        $query = "select code as id, name, date_start, date_end from semestre";
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

    public static function teachersByCodPeriod( $period_code )
    {
        $query = "
        select distinct docente_code as code, semestre_code from matricula
        where semestre_code = 1
        ";
        return static::data( $query);
    }

    

}
