<?php
use App\Model\Config\Param;
use App\Model\Exclusion;
use App\Model\Config;

function fres()
{
    $response = (Object)[
        'status' => false, //true, false
        'message' => '',
        'errors' => collect([]),
        'data' => null,
    ];
    return $response;
}

function _const( $key )
{
    $const = Config::where('key', $key)->first();
    return $const!==null? $const->value: null;
}


function excludeTeachers( $period_id, $school_code, &$data, $filter_key )
{
    $exclusions = \DB::table('exclusions as ex')
        ->leftJoin('evaluations as ev', 'ev.id', '=', 'ex.evaluation_id')
        ->select('ev.period_id', 'ex.*')
        ->where('ex.type', Exclusion::TYPE_TEACHER)
        ->where('ev.period_id', $period_id)
        ->where('ex.school_code', $school_code)
        ->get();

    $data = $data->whereNotIn($filter_key, $exclusions->pluck('teacher_code')->toArray() );  
    return $data;
}

function excludeCourses( $period_id, $school_code, &$data, $filter_key)
{
    $exclusions = \DB::table('exclusions as ex')
        ->leftJoin('evaluations as ev', 'ev.id', '=', 'ex.evaluation_id')
        ->select('ev.period_id', 'ex.*')
        ->where('ex.type', Exclusion::TYPE_COURSE)
        ->where('ev.period_id', $period_id)
        ->where('ex.school_code', $school_code)
        ->get();

    $data = $data->whereNotIn('course_key', $exclusions->pluck('course_key')->toArray() );
    return $data;
}