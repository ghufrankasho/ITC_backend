<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'subcategory_id' => $this->subcategory_id,
            'image' =>$this->image,
            'file' =>$this->file,
            'hide' =>$this->hide,
            'created_at'  => $this->created_at->toDateTimeString(),
        ];
    }
}