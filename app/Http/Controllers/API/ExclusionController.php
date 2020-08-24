<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Model\Exclusion;

class ExclusionController extends Controller
{
    public function teachers(Request $request)
    {
        $request->validate([
            'evaluation_id' => 'required',
            'school_code' => 'required',
        ]);

        return Exclusion::teachers( $request->school_code, $request->evaluation_id );
    }

    public function saveTeachersCoursesExclusions(Request $request)
    {
        $request->validate([
            'evaluation_id' => 'required',
            'school_code' => 'required',
        ]);

        DB::beginTransaction();
        try{

            $exclusions = [];
            foreach($request->teachers as $teacher)
            {
                $teacher = (object) $teacher;
                if( $teacher->status ){
                    $exclusions[] = [
                        'type' => Exclusion::TYPE_TEACHER,
                        'evaluation_id' => $request->evaluation_id,
                        'school_code' => $request->school_code,
                        'teacher_code' => $teacher->code,
                        'course_code' => null,
                        'course_group' => null,
                        'course_key' => null,
                    ];
                }
                foreach($teacher->courses as $course)
                {
                    $course = (object) $course;
                    if( $course->status ){
                        $exclusions[] = [
                            'type' => Exclusion::TYPE_COURSE,
                            'evaluation_id' => $request->evaluation_id,
                            'school_code' => $request->school_code,
                            'teacher_code' => $teacher->code,
                            'course_code' => $course->code,
                            'course_group' => $course->group,
                            'course_key' => $course->key,
                        ];
                    }
                }
            }

            Exclusion::where('evaluation_id', $request->evaluation_id)
                ->where('school_code', $request->school_code )->delete();

            $insert = Exclusion::insert($exclusions);

            DB::commit();
            return response()->json($insert);
        }catch (\Exception $e) {
            DB::rollback();
            return response()->json( $e->getMessage(), 500 );
        }

        return $request->all();
    }
}
