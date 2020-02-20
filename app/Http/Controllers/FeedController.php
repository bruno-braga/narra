<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Rss\RssBuilder;

use App\Program;

class FeedController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($slug)
    {
        $program = Program::select(['programs.id', 'programs.user_id', 'programs.title', 'programs.slug', 'programs.description', 'programs.folder'])
            ->where('programs.user_id', Auth::id())
            ->where('programs.slug', $slug)
            ->with([
                'categories' => function($query) {
                    $query->select('categories.id', 'categories.name', 'categories.parent_id');
                },
                'episodes' => function($query) {
                    $query->select('episodes.id', 'episodes.program_id', 'title', 'slug', 'description', 'duration', 'size', 'type', 'updated_at')
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
                    $query->select('id', 'program_id', 'language_id', 'explicit')->with('language');
                }
            ])
            ->get()
            ->first();

        $dom = RssBuilder::build($program);

        Storage::disk('public')
            ->put(substr($program->images->path, 8, 15) . '/rss', $dom->saveXML());
    }
}
