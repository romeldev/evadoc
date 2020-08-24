<?php

use Illuminate\Database\Seeder;
use App\Model\Evaluation;
use App\Model\RepoUnprg;
use App\Model\Reply;
use App\Model\ReplyItem;
use App\Model\Scale;

class ReplySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ReplyItem::query()->delete();
        Reply::query()->delete();

        foreach(Evaluation::all() as $evaluation)
        {
            $survey = $evaluation->survey;
            $scale_options = collect($survey->scale->options)->pluck('value');
            // $scale_options = [1,1,1,1,2,2,2,3,3,4,4,5];
            // dd($scale_options);

            $studentsData = RepoUnprg::coursesStudentPlain($evaluation->period_id);

            foreach($studentsData as $row)
            {
                $reply = Reply::create([
                    'evaluation_id' => $evaluation->id,
                    'school_code' => $row->school_code,
                    'student_code' => $row->student_code,
                    'teacher_code' => $row->teacher_code,
                    'course_key' => $row->course_code.'-'.$row->course_group,
                    // 'course_code' => $row->course_code,
                    // 'course_group' => $row->course_group,
                    'status' => 1,
                ]);

                $replyItems = [];
                foreach($survey->items as $item)
                {
                    $replyItems[] = [
                        'reply_id' => $reply->id,
                        'item_id' => $item->id,
                        'value' => $scale_options[rand(0, count($scale_options)-1 )],
                        'created_at' => \Carbon\Carbon::now(),
                    ];

                }
                $ri = ReplyItem::insert($replyItems);
            }
        }
    }

}
