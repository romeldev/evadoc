<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Model\Evaluation;
use App\Http\Resources\EvaluationResource;

class EvaluationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Evaluation::search( $request->search )->paginate(10);
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
            'title' => 'required',
            'descrip' => 'required',
            'period_id' => 'required|unique:evaluations,period_id',
            'date_start' => 'required|date',
            'date_end' => 'required|date',
            'level_id' => 'required',
            'status' => 'required|in:'.implode( ',', array_keys(Evaluation::arrayStatus()) ),
            'indicators' => 'required|array|min:1',
            'indicators.*.name' => 'required',
            'indicators.*.weight' => 'required|integer|gt:0',
        ]);

        DB::beginTransaction();
        try{
            $evaluation = new Evaluation;
            $evaluation->title = $request->title;
            $evaluation->descrip = $request->descrip;
            $evaluation->period_id = $request->period_id;
            $evaluation->period_text = $request->period_text;
            $evaluation->date_start = $request->date_start;
            $evaluation->date_end = $request->date_end;
            $evaluation->status = Evaluation::STATUS_STOPED;
            $evaluation->survey_id = trim($request->survey_id)==''? null: $request->survey_id;
            $evaluation->level_id = $request->level_id;
            $evaluation->save();

            $evaluation->saveIndicators($request->indicators);

            DB::commit();
            return response()->json($evaluation);
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
        return new EvaluationResource(Evaluation::findOrFail($id));
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
        // dd($request->all());
        $this->validate($request, [
            'title' => 'required',
            'descrip' => 'required',
            'period_id' => 'required|unique:evaluations,period_id,'.$id,
            'date_start' => 'required|date',
            'date_end' => 'required|date',
            'level_id' => 'required:level_id',
            'status' => 'required|in:'.implode( ',', array_keys(Evaluation::arrayStatus()) ),
            'indicators' => 'required|array|min:1',
            'indicators.*.name' => 'required',
            'indicators.*.weight' => 'required|integer|gt:0',
        ]);

        // Si la evaluacion ya tiene respuestas o calificaciones solo se podra editar los siguientes campos
        // fecha_fin, level, status, indicators only names

        $evaluation = Evaluation::findOrFail($id);


        // 1. si la encuesta de la evaluacion ya tiene al menos una respuesta:
        if( $evaluation->hasReplies() ){
            // 1.1 ya no se puede modificar la encuesta
            if( $evaluation->survey_id !== $request->survey_id ){
                return response()->json(
                    __('It is not possible to modify the survey, as it has stored responses.'), 400);
            }
            // 1.1 ya no se puede modificar el periodo
            if( $evaluation->period_id !== $request->period_id ){
                return response()->json(
                    __('It is not possible to modify the period, as it has stored responses.'), 400);
            }
        }

        // 2. si la evaluacion ya tiene al menos una calificacion de sus indicadores:
        if( $evaluation->hasQualifies() ){
            $indicators = $evaluation->indicators;
            // 2.1 No se puede eliminar ni agregar mas indicadores
            if( count($request->indicators) !== $indicators->count() ){
                return response()->json(
                    __('Can not add or remove indicators, because this evalaution has stored qualifies.'), 400);
            }
        }

        DB::beginTransaction();
        try{
            
            $evaluation->title = $request->title;
            $evaluation->descrip = $request->descrip;
            $evaluation->period_id = $request->period_id;
            $evaluation->period_text = $request->period_text;
            $evaluation->date_start = $request->date_start;
            $evaluation->date_end = $request->date_end;
            $evaluation->status = Evaluation::STATUS_STOPED;
            $evaluation->survey_id = trim($request->survey_id)==''? null: $request->survey_id;
            $evaluation->level_id = $request->level_id;
            $evaluation->status = $request->status;
            $evaluation->save();

            $evaluation->saveIndicators($request->indicators);

            DB::commit();
            return response()->json($evaluation);
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
            $evaluation = Evaluation::findOrFail($id);
            $evaluation->indicators()->delete();
            $evaluation->delete();
            DB::commit();

            return response()->json($evaluation);
        }catch (\Exception $e) {
            DB::rollback();
            return response()->json( $e->getMessage(), 500 );
        }
    }
}
