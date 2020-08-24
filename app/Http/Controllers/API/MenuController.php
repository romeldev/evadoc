<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Model\Menu;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $items = Menu::tree();
        return $items;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'order' => "required|numeric",
            'label' => "required",
            'path' => "required|unique:menus,path,NULL,id",
        ]);

        DB::beginTransaction();
        try{
            $menu = new Menu;
            $menu->order = $request->order;
            $menu->label = $request->label;
            $menu->path = $request->path;
            $menu->icon = $request->icon;
            $menu->menu_id = $request->menu_id? $request->menu_id: null;
            $menu->save();
            Menu::updateCache();

            DB::commit();
            return response()->json($menu);
        }catch (\Exception $e) {
            DB::rollback();
            return response()->json( $e->getMessage(), 500 );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $menu =  Menu::find($id);
        return $menu;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'order' => "required|numeric",
            'label' => "required",
            'path' => "required|unique:menus,path,{$id},id",
        ]);

        DB::beginTransaction();
        try{
            $menu = Menu::findOrFail($id);
            $menu->order = $request->order;
            $menu->label = $request->label;
            $menu->path = $request->path;
            $menu->icon = $request->icon;
            $menu->menu_id = $request->menu_id? $request->menu_id: null;
            $menu->save();
            Menu::updateCache();

            DB::commit();
            return response()->json($menu);
        }catch (\Exception $e) {
            DB::rollback();
            return response()->json( $e->getMessage(), 500 );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $this->validate(request(), [
        //     'id' => new MenuHasNotDependences(),
        // ]);

        DB::beginTransaction();
        try{
            $menu = Menu::findOrFail($id);
            $menu->delete();
            Menu::updateCache();
            DB::commit();
            return response()->json($menu);
        }catch (\Exception $e) {
            DB::rollback();
            return response()->json( $e->getMessage(), 500 );
        }
    }
}
