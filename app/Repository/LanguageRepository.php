<?php

namespace App\Repository;

use Illuminate\Support\Facades\DB;

class LanguageRepository implements LanguageRepositoryInterface
{
    /**
     * Get all cities
     *
     * @var Illuminate\Support\Collection
     */
    public function getAll()
    {
        return DB::table('languages')->get();
    }
}
