<?php

namespace App\Http\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Rss\RssBuilder;

use App\Repository\EpisodeRepositoryInterface;
use App\Repository\ProgramRepositoryInterface;

use App\Program;

use Carbon\Carbon;

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
        $this->episode = $episode;
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

    public function program($slug)
    {
        $program = Program::where('slug', $slug);
        if (!$program->exists()) {
            abort(404);
        }

        dd($program->get()->toArray());
    }

    public function fd($id)
    {
        $program = Program::select(['programs.id', 'programs.user_id', 'programs.title', 'programs.slug', 'programs.description', 'programs.folder'])
            ->where('programs.user_id', Auth::id())
            ->where('programs.id', $id)
            ->with([
                'categories' => function($query) {
                    $query->select('categories.id', 'categories.name', 'categories.parent_id');
                },
                'episodes' => function($query) {
                    $query->select('episodes.id', 'episodes.program_id', 'title', 'description', 'duration', 'size', 'type', 'updated_at')
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
                'images' => function($query) {
                    $query->select('imageable_id', DB::raw('CONCAT(images.path, images.filename) as path'));
                },
                'settings' => function($query) {
                    $query->select('id', 'program_id', 'explicit');
                }
            ])
            ->get()
            ->first();

        $dom = RssBuilder::build($program);

        Storage::disk('public')
            ->put(substr($program->images->path, 8, 15) . '/rss', $dom->saveXML());

        return ('home');
    }
}
