<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Model\Indicator;

class IndicatorResource extends JsonResource
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
            'name' => $this->name,
            'editable' => $this->editable,
            'type_id' => $this->type_id,
            'items' => $this->items->pluck('id'),
            'weight' => $this->weight,
        ];
    }


    

    // id: '',  
    // name: 'indicador #1', 
    // editable: true,
    // type: '1', 
    // type_name: 'Manual',
    // survey_id: '', 
    // survey_title: '', 
    // items: [],
    // value: 0,
}
