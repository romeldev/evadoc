<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Scale extends Model
{
    protected $fillable = [ 'name', 'options' ];
    
    public function scopeSearch($query, $search )
    {
        if( trim($search)!=''){
            return $query->where('name', 'like', "%$search%")
            ;
        }
    }

    public function getOptionsAttribute( $value )
    {
        return json_decode($value);
    }
    
    public function setOptionsAttribute( $value )
    {
        $this->attributes['options'] = json_encode($value);
    } 
}
