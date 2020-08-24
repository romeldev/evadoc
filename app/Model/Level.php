<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Level extends Model
{
    protected $fillable = [ 'name', 'intervals' ];

    const GATE_CLOSED = 0; // []
    const GATE_OPEN = 1; // <>

    public static function generateColour()
    {
        $letters = '0123456789ABCDEF';
        $color = '#';
        for($i = 0; $i < 6; $i++) {
            $color .= substr($letters, rand(0, 15), 1);
        }
        return $color;
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }

    public function surveys()
    {
        return $this->hasMany(Survey::class);
    }

    public function scopeSearch($query, $search )
    {
        if( trim($search)!=''){
            return $query->where('name', 'like', "%$search%")
            ;
        }
    }

    public function getIntervalsAttribute( $value )
    {
        return json_decode($value);
    }
    
    public function setIntervalsAttribute( $value )
    {
        $this->attributes['intervals'] = json_encode($value);
    } 

    public static function evalInInterval($interval, $value)
    {
        $status = false;
        if( $interval->g1 == Level::GATE_OPEN && $interval->g2 == Level::GATE_OPEN ){
            
            $status = ( $interval->v1 < $value && $value < $interval->v2 );

        }else if( $interval->g1 == Level::GATE_OPEN && $interval->g2 == Level::GATE_CLOSED ){

            $status = ( $interval->v1 < $value && $value <= $interval->v2 );

        }else if( $interval->g1 == Level::GATE_CLOSED && $interval->g2 == Level::GATE_CLOSED ){

            $status = ( $interval->v1 <= $value && $value <= $interval->v2 );

        }else if( $interval->g1 == Level::GATE_CLOSED && $interval->g2 == Level::GATE_OPEN ){

            $status = ( $interval->v1 <= $value && $value < $interval->v2 );

        }

        return $status;
    }

    public static function evalInIntervals($intervals, $value)
    {
        $status = null;
        foreach($intervals as $interval)
        {
            if( static::evalInInterval($interval, $value) )
            {
                $status = $interval->value;
                break;
            }
        }
        return $status;
    }

}
