<?php

namespace App\Repository;

use Illuminate\Support\Facades\DB;

use App\Http\Resources\EpisodeCollection;

use App\Program;

class ProgramRepository implements ProgramRepositoryInterface
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
    public function getAll()
    {
        return DB::table('programs')
            ->select(
                'programs.id',
                'programs.title',
                DB::RAW('IFNULL(' .
                    DB::raw('CONCAT(images.path, images.filename)')
                    . ', "/storage/default-podcast.png") as image'
                )
            )
            ->leftJoin('images', 'programs.id', '=', 'images.imageable_id')
            ->get();
    }

    /**
     * Get all cities
     *
     * @var Illuminate\Support\Collection
     */
    public function getUserPrograms($userId, $programId = null)
    {
          $table = DB::table('programs');

          if (!is_null($programId)) {
              $table->where('programs.id', $programId);
          }

          return $table->select(
                'programs.id',
                'programs.title',
                'programs.description',
                DB::raw('CONCAT(images.path, images.filename) as image')
            )
            ->leftJoin('images', 'programs.id', '=', 'images.imageable_id')
            ->where('images.imageable_type', '=', 'App\Program')
            ->where('user_id', $userId)
            ->get();
    }

    public function getProgramsWithEpisodes($episodes, $userId)
    {
        $programs = $this->getUserPrograms($userId);

        $programsClone = $programs;
        foreach($programs as  $programKey => $program) {
          $programsClone[$programKey]->episodes = [];
          foreach($episodes as $episodeKey => $episode) {
            if ($episode->program_id == $program->id) {
              $programsClone[$programKey]->episodes[$episodeKey] = $episode;
            }
          }
        }

        return $programsClone;
    }

    public function getProgramByIdWithEpisodes($programId, $episodes, $userId)
    {
        $programs = $this->getUserPrograms($userId, $programId);

        $programsClone = $programs;
        foreach($programs as  $programKey => $program) {
          $programsClone[$programKey]->episodes = [];
          foreach($episodes as $episodeKey => $episode) {
            if ($episode->program_id == $program->id) {
              $programsClone[$programKey]->episodes[$episodeKey] = $episode;
            }
          }
        }

        return $programsClone->first();
    }
}
