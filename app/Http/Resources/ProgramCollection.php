<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProgramCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $program = [
            'id' => $this->id,
            'title' => $this->title,
            'image' => '/storage/default-podcast.png',
            'description' => $this->description
        ];

        if ($this->images()->exists()) {
            $program['image'] = $this->images->image_path;
        }

        return $program;
    }
}
