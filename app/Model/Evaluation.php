<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Evaluation extends Model
{
    use SoftDeletes;
    
    protected $fillable = [ 'title', 'descrip', 'period_id', 'period_text', 'date_start', 'date_end', 'survey_id', 'level_id' ];

    CONST STATUS_STOPED  = 1;
    CONST STATUS_STARTED = 2;
    CONST STATUS_FINISHED= 3;

    public static function currentEvaluation()
    {
        $now = \Carbon\Carbon::now();
        $evaluation = Evaluation::where('date_start', '<=', $now)
            ->where('date_end', '>=', $now)
            ->where('status', static::STATUS_STARTED )->first();
        return $evaluation;
    }

    public function scopeSearch($query, $search )
    {
        if( trim($search)!=''){
            return $query->where('title', 'like', "%$search%")
            ;
        }
    }

    public function indicators()
    {
        return $this->hasMany(Indicator::class, 'evaluation_id');
    }

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function items()
    {
        return Item::from('items as i')
            ->leftJoin('indicators as ic', 'ic.survey_id', 'i.survey_id')
            ->where('ic.evaluation_id', $this->id)
            ->where('ic.type', Indicator::TYPE_SURVEY)
            ->select('i.*')
            ->distinct()
            ->get();
    }


    public function saveIndicators( $array=[] )
    {
        if( count($array) == 0) return 0;

        $ids =  [];
        foreach($array as $row){
            $id = (int)$row['id'];
            if( $id>0 ) $ids[] = $id; 
        }

        if( count($ids)>0 )
        {
            $deletes = $this->indicators()->whereNotIn('id', $ids)->delete();
        }

        $count = 0;
        foreach($array as $key => $row)
        {
            $id = (int)$row['id'];

            $indicator = Indicator::find($id);
            if( $indicator==null) $indicator = new Indicator;

            $indicator->order = $key+1;
            $indicator->name = $row['name'];
            $indicator->editable = $row['editable'];
            $indicator->type_id = $row['type_id'];
            $indicator->weight = $row['weight'];
            $indicator->evaluation_id = $this->id;

            if( $indicator->save() ) $count++;

            if( isset($row['items'][0]) ){
                $attach = $indicator->items()->attach($row['items']);
            }
        }

        return $count;
    }

    public function saveIndicators2( $indicators=[] )
    {
        $data = [];

        $delete = $this->indicators()->delete();

        foreach($indicators as $key => $indicator){
            $indicator = (object) $indicator;

            $realIndicator = Indicator::create([
                'order' => $key+1,
                'name' => $indicator->name,
                'editable' => $indicator->editable,
                'type' => $indicator->type,
                'weight' => $indicator->weight,
                'survey_id' => $indicator->survey_id,
                'evaluation_id' => $this->id,
            ]);

            if( isset($indicator->items[0]) ){
                $attach = $realIndicator->items()->attach($indicator->items);
            }
        }

        return true;
    }

    public static function current()
    {
        return Evaluation::orderBy('period_id', 'desc')->first();
    }
}
