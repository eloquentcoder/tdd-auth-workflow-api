<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'featuredImage' => $this->featured_image,
            'isFeatured' => $this->is_featured,
            'productId' => $this->product_id,
            'tags' => $this->tags,
            'otherImages' => $this->other_images,
            'category' => $this->category->title
        ];
    }
}
