<?php

namespace App\Repository;

interface ProgramRepositoryInterface
{
    public function getRoomBySlug($slug);
    public function getAll();
}
