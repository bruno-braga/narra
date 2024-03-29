<?php

namespace App\Repository;

use Illuminate\Support\Facades\DB;

class EpisodeRepository implements EpisodeRepositoryInterface
{
    /**
     * Get room by slug
     *
     * @var Illuminate\Support\Collection
     */
    public function getRoomBySlug($slug)
    {
    }

    /**
     * Get all cities
     *
     * @var Illuminate\Support\Collection
     */
    public function getAll($includeDraft = false)
    {
        return DB::table('episodes')
            ->distinct()
            ->select(
                'episodes.id',
                'episodes.title',
                'episodes.is_draft',
                'episodes.program_id',
                DB::raw('CONCAT(audios.path, audios.filename) as audio'),
                DB::raw('CONCAT(images.path, images.filename) as image'),
                'episodes.description'
            )
            ->leftJoin('audios', 'episodes.id', '=', 'audios.audiable_id')
            ->leftJoin('images', 'episodes.id', '=', 'images.imageable_id')
            ->where('images.imageable_type', '=', 'App\Episode')
            ->orWhere('episodes.is_draft', '=', $includeDraft)
            ->get();
    }
}
