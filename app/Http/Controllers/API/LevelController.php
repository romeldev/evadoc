<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

use App\Model\Level;
use App\Http\Resources\LevelResource;
use App\Rules\IsReferencedLevel;

class LevelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $items = Level::search( $request->search )->paginate(10);
        return LevelResource::collection($items);
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
            'name' => "required|unique:levels,name,NULL,id",
            'intervals' => 'required|array|min:1',
            'intervals.*.g1' => 'required|in:0,1',
            'intervals.*.g2' => 'required|in:0,1',
            'intervals.*.v1' => 'required|numeric',
            'intervals.*.v2' => 'required|numeric',
            'intervals.*.value' => 'required',
        ]);

        DB::beginTransaction();
        try{
            $level = new Level;
            $level->name = $request->name;
            $level->intervals = $request->intervals;
            $level->save();
            DB::commit();
            return response()->json($level);
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
        $level =  Level::find($id);
        return $level;
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
            'name' => "required|unique:scales,name,{$id},id",
            'intervals' => 'required|array|min:1',
            'intervals.*.g1' => 'required|in:0,1',
            'intervals.*.g2' => 'required|in:0,1',
            'intervals.*.v1' => 'required|numeric',
            'intervals.*.v2' => 'required|numeric',
            'intervals.*.value' => 'required',
        ]);

        DB::beginTransaction();
        try{
            $level = Level::findOrFail($id);
            $level->name = $request->name;
            $level->intervals = $request->intervals;
            $level->save();
            DB::commit();
            return response()->json($level);
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
        $this->validate(request(), [
            'id' => new IsReferencedLevel(),
        ]);

        DB::beginTransaction();
        try{
            $level = Level::findOrFail($id);
            $level->delete();
            DB::commit();
            return response()->json($level);
        }catch (\Exception $e) {
            DB::rollback();
            return response()->json( $e->getMessage(), 500 );
        }
    }
}
