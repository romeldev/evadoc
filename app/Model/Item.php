<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use SoftDeletes;
    
    protected $fillable = [ 'name', 'order', 'value', 'survey_id' ];


    public function indicators()
    {
        return $this->belongsToMany(Indicator::class, 'indicator_items');
    }
}
