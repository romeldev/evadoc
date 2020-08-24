<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QualifyResource extends JsonResource
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
            'evaluation_id' => $this->evaluation_id,
            'teacher_code' => $this->teacher_code,
            'course_code' => $this->course_code,
            'updated_at' => $this->updated_at,
            'indicators' => $this->fill_indicators,
        ];
    }
}
