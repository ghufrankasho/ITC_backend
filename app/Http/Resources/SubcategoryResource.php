<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubcategoryResource extends JsonResource
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
            
            'seo_name'        => $this->seo_name,
            'seo_description' => $this->seo_description,
            'category_id' => $this->category_id,
            'created_at'  => $this->created_at->toDateTimeString(),
        ];
    }
}