<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repository\EpisodeRepository;
use App\Repository\EpisodeRepositoryInterface;

use App\Repository\ProgramRepository;
use App\Repository\ProgramRepositoryInterface;

use App\Repository\LanguageRepository;
use App\Repository\LanguageRepositoryInterface;

class RepositoryProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            EpisodeRepositoryInterface::class,
            EpisodeRepository::class
        );

        $this->app->bind(
            ProgramRepositoryInterface::class,
            ProgramRepository::class
        );

        $this->app->bind(
            LanguageRepositoryInterface::class,
            LanguageRepository::class
        );
    }
}
