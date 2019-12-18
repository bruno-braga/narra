<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


use App\Http\Requests\EpisodeRequest;
use App\Repository\EpisodeRepositoryInterface;
use App\Repository\ProgramRepositoryInterface;

use App\Audio;

use App\Episode;
use App\Program;

use App\Http\Resources\EpisodeCollection;

use Illuminate\Support\Facades\DB;

class EpisodesController extends Controller
{
    private $episode;
    private $program;

    public function __construct(EpisodeRepositoryInterface $episode, ProgramRepositoryInterface $program)
    {
        $this->episode = $episode;
        $this->program = $program;

        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $programs = Program::select(['programs.id', 'programs.title'])
            ->where('programs.user_id', Auth::id())
            ->with([
                'episodes' => function($query) {
                  $query->select('id', 'program_id', 'title', 'is_draft', 'description')
                      ->with([
                          'images' => function($query) {
                              $query->select('imageable_id', DB::raw('CONCAT(images.path, images.filename) as path'));
                          },
                          'audios' => function($query) {
                              $query->select('audiable_id', DB::raw('CONCAT(audios.path, audios.filename) as path'));
                          }
                      ]);
                }
            ])
            ->get()
            ->toArray();

        return view(
            'episodes.index',
            [
                'programs' => $programs
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view(
            'episodes.create',
            [
                'programs' => Program::all()
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EpisodeRequest $request)
    {
        Episode::$file = $request->file('file');
        Episode::$cover = $request->file('cover');

        $data = $request->only([
            'title',
            'program_id',
            'description',
            'type',
            'size'
        ]);

        $data['duration'] = (int) $request->input('duration');

        $episode = new Episode($data);
        $episode->save();

        return response()->json(new EpisodeCollection($episode));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Episode $episode)
    {
        return view(
            'episodes.edit',
            [ 
                'episode' => $episode,
                'programs' => Program::all()
            ]
        );

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Episode::$cover= $request->file('cover');
        Episode::$file = $request->file('file');

        $episode = Episode::find($id);

        $data = $request->only([
            'title',
            'program_id',
            'description',
            'type',
            'size'
        ]);

        $data['duration'] = (int) $request->input('duration');

        $episode->update($data);

        return response()->json(200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Episode $episode)
    {
        $program = Program::find($episode->program_id);

        $isOwnedByAuthUser = $program->first()->user_id == Auth::id();
        if (!$isOwnedByAuthUser) {
            return response()->json(401);
        }

        if ($episode->delete()) {
          return response()->json(204);
        } 

        return respnse()->json(404);
    }
}
