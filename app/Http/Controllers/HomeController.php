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
        $program = Program::select(['programs.id', 'programs.title', 'programs.description'])
            ->where('programs.user_id', Auth::id())
            ->where('programs.id', $id)
            ->with([
                'episodes' => function($query) {
                  $query->select('id', 'program_id', 'title', 'description')
                      ->where('is_draft', false)
                      ->with([
                          'images' => function($query) {
                              $query->select('imageable_id', DB::raw('CONCAT(images.path, images.filename) as path'));
                          },
                          'audios' => function($query) {
                              $query->select('audiable_id', DB::raw('CONCAT(audios.path, audios.filename) as path'));
                          }
                      ]);
                },
                'settings' => function($query) {
                    $query->select('id', 'program_id');
                }
            ])
            ->get()
            ->first();

        dd($program->toArray());

        $dom = RssBuilder::build($program);

        Storage::disk('public')
            ->put(substr($programs->image, 8, 15) . '/rss.txt', $dom->saveXML());
    }
}
