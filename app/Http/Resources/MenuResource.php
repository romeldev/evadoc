<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MenuResource extends JsonResource
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
            'order' => $this->order,
            'label' => $this->label,
            'path' => $this->path,
            'icon' => $this->icon,
            'menu_id' => $this->menu_id,
            'items' => MenuResource::collection($this->childrenMenus),
        ];
    }

}
