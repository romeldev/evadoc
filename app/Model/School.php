<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class School extends Model
{
    use SoftDeletes;

    protected $fillable = [ 'name', 'faculty_id'];


    public function Faculty()
    {
        return $this->belongsTo(School::class);
    }
}
