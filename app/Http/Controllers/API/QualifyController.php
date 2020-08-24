<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Model\Qualify;
use App\Http\Resources\QualifyResource;

class QualifyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
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
            'evaluation_id' => 'required|integer',
            'teacher_code' => 'required',
            'course_code' => 'required',
            'indicators' => 'required|array|min:1',
            'indicators.*.value' => 'required|numeric',
        ]);

        $id = (int) $request->id;
        if( $id > 0){
            return $this->update($request, $id);
        }

        DB::beginTransaction();
        try{
            $qualify = new Qualify;
            $qualify->evaluation_id = $request->evaluation_id;
            $qualify->teacher_code = $request->teacher_code;
            $qualify->course_code = $request->course_code;
            $qualify->save();
            DB::commit();
            return response()->json($qualify);
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
    public function show(Request $request, $id)
    {
        
        $qualify =  Qualify::where('evaluation_id', $request->evaluation_id)
            ->where('teacher_code', $request->teacher_code)
            ->where('course_code', $request->course_code)
            ->first();

        if($qualify==null) 
            $qualify = new Qualify;
            $qualify->evaluation_id = $request->evaluation_id;
            $qualify->teacher_code = $request->teacher_code;
            $qualify->course_code = $request->course_code;
       

        return new QualifyResource($qualify);
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
            'evaluation_id' => 'required|integer',
            'teacher_code' => 'required',
            'course_code' => 'required',
            'indicators' => 'required|array|min:1',
            'indicators.*.value' => 'required|numeric',
        ]);
        
        DB::beginTransaction();
        try{
            $qualify = Qualify::find($id);
            $qualify->evaluation_id = $request->evaluation_id;
            $qualify->teacher_code = $request->teacher_code;
            $qualify->course_code = $request->course_code;
            $qualify->save();
            DB::commit();
            return response()->json($qualify);
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
        dd('destroy');
    }
}
