<?php

namespace App\Http\Resources;

use App\Http\Resources\Attraction as AttractionResource;
use Illuminate\Http\Resources\Json\JsonResource;

class City extends JsonResource
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
            'attractions' => AttractionResource::collection($this->whenLoaded('attractions')),
        ];
    }
}
