<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Model\Scale;
use App\Http\Resources\ScaleResource;
use App\Rules\IsReferencedScale;

class ScaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $items = Scale::search( $request->search )->paginate(10);
        return ScaleResource::collection( $items );
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
            'name' => "required|unique:users,name,NULL,id",
            'options' =>'required|array|min:1',
            'options.*.value' =>'required|integer',
            'options.*.text' =>'required',
        ]);

        DB::beginTransaction();
        try{
            $scale = new Scale;
            $scale->name = $request->name;
            $scale->options = $request->options;
            $scale->save();
            DB::commit();
            return response()->json($scale);
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
        $scale =  Survey::find($id);
        return $scale;
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
            'options' =>'required|array|min:1',
            'options.*.value' =>'required|integer',
            'options.*.text' =>'required',
        ]);

        DB::beginTransaction();
        try{
            $scale = Scale::findOrFail($id);
            $scale->name = $request->name;
            $scale->options = $request->options;
            $scale->save();
            DB::commit();
            return response()->json($scale);
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
            'id' => new IsReferencedScale(),
        ]);

        DB::beginTransaction();
        try{
            $scale = Scale::findOrFail($id);
            $scale->delete();
            DB::commit();
            return response()->json($scale);
        }catch (\Exception $e) {
            DB::rollback();
            return response()->json( $e->getMessage(), 500 );
        }
    }
}
