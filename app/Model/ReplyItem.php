<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ReplyItem extends Model
{
    protected $fillable = [
        'reply_id',
        'item_id',
        'value', 
    ];
}
