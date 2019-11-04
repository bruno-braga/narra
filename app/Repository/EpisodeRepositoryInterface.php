<?php

namespace App\Repository;

interface EpisodeRepositoryInterface
{
    public function getRoomBySlug($slug);
    public function getAll();
}
