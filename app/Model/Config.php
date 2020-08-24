<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    public $table = 'config';
    public $timestamps = false;

    // protected $primaryKey = 'key';

    protected $fillable = [ 'key', 'value', 'type' ];

    const TYPE_VAR = 0;
    const TYPE_CONST = 1;

    public static function listCache()
    {
        $data = Config::where('type', self::TYPE_CONST)->select('key', 'label')->get();
        return $data;
    }

    public static function listVars()
    {
        $data = Config::where('type', self::TYPE_VAR)->select('key', 'label', 'value')->get();
        return $data;
    }

    public static function clearCache( $key )
    {
        return cache()->forget($key);
    }

    public static function saveVar( $key, $value)
    {
        return Config::where('type', self::TYPE_VAR)
            ->where('key', $key)
            ->update(['value'=>$value]);
    }

}
