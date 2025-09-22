<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
       return [
            'id'          => $this->id,
            'name'        => $this->name,
            'description' => $this->description,
            'hide' =>$this->hide,
            'seo_name'        => $this->seo_name,
            'seo_description' => $this->seo_description,
            
            'created_at'  => $this->created_at->toDateTimeString(),
        ];
    }
}