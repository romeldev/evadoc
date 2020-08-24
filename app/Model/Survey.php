<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Survey extends Model
{
    use SoftDeletes;

    protected $fillable = [ 'title', 'descrip', 'scale_id', 'level_id' ];

    public function scopeSearch($query, $search )
    {
        if( trim($search)!=''){
            return $query->where('title', 'like', "%$search%")
                // ->orWhere('name', 'like', "%$search%")
            ;
        }
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }

    public function scale()
    {
        return $this->belongsTo(Scale::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function saveItems( $array=[] )
    {
        if( count($array) == 0) return 0;

        $ids =  [];
        foreach($array as $row){
            $id = (int)$row['id'];
            if( $id>0 ) $ids[] = $id; 
        }

        if( count($ids)>0 )
        {
            $deletes = $this->items()->whereNotIn('id', $ids)->delete();
        }

        $count = 0;
        foreach($array as $key => $row)
        {
            $id = (int)$row['id'];

            $item = Item::find($id);
            if( $item==null) $item = new Item;

            $item->order = $key+1;
            $item->name = $row['name'];
            $item->value = $row['value'];
            $item->survey_id = $this->id;

            if( $item->save() ) $count++;
        }

        return $count;
    }

}
