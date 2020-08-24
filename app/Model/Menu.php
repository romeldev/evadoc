<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\MenuResource;

class Menu extends Model
{
    //

    protected $fillable = [ 'order', 'label', 'path', 'icon', 'menu_id' ];


    public static function tree()
    {
        $menus = cache()->rememberForever( _const('CACHE_MENU'), function(){
            return Menu::whereNull('menu_id')->with('childrenMenus')->orderBy('order', 'asc')->get();
        });

        return MenuResource::collection($menus);
    }

    public static function updateCache()
    {
        cache()->forget( _const('CACHE_MENU') );
        $menus = cache()->rememberForever( _const('CACHE_MENU'), function(){
            return Menu::whereNull('menu_id')->with('childrenMenus')->orderBy('order', 'asc')->get();
        });
    }

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }

    public function childrenMenus()
    {
        return $this->hasMany(Menu::class)->with('menus');
    }
}
