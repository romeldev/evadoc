<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class QualifyIndicator extends Model
{
    protected $table = 'qualify_indicator';

    protected $fillable = [ 'qualify_id', 'indicator_id', 'value' ];

    public function qualify()
    {
        return $this->belongsTo(Qualify::class)->withDefault();
    }

    public function indicator()
    {
        return $this->belongsTo(Indicator::class)->withDefault();
    }
}
