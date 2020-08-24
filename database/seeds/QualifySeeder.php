<?php

use Illuminate\Database\Seeder;
use App\Model\Evaluation;
use App\Model\RepoUnprg;
use App\Model\Qualify;
use App\Model\QualifyIndicator;

class QualifySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        QualifyIndicator::query()->delete();
        Qualify::query()->delete();
        foreach(Evaluation::all() as $evaluation)
        {
            $indicators = $evaluation->indicators;
            $coursesData = RepoUnprg::coursesTeacherPlain( $evaluation->period_id  );

            foreach($coursesData as $courseData)
            {
                $qualify = Qualify::create([
                    'evaluation_id' => $evaluation->id,
                    'course_key' => $courseData->course_code.'-'.$courseData->course_group,
                    // 'course_code' => $courseData->course_code,
                    // 'course_group' => $courseData->course_group,
                    'teacher_code' => $courseData->teacher_code,
                    'school_code' => $courseData->school_code,
                    'faculty_code' => $courseData->faculty_code,
                    'avg' => 0,
                ]);

                $total = 0;
                $qIndicators = [];
                foreach($indicators as $indicator)
                {
                    $value = rand(0, (int)$indicator->weight);
                    $now = \Carbon\Carbon::now();
                    $qIndicators[] = [
                        'qualify_id' => $qualify->id,
                        'indicator_id' => $indicator->id,
                        'value' => $value,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                    $total += $value;
                }

                $qualify->avg = $total / $indicators->count();
                $qualify->save();

                $qi = QualifyIndicator::insert($qIndicators);
            }
        }
    }
}
