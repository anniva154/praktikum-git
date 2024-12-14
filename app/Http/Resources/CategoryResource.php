<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'slug' => $this->slug,
            'is_parent' => $this->is_parent == 1 ? 'Yes' : 'No',
            'parent_category' => $this->parent_info->title ?? null,
            'photo' => $this->photo ?? asset('backend/img/thumbnail-default.jpg'),
            'status' => $this->status,
        ];
    }
}
