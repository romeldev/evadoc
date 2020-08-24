<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class RepoUnprg extends Model
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

    # V1
    public static function viewTeachers( $school_code, $period_code, $cache=true )
    {
        // if($cache){
        //     $data = cache()->rememberForever( _const('CACHE_TEACHERS'), function() use($school_code, $period_code) {
        //         return static::viewTeachers( $school_code, $period_code, false);
        //     });
        //     return $data;
        // }

        $query = "
        select 
        d.code as teacher_code, d.fullname as teacher_fullname,
        c.code as course_code, c.name as course_name, m.grupo_code as course_group,
        count(*) as number_students
        from matricula as m
        left join curso as c on c.code = m.curso_code
        left join docente as d on d.code = m.docente_code
        left join docente_escuela as de on de.docente_code = d.code
        where m.semestre_code = $period_code
        and de.escuela_code = $school_code
        group by teacher_code, teacher_fullname, course_code, course_name, course_group
        ";

        $data = collect( static::data( $query) );
        
        foreach($data as $row)
        {
            $row->course_key = $row->course_code.'-'.$row->course_group;
        }
        return $data;
    }

    # V2
    public static function viewSchools( $cache=true )
    {
        if($cache){
            $data = cache()->rememberForever( _const('CACHE_SCHOOLS'), function() {
                return static::viewSchools(false);
            });
            return $data;
        }

        $query = "
            select
            f.code as faculty_code, f.name as faculty_name,
            e.code as school_code, e.name as school_name
            from escuela as e
            left join facultad as f on f.code = e.facultad_code
        ";
        return collect( static::data( $query) );
    }

    # v3
    public static function viewStudent( $student_code, $period_code, $filtered=false )
    {
        $query = "
        select
        a.code as student_code, a.fullname as student_fullname,
        d.code as teacher_code, d.fullname as teacher_fullname,
        c.code as course_code, c.name as course_name, m.grupo_code as course_group,
        ae.escuela_code as school_code
        from matricula as m
        left join alumno as a on a.code = m.alumno_code
        left join curso as c on c.code = m.curso_code
        left join docente as d on d.code = m.docente_code
        left join alumno_escuela as ae on ae.alumno_code = m.alumno_code
        where m.semestre_code = $period_code
        and m.alumno_code = $student_code";

        $data = collect( static::data( $query) );

        foreach($data as $row) $row->course_key = $row->course_code.'-'.$row->course_group;

        if( $filtered ) {
            $school_code = $data->first() !=null? $data->first()->school_code: 0;
            excludeCourses( $period_code, $school_code, $data, 'course_key');
        }

        return $data;
    }

    # F2 <- V1
    public static function viewTeachersF( $school_code, $period_code )
    {
        $data = static::viewTeachers( $school_code, $period_code );
        excludeTeachers( $period_code, $school_code, $data, 'teacher_code');
        excludeCourses( $period_code, $school_code, $data, 'course_key');
        return $data;
    }

    # F3 <- F2
    public static function vTreeTeachers( $school_code, $period_code, $filtered=true )
    {
        if( $filtered ){
            $data = static::viewTeachersF( $school_code, $period_code );
        }else{
            $data = static::viewTeachers( $school_code, $period_code );
        }

        $teachers = [];
        foreach($data as $row)
        {
            $teachers[$row->teacher_code]['code'] = $row->teacher_code;
            $teachers[$row->teacher_code]['fullname'] = $row->teacher_fullname;
            $teachers[$row->teacher_code]['courses'][$row->course_key]['key'] = $row->course_key;
            $teachers[$row->teacher_code]['courses'][$row->course_key]['code'] = $row->course_code;
            $teachers[$row->teacher_code]['courses'][$row->course_key]['group'] = $row->course_group;
            $teachers[$row->teacher_code]['courses'][$row->course_key]['name'] = $row->course_name;
            $teachers[$row->teacher_code]['courses'][$row->course_key]['number_students'] = $row->number_students;
        }

        foreach($teachers as $keyt => $teacher)
        {
            $teacher = (object) $teacher;

            foreach($teacher->courses as $keyc => $course)
            {
                $teacher->courses[$keyc] = (object) $course;
            }

            $teacher->courses = collect( array_values($teacher->courses) );


            $teachers[$keyt] = (object) $teacher;
        }

        $teachers = collect( array_values($teachers) );
        return $teachers;
    }

    # F4 <- F2
    public static function allTeachers( $school_code, $period_code, $filtered=true )
    {
        $treeTeachers = static::vTreeTeachers( $school_code, $period_code, $filtered );

        $teachers = $treeTeachers->map(function($item, $key){
            // return (object) collect($item)->only(['code', 'fullname'])->toArray();
            return (object) ['code' => $item->code, 'fullname' => $item->fullname];
        });
        return $teachers;
    }

    # F5 <- F2
    public static function oneTeacherWithCourses( $school_code, $period_code, $teacher_code )
    {
        $teacher = static::vTreeTeachers( $school_code, $period_code )
            ->where('code', $teacher_code)->first();
        return $teacher;
    }

    # S1 <- V1
    public static function vTreeSchools()
    {
        $data = static::viewSchools();
        $schools = [];
        foreach($data as $row)
        {
            $schools[$row->school_code]['code'] = $row->school_code;
            $schools[$row->school_code]['name'] = $row->school_name;
            $schools[$row->school_code]['faculty'] = (object) [
                'code' => $row->faculty_code,
                'name' => $row->faculty_name,
            ];
        }

        foreach($schools as $key => $school) $schools[$key] = (object)$school;
        $schools = collect( array_values($schools) );
        return $schools;
    }

    # S2 <- S1
    public static function oneSchool( $school_code )
    {
        $school = static::vTreeSchools()->where('code', $school_code)->first();
        return $school;
    }


    public static function oneStudent( $student_code, $period_code )
    {
        $vData = static::viewStudent( $student_code, $period_code, true );

        $student = [];

        foreach($vData as $row)
        {
            $student['code'] = $row->student_code;
            $student['fullname'] = $row->student_fullname;
            $student['school_code'] = $row->school_code;
            $student['courses'][$row->course_key]['key'] = $row->course_key;
            $student['courses'][$row->course_key]['code'] = $row->course_code;
            $student['courses'][$row->course_key]['name'] = $row->course_name;
            $student['courses'][$row->course_key]['group'] = $row->course_group;
            $student['courses'][$row->course_key]['teacher_code'] = $row->teacher_code;
            $student['courses'][$row->course_key]['teacher_fullname'] = $row->teacher_fullname;
        }

        if( count($student) == 0 ) return null;

        $student = (object) $student;
        $student->courses = collect(array_values($student->courses));
        foreach($student->courses as $key => $course) $student->courses[$key] = (object) $course;
        return $student;
    }


    // -----------------
    // 1. Listar profesores (con todos sus cursos) de una escuela por código de semestre.
    public static function listTeachersWithCourses( $period_code, $school_code )
    {
        $query = "
        select distinct
        d.code as teacher_code,
        d.fullname as teacher_fullname,
        c.code as course_code,
        c.name as course_name,
        m.grupo_code as course_group
        from matricula as m
        left join docente as d on d.code = m.docente_code
        left join curso as c on c.code = m.curso_code
        left join docente_escuela as de on de.docente_code = d.code
        where m.semestre_code = $period_code
        and de.escuela_code = $school_code
        order by d.fullname";
        return static::data( $query);
    }

    // 2. Listar cursos de un estudiante por semestre
    public static function listStudentCourses( $period_code, $studente_code )
    {
        $query = "
        select
        a.code as student_code,
        a.fullname as student_fullname,
        d.code as teacher_code,
        d.fullname as teacher_fullname,
        c.code as course_code,
        c.name as course_name,
        m.grupo_code as course_group
        from matricula as m
        left join alumno as a on a.code = m.alumno_code
        left join curso as c on c.code = m.curso_code
        left join docente as d on d.code = m.docente_code
        where m.semestre_code = $period_code
        and m.alumno_code = $studente_code";
        return static::data( $query);
    }

    // 3. Matriculas por cada curso de un profesor
    public static function listTeacherCoursesWithEnrolles( $period_code, $school_code, $teacher_code )
    {
        $query = "
        select
        de.escuela_code,
        d.code as teacher_code, d.fullname as teacher_fullname,
        c.code as course_code, c.name as course_name, m.grupo_code as course_group,
        count(*) as enrolls
        from matricula as m
        left join docente_escuela as de on de.docente_code = m.docente_code
        left join docente as d on d.code = m.docente_code
        left join curso as c on c.code = m.curso_code
        where m.semestre_code = $period_code
        and de.escuela_code = $school_code
        and m.docente_code =  $teacher_code
        group by d.code, d.fullname, c.code, c.name, m.grupo_code
        ";
        return static::data( $query);
    }

    // 3. Listar todos los docentes por escuela en un determinado semestre
    public static function listTeachers( $period_code, $school_code )
    {
        $query = "
            select distinct
            d.code as code, d.fullname as fullname
            from matricula as m
            left join docente_escuela as de on de.docente_code = m.docente_code
            left join docente as d on d.code = m.docente_code
            where m.semestre_code = $period_code
            and de.escuela_code = $school_code
        ";

        $data = collect( static::data( $query) );
        return $data;
    }
    // ----------------------------------- DATA FORMATED ----------------------------------------

    

    // 1.1
    public static function treeTeachersWithCourses( $period_code, $school_code )
    {
        $data = self::listTeachersWithCourses( $period_code, $school_code );

        $teachers = [];
        foreach($data as $row)
        {
            $teachers[$row->teacher_code]['code'] = $row->teacher_code; 
            $teachers[$row->teacher_code]['fullname'] = $row->teacher_fullname; 
            $teachers[$row->teacher_code]['courses'][$row->course_code.''.$row->course_group]['code'] = $row->course_code; 
            $teachers[$row->teacher_code]['courses'][$row->course_code.''.$row->course_group]['name'] = $row->course_name; 
            $teachers[$row->teacher_code]['courses'][$row->course_code.''.$row->course_group]['group'] = $row->course_group; 
        }

        $teachers = collect( array_values($teachers) );

        foreach($teachers as $key => $teacher)
        {
            $teacher = (object) $teacher;
            foreach($teacher->courses as $keyc => $course){
                $teacher->courses[$keyc] = (object) $course;
            }
            $teacher->courses = collect( array_values($teacher->courses) );
            $teachers[$key] = $teacher;
        }

        return $teachers;
    }

    // *******************************Only seeder purpose************************************
    // 1. coursesTeacherPlain()
    public static function coursesTeacherPlain( $semestre_code )
    {
        $query = "
        select distinct
        f.code as faculty_code, f.name as faculty_name,
        e.code as school_code, e.name as school_name,
        d.code as teacher_code, d.fullname as teacher_fullname,
        c.code as course_code, c.name as course_name, grupo_code as course_group
        from matricula as m
        left join curso as c on c.code = m.curso_code
        left join docente as d on d.code = m.docente_code
        left join docente_escuela as de on d.code = de.docente_code
        left join escuela as e on e.code = de.escuela_code
        left join facultad as f on f.code = e.facultad_code
        where m.semestre_code = $semestre_code
        order by teacher_code";

        return static::data( $query );
    }

    // 2. coursesStudentPlain()
    public static function coursesStudentPlain( $semestre_code )
    {
        $query = "
        select
        f.code as faculty_code, f.name as faculty_name,
        e.code as school_code, e.name as school_name,
        a.code as student_code, a.fullname as student_fullname,
        d.code as teacher_code, d.fullname as teacher_fullname,
        c.code as course_code, c.name as course_name, grupo_code as course_group
        from matricula as m
        left join curso as c on c.code = m.curso_code
        left join alumno as a on a.code = m.alumno_code
        left join docente as d on d.code = m.docente_code
        left join alumno_escuela as ae on a.code = ae.alumno_code
        left join escuela as e on e.code = ae.escuela_code
        left join facultad as f on f.code = e.facultad_code
        where m.semestre_code = 1
        order by m.alumno_code
        ";

        return static::data( $query );
    }

    public static function coursesTeacherTree( $semestre_code )
    {
        $data = static::coursesTeacherPlain( $semestre_code );

        $faculties = [];

        foreach($data as $row)
        {
            // faculties
            $faculties[$row->faculty_code]['code'] = $row->faculty_code;
            $faculties[$row->faculty_code]['name'] = $row->faculty_name;

            // schools
            $faculties[$row->faculty_code]['schools'][$row->school_code]['code'] = $row->school_code;
            $faculties[$row->faculty_code]['schools'][$row->school_code]['name'] = $row->school_name;

            // teachers
            $faculties[$row->faculty_code]['schools'][$row->school_code]['teachers'][$row->teacher_code]['code'] = $row->teacher_code;
            $faculties[$row->faculty_code]['schools'][$row->school_code]['teachers'][$row->teacher_code]['fullname'] = $row->teacher_fullname;

            // courses
            $faculties[$row->faculty_code]['schools'][$row->school_code]['teachers'][$row->teacher_code]['courses'][$row->course_code]['code'] = $row->course_code;
            $faculties[$row->faculty_code]['schools'][$row->school_code]['teachers'][$row->teacher_code]['courses'][$row->course_code]['name'] = $row->course_name;
            $faculties[$row->faculty_code]['schools'][$row->school_code]['teachers'][$row->teacher_code]['courses'][$row->course_code]['group'] = $row->course_group;
        }

        // dd($faculties);
        return $faculties;
    }

    public static function coursesStudentTree( $semestre_code )
    {
        $data = static::coursesStudentPlain($semestre_code);

        $faculties = [];

        foreach($data as $row)
        {
            // faculties
            $faculties[$row->faculty_code]['code'] = $row->faculty_code;
            $faculties[$row->faculty_code]['name'] = $row->faculty_name;

            // schools
            $faculties[$row->faculty_code]['schools'][$row->school_code]['code'] = $row->school_code;
            $faculties[$row->faculty_code]['schools'][$row->school_code]['name'] = $row->school_name;

            // students
            $faculties[$row->faculty_code]['schools'][$row->school_code]['students'][$row->student_code]['code'] = $row->student_code;
            $faculties[$row->faculty_code]['schools'][$row->school_code]['students'][$row->student_code]['fullname'] = $row->student_fullname;

            // courses
            $faculties[$row->faculty_code]['schools'][$row->school_code]['students'][$row->student_code]['courses'][$row->course_code.'-'.$row->course_group]['code'] = $row->course_code;
            $faculties[$row->faculty_code]['schools'][$row->school_code]['students'][$row->student_code]['courses'][$row->course_code.'-'.$row->course_group]['name'] = $row->course_name;
            $faculties[$row->faculty_code]['schools'][$row->school_code]['students'][$row->student_code]['courses'][$row->course_code.'-'.$row->course_group]['group'] = $row->course_group;

            $faculties[$row->faculty_code]['schools'][$row->school_code]['students'][$row->student_code]['courses'][$row->course_code.'-'.$row->course_group]['teacher_code'] = $row->teacher_code;
            $faculties[$row->faculty_code]['schools'][$row->school_code]['students'][$row->student_code]['courses'][$row->course_code.'-'.$row->course_group]['teacher_fullname'] = $row->teacher_fullname;
        }

        return $faculties;
    }
    
}
