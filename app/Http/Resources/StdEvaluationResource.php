<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StdEvaluationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'descrip' => $this->descrip,
            'period_id' => $this->period_id,
            'period_text' => $this->period_text,
            'date_start' => $this->date_start,
            'date_end' => $this->date_end,
            'survey' => new StdSurveyResource($this->survey),
        ];
    }

}
