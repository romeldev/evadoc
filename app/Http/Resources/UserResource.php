<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'fullname' => $this->fullname,
            'name' => $this->name,
            'email' => $this->email,
            'photo' => $this->avatar,
            'created_at' => $this->created_at,
            'school_code' => $this->school_code,
            'roles' => $this->roles->pluck('name', 'id'),
        ];
    }
}
