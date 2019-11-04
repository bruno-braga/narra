<?php

namespace App\Http\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Rss\RssBuilder;

use App\Repository\EpisodeRepositoryInterface;
use App\Repository\ProgramRepositoryInterface;

class HomeController extends Controller
{
    private $program;
    private $episode;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(EpisodeRepositoryInterface $episode, ProgramRepositoryInterface $program)
    {
        $this->middleware('auth');

        $this->program = $program;
        $this->episode= $episode;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function fd($id)
    {
        $episodes = $this
            ->episode
            ->getAll();

        $programs = $this
            ->program
            ->getProgramByIdWithEpisodes($id, $episodes, Auth::id());

        $dom = RssBuilder::build($programs, $episodes);

        Storage::disk('public')
            ->put(substr($programs->image, 8, 15) . '/rss.txt', $dom->saveXML());
    }
}
