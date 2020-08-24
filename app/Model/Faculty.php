<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faculty extends Model
{
    use SoftDeletes;

    protected $fillable = [ 'name', 'initials' ];

    public function scopeSearch($query, $search )
    {
        if( trim($search)!=''){
            return $query->where('name', 'like', "%$search%")
            ;
        }
    }


    public function schools()
    {
        return $this->hasMany(School::class);
    }

    public function saveSchools( $array )
    {
        if( count($array) == 0) return 0;

        $ids =  [];
        foreach($array as $row){
            $id = (int)$row['id'];
            if( $id>0 ) $ids[] = $id; 
        }

        if( count($ids)>0 )
        {
            //eliminar las escuelas de la facultada excepto los ids actuales
            $deletes = $this->schools()->whereNotIn('id', $ids)->delete();
        }

        $count = 0;
        foreach($array as $row)
        {
            $id = (int)$row['id'];

            $school = School::find($id);
            if( $school==null) $school = new School;

            $school->name = $row['name'];
            $school->faculty_id = $this->id;

            if( $school->save() ) $count++;
        }

        return $count;
    }
}
