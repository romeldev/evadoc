<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Indicator extends Model
{
    use SoftDeletes;

    CONST TYPE_MANUAL = 1;
    CONST TYPE_SURVEY = 2;

    protected $fillable = [ 'order', 'name', 'editable', 'type_id', 'weight', 'evaluation_id' ];

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class, 'evaluation_id')->withDefault();
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'indicator_items');
    }

    public function survey()
    {
        //return a object null
        return $this->belongsTo(Survey::class)->withDefault();
    }

    public function getTypeNameAttribute()
    {
        $types = self::arrayTypes();
        if( isset($types[$this->type]) ){
            return $types[$this->type];
        }else{
            return 'Unknown';
        }
    }

    public static function arrayTypes()
    {
        $data[1] = 'Manual';
        $data[2] = 'Survey';
        return $data;
    }
}
