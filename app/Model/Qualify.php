<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Qualify extends Model
{
    
    protected $fillable = [ 
        'evaluation_id', 
        'teacher_code', 
        'course_code', 
        'course_key', 
        'school_code',
        // 'course_group', 
    ];

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class)->withDefault();
    }

    public function qualifyIndicators()
    {
        return $this->hasMany(QualifyIndicator::class, 'qualify_id');
    }

    public function getFillIndicatorsAttribute()
    {

        $indicators = $this->evaluation->indicators()
            ->select('id', 'name', 'editable', 'type_id')
            ->get();

        $qualifyIndicators = $this->qualifyIndicators; 
        // dd($qualifyIndicators);

        foreach($indicators as $indicator)
        {
            $indicator->value = 0;
            foreach($qualifyIndicators as $qIndicator)
            {
                if( $indicator->id == $qIndicator->indicator_id){
                    $indicator->value = $qIndicator->value;
                    break;
                }
            }
        }
        return $indicators;
    }

    // public static function courseQualify($evaluation_id, $teacher_code, $course_code, $course_group, $school_code)
    public static function courseQualify($evaluation_id, $teacher_code, $course_key, $school_code)
    {

        $evaluation = Evaluation::find($evaluation_id);

        $indicators = $evaluation->indicators()
            ->select('id', 'name', 'editable', 'type_id')
            ->get();

        $qualify = Qualify::where([
            'evaluation_id' => $evaluation_id,
            'teacher_code' => $teacher_code,
            'course_key' => $course_key,
            'school_code' => $school_code,
            ])
            ->first();

        if( $qualify == null)
        {
            $qualify = Qualify::create([
                'evaluation_id' => $evaluation_id,
                'teacher_code' => $teacher_code,
                'course_key' => $course_key,
                'school_code' => $school_code,
            ]);
        }

        foreach($indicators as $indicator)
        {
            $indicator->value = 0;
            foreach($qualify->qualifyIndicators as $qIndicator)
            {
                if( $indicator->id == $qIndicator->indicator_id){
                    $indicator->value = $qIndicator->value;
                    break;
                }
            }
        }

        $qualify->indicators = $indicators;
        
        return $qualify;
    }

    public function saveIndicators($indicators=[])
    {
        $data = [];
        $now = \Carbon\Carbon::now();
        $total = 0;
        foreach($indicators as $indicator)
        {
            $data[] = [
                'qualify_id' => $this->id,
                'indicator_id' => $indicator['id'],
                'value' => $indicator['value'],
                'created_at' => $now,
                'updated_at' => $now,
            ];
            $total += $indicator['value']; 
        }
        
        $this->qualifyIndicators()->delete();
        QualifyIndicator::insert($data);

        return $total/count($indicators);
    }

}
