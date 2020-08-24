<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Unprg;
use App\Model\Evaluation;
use App\Model\Reply;

class TestController extends Controller
{

    public function index(Request $request) 
    {
        $evaluation = Evaluation::current();

        $enrollments = Unprg::allEnrollments();

        $items = $evaluation->items();

        $data = [];
        
        foreach($enrollments as $enrollment)
        {
            foreach($items as $item)
            {
                $data[] = [
                    'student_cod' => $enrollment->student_cod,
                    'course_cod' => $enrollment->course_cod,
                    'teacher_cod' => $enrollment->teacher_cod,
                    'evaluation_id' => $evaluation->id,
                    'survey_id' => $item->survey_id,
                    'item_id' => $item->id,
                    'item_value' => rand(1, 5),
                ];
            }
        }    
        
        Reply::query()->delete();

        $parts = array_chunk( $data, 1000);

        foreach( $parts as $key => $part){
            $x = Reply::insert( $part );
            echo ($key+1)." part inserted!<br>";
        }
        dd('END');
    }


}
