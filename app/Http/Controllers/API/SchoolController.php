<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\School;
use DB;

class SchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $items = School::search( $request->search )->paginate(10);
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
            'name' => "required|unique:schools,name,NULL,id,deleted_at,NULL",
        ]);

        DB::beginTransaction();
        try{
            $school = new School;
            $school->name = $request->name;
            $school->save();
            DB::commit();
            return response()->json($school);
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
        $item =  School::find($id);
        return $item;
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
            'name' => "required|unique:schools,name,{$id},id,deleted_at,NULL",
        ]);

        DB::beginTransaction();
        try{
            $school = School::findOrFail($id);
            $school->name = $request->name;
            $school->save();
            DB::commit();
            return response()->json($school);
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
        DB::beginTransaction();
        try{
            $school = School::findOrFail($id);
            $school->delete();
            DB::commit();
            return response()->json($school);
        }catch (\Exception $e) {
            DB::rollback();
            return response()->json( $e->getMessage(), 500 );
        }
    }
}
