<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StdSurveyResource extends JsonResource
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
            'items' => $this->items()->select('id', 'name', 'order')->get(),
            'scale' => $this->scale,
        ];
    }

}
