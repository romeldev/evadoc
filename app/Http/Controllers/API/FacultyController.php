<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Model\Faculty;
use App\Http\Resources\FacultyResource;

class FacultyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $items = Faculty::search( $request->search)->latest()->paginate(10);
        return FacultyResource::collection($items);
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
            'name' => "required|unique:faculties,name,NULL,id,deleted_at,NULL",
            'schools' => 'sometimes|array',
            'schools.*.name' => 'sometimes|required',
        ]);

        DB::beginTransaction();
        try{
            $faculty = new Faculty;
            $faculty->name = $request->name;
            $faculty->initials = $request->initials;
            $faculty->save();
            $faculty->saveSchools($request->schools);
            DB::commit();
            return response()->json($faculty);
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
        $item =  Faculty::find($id);
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
            'name' => "required|unique:faculties,name,{$id},id,deleted_at,NULL",
            'schools' => 'sometimes|array',
            'schools.*.name' => 'sometimes|required',
        ]);

        DB::beginTransaction();
        try{
            $faculty = Faculty::findOrFail($id);
            $faculty->name = $request->name;
            $faculty->initials = $request->initials;
            $faculty->save();
            $faculty->saveSchools($request->schools);
            DB::commit();
            return response()->json($faculty);
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
            $faculty = Faculty::findOrFail($id);
            $faculty->schools()->delete();
            $faculty->delete();
            DB::commit();
            return response()->json($faculty);
        }catch (\Exception $e) {
            DB::rollback();
            return response()->json( $e->getMessage(), 500 );
        }
    }
}
