<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Model\Survey;
use App\Rules\HasReferencedItems;
use App\Rules\IsReferencedSurvey;

class SurveyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Survey::search( $request->search )->paginate(10);
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
            'title' => 'required|unique:surveys,title',
            'descrip' => 'required',
            'scale_id' => 'required',
            'level_id' => 'required',
            'items' => 'required|array|min:1',
            'items.*.name' => 'required',
            'items.*.value' => 'required|integer|gt:0',
        ]);


        DB::beginTransaction();
        try{
            $survey = new Survey;
            $survey->title = $request->title;
            $survey->descrip = $request->descrip;
            $survey->scale_id = $request->scale_id;
            $survey->level_id = $request->level_id;
            $survey->save();
            $survey->saveItems($request->items);
            DB::commit();
            return response()->json($survey);
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
        $survey =  Survey::find($id);
        $survey->items = $survey->items()->select('id', 'name', 'value')->orderBy('order')->get();
        return $survey;
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
            'title' => 'required|unique:surveys,title,'.$id,
            'descrip' => 'required',
            'items' => 'required|array|min:1',
            'items' => new HasReferencedItems( $id ),
            'items.*.name' => 'required',
            'items.*.value' => 'required|integer|gt:0',
        ]);

        DB::beginTransaction();
        try{
            $survey = Survey::findOrFail($id);
            $survey->title = $request->title;
            $survey->descrip = $request->descrip;
            $survey->scale_id = $request->scale_id;
            $survey->level_id = $request->level_id;
            $survey->save();
            $survey->saveItems($request->items);
            DB::commit();
            return response()->json($survey);
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
            'id' => new IsReferencedSurvey( $id ),
        ]);

        DB::beginTransaction();
        try{
            $survey = Survey::findOrFail($id);
            $survey->items()->delete();
            $survey->delete();
            DB::commit();
            return response()->json($survey);
        }catch (\Exception $e) {
            DB::rollback();
            return response()->json( $e->getMessage(), 500 );
        }
    }
}
