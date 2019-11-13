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
        $episode = [
            'id' => $this->id,
            'program_id' => (int) $this->program_id,
            'title' => $this->title,
            'description' => $this->description
        ];

        if ($this->audios->first()->isNotEmpty()) {
            $episode['audio'] = $this->audios->audio_path;
        }

        if ($this->images->isNotEmpty()) {
            $episode['image'] = $this->images->image_path;
        }
       
        return $episode;
    }
}
