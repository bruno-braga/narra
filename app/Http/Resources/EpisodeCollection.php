<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EpisodeCollection extends JsonResource

{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'program_id' => $this->program_id,
            'title' => $this->title,
            'audio' => $this->audios->first()->audio_path,
            'description' => $this->description,
            'toggleOperation' => false
        ];
    }
}
