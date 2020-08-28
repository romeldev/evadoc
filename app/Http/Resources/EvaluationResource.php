<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EvaluationResource extends JsonResource
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
            'survey_id' => $this->survey_id,
            'survey_text' => $this->survey->title,
            'level_id' => $this->level_id,
            'level_name' => $this->level->name,
            'status' => $this->status,
            'has_replies' => $this->hasReplies(),
            'has_qualifies' => $this->hasQualifies(),
            'indicators' => IndicatorResource::collection($this->indicators),
        ];
    }

}
